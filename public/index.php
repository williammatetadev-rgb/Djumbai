<?php
/**
 * Djumbai — Ponto de Entrada Principal & Front Controller
 * Inicialização com blindagem de segurança, headers, sessões e tratamento de erros
 */

// Define os caminhos absolutos do projecto
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
define('BASE_URL',  '/djumbai/public');

// 1. Carrega o gestor de variáveis de ambiente
require_once APP_PATH . '/core/Env.php';
Env::load(ROOT_PATH . '/.env');

// 2. Define o ambiente e configuração de erros
$appEnv   = Env::get('APP_ENV', 'development');
$appDebug = Env::get('APP_DEBUG', 'true') === 'true';

if ($appDebug && $appEnv === 'development') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
}

// 3. Aplica cabeçalhos de segurança (CSP, X-Frame-Options, X-Content-Type-Options, etc.)
require_once APP_PATH . '/core/SecurityHeaders.php';
SecurityHeaders::apply();

// 4. Configura e inicia sessões de forma estritamente blindada
if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
               ($_SERVER['SERVER_PORT'] ?? 80) == 443;

    $lifetime = (int) Env::get('SESSION_LIFETIME', 7200);

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');

    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

// 5. Carrega o núcleo do sistema
require_once APP_PATH . '/core/SecurityLogger.php';
require_once APP_PATH . '/core/RateLimiter.php';
require_once ROOT_PATH . '/config/database.php';
require_once APP_PATH . '/core/Router.php';
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/core/helpers.php';

// 6. Manipulador global de excepções não tratadas (evita expor stack traces a utilizadores)
set_exception_handler(function (Throwable $e) use ($appDebug) {
    SecurityLogger::log('UNCAUGHT_EXCEPTION', $e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);

    http_response_code(500);

    if ($appDebug) {
        echo '<div style="background:#FEE;border:2px solid red;padding:20px;margin:20px;font-family:monospace;">';
        echo '<h3>Excepção Não Tratada (Modo Debug):</h3>';
        echo '<p><strong>Mensagem:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>Ficheiro:</strong> ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        echo '</div>';
    } else {
        echo '<!DOCTYPE html><html lang="pt"><head><meta charset="UTF-8"><title>Erro no Sistema</title></head><body style="font-family:sans-serif;text-align:center;padding:50px;"><h2>Ocorreu um erro temporário</h2><p>A nossa equipa já foi notificada. Por favor tente novamente dentro de instantes.</p><a href="' . BASE_URL . '/">Voltar ao Início</a></body></html>';
    }
});

// 7. Verificação de Modo de Manutenção (Acesso público restrito a administradores)
$isManutencao = (bool) siteSetting('modo_manutencao', false);
if ($isManutencao && !isAdmin()) {
    $currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '/djumbai/public';
    $path = str_replace($base, '', $currentUri);
    $path = '/' . trim($path, '/');
    if ($path === '//') $path = '/';

    $allowedInMaintenance = ['/login', '/logout', '/admin'];
    $isAllowed = false;
    foreach ($allowedInMaintenance as $allowed) {
        if ($path === $allowed || str_starts_with($path, '/admin/')) {
            $isAllowed = true;
            break;
        }
    }

    if (!$isAllowed) {
        require APP_PATH . '/views/errors/manutencao.php';
        exit;
    }
}

// 8. Inicia e despacha as rotas do Router
$router = new Router();

// ─── Rotas Públicas ────────────────────────────────────────────
$router->get('/',                            'HomeController',     'index');
$router->get('/como-funciona',               'HomeController',     'comoFunciona');

// ─── Autenticação ──────────────────────────────────────────────
$router->get( '/login',                      'AuthController',     'loginForm');
$router->post('/login',                      'AuthController',     'login');
$router->get( '/cadastro',                   'AuthController',     'cadastroForm');
$router->post('/cadastro',                   'AuthController',     'cadastro');
$router->get( '/logout',                     'AuthController',     'logout');

// ─── Painel & Perfil do Cidadão ──────────────────────────────────
$router->get( '/perfil',                     'UserController',     'dashboard');
$router->get( '/perfil/definicoes',          'UserController',     'settings');
$router->post('/perfil/definicoes',          'UserController',     'salvarSettings');
$router->post('/notificacoes/{id}/ler',      'UserController',     'marcarLida');

// ─── Problemas & Comentários (Cidadão) ───────────────────────────
$router->get( '/mapa',                       'ProblemaController', 'mapa');
$router->get( '/api/mapa-ocorrencias',       'ProblemaController', 'apiPontosMapa');
$router->get( '/problemas',                  'ProblemaController', 'index');
$router->get( '/problemas/{id}',             'ProblemaController', 'show');
$router->get( '/reportar',                   'ProblemaController', 'criar');
$router->post('/reportar',                   'ProblemaController', 'guardar');
$router->post('/confirmar/{id}',             'ProblemaController', 'confirmar');
$router->post('/problemas/{id}/comentar',    'ProblemaController', 'comentar');

// ─── Painel Admin & Moderação ──────────────────────────────────
$router->get( '/admin',                          'AdminController',    'dashboard');
$router->get( '/admin/problemas',                'AdminController',    'problemas');
$router->post('/admin/problemas/{id}/estado',     'AdminController',    'mudarEstado');
$router->get( '/admin/usuarios',                 'AdminController',    'usuarios');
$router->post('/admin/usuarios/{id}/banir',      'AdminController',    'toggleBan');
$router->post('/admin/usuarios/{id}/notificar',  'AdminController',    'notificarBonsModos');
$router->post('/admin/usuarios/{id}/eliminar',   'AdminController',    'eliminarUsuario');
$router->get( '/admin/comentarios',              'AdminController',    'comentarios');
$router->post('/admin/comentarios/{id}/eliminar', 'AdminController',    'eliminarComentario');
$router->get( '/admin/definicoes',               'AdminController',    'definicoes');
$router->post('/admin/definicoes',               'AdminController',    'salvarDefinicoes');

// Executa a rota
$router->dispatch();
