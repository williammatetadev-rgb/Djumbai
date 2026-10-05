<?php
/**
 * Djumbai — AuthController Blindado contra Ataques de Força Bruta & CSRF
 */

require_once APP_PATH . '/Models/UserModel.php';

class AuthController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // ── Login ───────────────────────────────────────────────────

    public function loginForm(): void
    {
        if (isLoggedIn()) {
            $this->redirect('/');
        }

        $this->render('auth/login', [
            'titulo'      => 'Entrar – Djumbai',
            'errors'      => [],
            'hideFooter'  => true,
        ]);
    }

    public function login(): void
    {
        // 1. Proteção contra Brute Force (Rate Limiting)
        if (RateLimiter::tooManyAttempts('login', 5, 900)) {
            $seconds = RateLimiter::availableIn('login');
            $minutes = max(1, ceil($seconds / 60));

            SecurityLogger::log('RATE_LIMIT_LOGIN', "Muitas tentativas de login bloqueadas por {$minutes} minutos.");

            $this->render('auth/login', [
                'titulo'       => 'Entrar – Djumbai',
                'errors'       => [],
                'global_error' => "Demasiadas tentativas de acesso falhadas. Acesso temporariamente bloqueado por segurança. Tente novamente dentro de {$minutes} minutos.",
                'hideFooter'   => true,
            ]);
            return;
        }

        // 2. Proteção CSRF
        if (!verifyCsrf()) {
            flash('error', 'Sessão expirada ou pedido inválido. Por favor, tente novamente.');
            $this->redirect('/login');
        }

        $email = sanitizeString($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $errors = [];

        if (empty($email) || !isValidEmail($email)) {
            $errors['email'] = 'Insira um endereço de correio electrónico válido.';
        }

        if (empty($senha)) {
            $errors['senha'] = 'A palavra-passe é obrigatória.';
        }

        if (!empty($errors)) {
            $this->render('auth/login', [
                'titulo'     => 'Entrar – Djumbai',
                'errors'     => $errors,
                'old'        => ['email' => $email],
                'hideFooter' => true,
            ]);
            return;
        }

        // 3. Verificação de Credenciais
        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($senha, $user['senha_hash'])) {
            // Incrementa tentativa de força bruta
            RateLimiter::hit('login', 900);
            SecurityLogger::log('FAILED_LOGIN', "Tentativa falhada de login para o e-mail: {$email}");

            $this->render('auth/login', [
                'titulo'       => 'Entrar – Djumbai',
                'errors'       => [],
                'global_error' => 'Credenciais inválidas. Verifique o seu e-mail e palavra-passe.',
                'old'          => ['email' => $email],
                'hideFooter'   => true,
            ]);
            return;
        }

        // 3.1 Verificação de suspensão / banimento
        if (($user['status'] ?? 'ativo') === 'banido') {
            SecurityLogger::log('BANNED_USER_LOGIN_ATTEMPT', "Tentativa de login de utilizador banido: {$email}");

            $this->render('auth/login', [
                'titulo'       => 'Entrar – Djumbai',
                'errors'       => [],
                'global_error' => 'A sua conta foi suspensa por violação das regras da comunidade Djumbai. Entre em contacto com a administração se pensa tratar-se de um erro.',
                'old'          => ['email' => $email],
                'hideFooter'   => true,
            ]);
            return;
        }

        // 4. Sucesso: Limpa contador de rate limiting e regenera ID da sessão
        RateLimiter::clear('login');
        session_regenerate_id(true);

        // Atualiza último acesso
        $this->userModel->updateUltimoAcesso((int) $user['id']);

        $_SESSION['user_id']        = (int) $user['id'];
        $_SESSION['user_nome']      = $user['nome'];
        $_SESSION['user_tipo']      = $user['tipo'];
        $_SESSION['welcome_modal']  = [
            'nome' => $user['nome'],
            'tipo' => $user['tipo'],
        ];

        SecurityLogger::log('SUCCESSFUL_LOGIN', "Utilizador logado com sucesso (Tipo: {$user['tipo']})", [
            'user_id' => $user['id']
        ]);

        if ($user['tipo'] === 'admin') {
            $this->redirect('/admin');
        } else {
            $this->redirect('/perfil');
        }
    }

    // ── Cadastro ────────────────────────────────────────────────

    public function cadastroForm(): void
    {
        if (isLoggedIn()) {
            $this->redirect('/');
        }

        $db         = Database::getInstance();
        $provincias = $db->query("SELECT id, nome FROM provincias ORDER BY nome ASC")->fetchAll();

        $this->render('auth/cadastro', [
            'titulo'     => 'Criar Conta – Djumbai',
            'errors'     => [],
            'provincias' => $provincias,
            'hideFooter' => true,
        ]);
    }

    public function cadastro(): void
    {
        // 1. Rate limiting de registo para evitar spam/criação em massa de contas
        if (RateLimiter::tooManyAttempts('cadastro', 5, 3600)) {
            SecurityLogger::log('RATE_LIMIT_CADASTRO', 'Muitas tentativas de cadastro a partir do mesmo IP.');
            flash('error', 'Limite de registos temporário atingido. Tente novamente mais tarde.');
            $this->redirect('/cadastro');
        }

        // 2. CSRF
        if (!verifyCsrf()) {
            flash('error', 'Pedido inválido ou sessão expirada.');
            $this->redirect('/cadastro');
        }

        $nome     = sanitizeString($_POST['nome']     ?? '');
        $email    = sanitizeString($_POST['email']    ?? '');
        $telefone = sanitizeString($_POST['telefone'] ?? '');
        $senha    = $_POST['senha']         ?? '';
        $confirma = $_POST['senha_confirm'] ?? '';
        $bairroId = (int) ($_POST['bairro_id'] ?? 0);

        $errors = [];

        if (!isValidHumanName($nome)) {
            $errors['nome'] = 'Insira um nome verdadeiro e válido (apenas letras e espaços, sem números ou códigos aleatórios).';
        }

        if (empty($email) || !isValidEmail($email)) {
            $errors['email'] = 'Insira um endereço de e-mail válido.';
        } elseif ($this->userModel->emailExists($email)) {
            $errors['email'] = 'Este endereço de e-mail já se encontra registado.';
        }

        if (!empty($telefone) && !isValidAngolaPhone($telefone)) {
            $errors['telefone'] = 'Insira um número de telefone válido de Angola (ex: 923 000 000 ou +244 923 000 000).';
        }

        // Política de senha segura: mínimo 8 caracteres
        if (strlen($senha) < 8) {
            $errors['senha'] = 'A palavra-passe deve conter pelo menos 8 caracteres para sua segurança.';
        }

        if ($senha !== $confirma) {
            $errors['senha_confirm'] = 'As palavras-passe inseridas não coincidem.';
        }

        if ($bairroId <= 0) {
            $errors['bairro'] = 'Por favor, seleccione o seu bairro.';
        }

        if (!empty($errors)) {
            RateLimiter::hit('cadastro', 3600);

            $db         = Database::getInstance();
            $provincias = $db->query("SELECT id, nome FROM provincias ORDER BY nome ASC")->fetchAll();

            $this->render('auth/cadastro', [
                'titulo'     => 'Criar Conta – Djumbai',
                'errors'     => $errors,
                'provincias' => $provincias,
                'old'        => compact('nome', 'email', 'telefone'),
                'hideFooter' => true,
            ]);
            return;
        }

        // Criação segura com Bcrypt
        $userId = $this->userModel->create([
            'nome'       => $nome,
            'email'      => $email,
            'telefone'   => $telefone ?: null,
            'senha_hash' => password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]),
            'tipo'       => 'cidadao',
            'bairro_id'  => $bairroId,
            'criado_em'  => date('Y-m-d H:i:s'),
        ]);

        RateLimiter::clear('cadastro');
        session_regenerate_id(true);

        $_SESSION['user_id']   = $userId;
        $_SESSION['user_nome'] = $nome;
        $_SESSION['user_tipo'] = 'cidadao';

        SecurityLogger::log('USER_REGISTERED', "Novo cidadão registado com ID: {$userId}");

        flash('success', 'Conta criada com sucesso! Bem-vindo(a) ao Djumbai, ' . $nome . '!');
        $this->redirect('/');
    }

    // ── Logout ──────────────────────────────────────────────────

    public function logout(): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId) {
            SecurityLogger::log('USER_LOGOUT', "Utilizador encerrou sessão (ID: {$userId})");
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        $this->redirect('/login');
    }
}
