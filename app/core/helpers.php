<?php
/**
 * Djumbai — Funções Auxiliares Globais com Blindagem de Segurança
 */

// ── Segurança & Output Encoding (Anti-XSS) ─────────────────────

/**
 * Escapa caracteres HTML para exibição segura (Prevenção XSS)
 */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Sanitiza strings de entrada removendo caracteres de controle invisíveis
 */
function sanitizeString(string $input): string
{
    return trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $input));
}

/**
 * Gera a URL completa segura
 */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Gera a URL de um asset
 */
function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

// ── Flash Messages ─────────────────────────────────────────────

function flash(string $key, string $message): void
{
    $_SESSION['flash_' . $key] = $message;
}

function getFlash(string $key): ?string
{
    $sessionKey = 'flash_' . $key;
    if (!empty($_SESSION[$sessionKey])) {
        $msg = $_SESSION[$sessionKey];
        unset($_SESSION[$sessionKey]);
        return $msg;
    }
    return null;
}

// ── Datas & Tempo ──────────────────────────────────────────────

function timeAgo(string $datetime): string
{
    $now  = new DateTime();
    $past = new DateTime($datetime);
    $diff = $now->getTimestamp() - $past->getTimestamp();

    return match (true) {
        $diff <  60        => 'Agora mesmo',
        $diff <  3600      => 'Há ' . max(1, round($diff / 60)) . ' min',
        $diff <  86400     => 'Há ' . max(1, round($diff / 3600)) . 'h',
        $diff <  604800    => 'Há ' . max(1, round($diff / 86400)) . ' dias',
        $diff <  2592000   => 'Há ' . max(1, round($diff / 604800)) . ' sem.',
        $diff <  31536000  => 'Há ' . max(1, round($diff / 2592000)) . ' meses',
        default            => $past->format('d/m/Y'),
    };
}

function dataAngolana(string $datetime): string
{
    $meses = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março',
        4 => 'Abril',   5 => 'Maio',      6 => 'Junho',
        7 => 'Julho',   8 => 'Agosto',    9 => 'Setembro',
        10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
    ];
    $d = new DateTime($datetime);
    return $d->format('d') . ' de ' . $meses[(int)$d->format('m')] . ' de ' . $d->format('Y');
}

// ── Validação & Autenticação ───────────────────────────────────

function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida se um nome é um nome humano válido (apenas letras, acentos e espaços; sem números ou códigos aleatórios)
 */
function isValidHumanName(string $name): bool
{
    $name = trim($name);
    if (mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 100) {
        return false;
    }
    // Rejeitar qualquer nome que contenha dígitos numéricos
    if (preg_match('/[0-9]/', $name)) {
        return false;
    }
    // Permitir apenas letras (incluindo UTF-8 com acentos), espaços, hífen e apóstrofos
    return (bool) preg_match('/^[\p{L}\s\-\']{2,100}$/u', $name);
}

/**
 * Valida se o telefone (caso fornecido) é um número válido de Angola
 */
function isValidAngolaPhone(string $phone): bool
{
    $clean = preg_replace('/\D/', '', $phone);
    if (empty($clean)) return true;
    if (str_starts_with($clean, '244')) {
        $clean = substr($clean, 3);
    }
    return (bool) preg_match('/^(9\d{8}|222\d{6})$/', $clean);
}

function isLoggedIn(): bool
{
    return !empty($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
}

function isAdmin(): bool
{
    return isLoggedIn() && (($_SESSION['user_tipo'] ?? '') === 'admin');
}

function currentUser(): ?array
{
    if (!isLoggedIn()) return null;
    return [
        'id'   => (int) $_SESSION['user_id'],
        'nome' => $_SESSION['user_nome'] ?? 'Utilizador',
        'tipo' => $_SESSION['user_tipo'] ?? 'cidadao',
    ];
}

// ── CSRF Protection Completa (Forms + AJAX) ────────────────────

/**
 * Gera um token CSRF criptograficamente seguro com tempo de expiração
 */
function csrfToken(): string
{
    if (empty($_SESSION['_csrf_token']) || empty($_SESSION['_csrf_token_time']) || (time() - $_SESSION['_csrf_token_time'] > 3600)) {
        $_SESSION['_csrf_token']      = bin2hex(random_bytes(32));
        $_SESSION['_csrf_token_time'] = time();
    }
    return $_SESSION['_csrf_token'];
}

/**
 * Valida o token CSRF de formulários POST ou cabeçalhos HTTP X-CSRF-Token
 */
function verifyCsrf(): bool
{
    $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (empty($token) || empty($_SESSION['_csrf_token'])) {
        SecurityLogger::log('CSRF_VIOLATION', 'Tentativa de submissão sem token CSRF válido.');
        return false;
    }

    $valid = hash_equals($_SESSION['_csrf_token'], (string) $token);
    if (!$valid) {
        SecurityLogger::log('CSRF_VIOLATION', 'Token CSRF não corresponde ao token da sessão.');
    }
    return $valid;
}

/**
 * Gera o campo oculto HTML com o token CSRF
 */
function csrfField(): string
{
    return '<input type="hidden" name="_csrf_token" value="' . e(csrfToken()) . '">';
}

/**
 * Verifica se o caminho actual corresponde à rota especificada
 */
function isCurrentPage(string $path): bool
{
    $uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '/djumbai/public';
    $uri  = str_replace($base, '', $uri);
    $uri  = '/' . trim($uri, '/');
    if ($uri === '//') $uri = '/';
    return $path === $uri;
}

/**
 * Renderiza um ícone SVG offline da pasta assets/icons
 */
function icon(string $name, int $size = 16, string $class = '', string $color = 'currentColor'): string
{
    $path = ROOT_PATH . '/public/assets/icons/' . $name . '.svg';
    if (!file_exists($path)) {
        return '';
    }
    $svg = file_get_contents($path);
    $svg = preg_replace('/width="\d+"/', 'width="' . $size . '"', $svg);
    $svg = preg_replace('/height="\d+"/', 'height="' . $size . '"', $svg);
    return '<span class="dj-icon ' . e($class) . '" style="display:inline-flex;align-items:center;justify-content:center;vertical-align:middle;color:' . e($color) . ';" aria-hidden="true">' . $svg . '</span>';
}

// ── Definições do Sistema (Settings) ──────────────────────────

/**
 * Obtém uma definição global do sistema
 */
function siteSetting(string $key, mixed $default = null): mixed
{
    static $settings = null;
    if ($settings === null) {
        $configFile = ROOT_PATH . '/storage/config/site_settings.json';
        if (file_exists($configFile)) {
            $settings = json_decode(file_get_contents($configFile), true) ?? [];
        } else {
            $settings = [];
        }
    }
    return $settings[$key] ?? $default;
}

/**
 * Obtém todas as definições globais do sistema
 */
function allSiteSettings(): array
{
    $configFile = ROOT_PATH . '/storage/config/site_settings.json';
    if (file_exists($configFile)) {
        return json_decode(file_get_contents($configFile), true) ?? [];
    }
    return [];
}
