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

        $identificador = sanitizeString($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $errors = [];

        if (empty($identificador)) {
            $errors['email'] = 'Insira o seu e-mail ou número de telefone.';
        }

        if (empty($senha)) {
            $errors['senha'] = 'A palavra-passe é obrigatória.';
        }

        if (!empty($errors)) {
            $this->render('auth/login', [
                'titulo'     => 'Entrar – Djumbai',
                'errors'     => $errors,
                'old'        => ['email' => $identificador],
                'hideFooter' => true,
            ]);
            return;
        }

        // 3. Verificação de Credenciais por E-mail ou Telefone
        $user = $this->userModel->findByEmailOrTelefone($identificador);

        if (!$user || !password_verify($senha, $user['senha_hash'])) {
            // Incrementa tentativa de força bruta
            RateLimiter::hit('login', 900);
            SecurityLogger::log('FAILED_LOGIN', "Tentativa falhada de login para: {$identificador}");

            $this->render('auth/login', [
                'titulo'       => 'Entrar – Djumbai',
                'errors'       => [],
                'global_error' => 'Credenciais inválidas. Verifique o seu e-mail / telefone e palavra-passe.',
                'old'          => ['email' => $identificador],
                'hideFooter'   => true,
            ]);
            return;
        }

        // 3.1 Verificação de suspensão / banimento
        if (($user['status'] ?? 'ativo') === 'banido') {
            SecurityLogger::log('BANNED_USER_LOGIN_ATTEMPT', "Tentativa de login de utilizador banido: {$identificador}");

            $this->render('auth/login', [
                'titulo'       => 'Entrar – Djumbai',
                'errors'       => [],
                'global_error' => 'A sua conta foi suspensa por violação das regras da comunidade Djumbai. Entre em contacto com a administração se pensa tratar-se de um erro.',
                'old'          => ['email' => $identificador],
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
            unset($_SESSION['redirect_after_login']);
            $this->redirect('/admin');
        } else {
            $redirectTo = $_SESSION['redirect_after_login'] ?? '/perfil';
            unset($_SESSION['redirect_after_login']);
            // Segurança: apenas aceitar redirects relativos (internos)
            if (!str_starts_with($redirectTo, '/') || str_starts_with($redirectTo, '//')) {
                $redirectTo = '/perfil';
            }
            $this->redirect($redirectTo);
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
        $municipios = $db->query("SELECT id, nome, provincia_id FROM municipios ORDER BY nome ASC")->fetchAll();
        $bairros    = $db->query("SELECT id, nome, municipio_id FROM bairros ORDER BY nome ASC")->fetchAll();

        $municipioAtivoId = (int) Env::get('MUNICIPIO_ATIVO_ID', 0);
        $municipioAtivoNome = '';
        if ($municipioAtivoId > 0) {
            $stmtM = $db->prepare("SELECT nome FROM municipios WHERE id = :mid LIMIT 1");
            $stmtM->execute([':mid' => $municipioAtivoId]);
            $municipioAtivoNome = $stmtM->fetchColumn() ?: '';
        }

        $this->render('auth/cadastro', [
            'titulo'              => 'Criar Conta – Djumbai',
            'errors'              => [],
            'provincias'          => $provincias,
            'municipios'          => $municipios,
            'bairros'             => $bairros,
            'municipioAtivoId'   => $municipioAtivoId,
            'municipioAtivoNome' => $municipioAtivoNome,
            'hideFooter'          => true,
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

        $nome        = sanitizeString($_POST['nome']     ?? '');
        $tipoContato = sanitizeString($_POST['tipo_contato'] ?? 'email');
        $email       = sanitizeString($_POST['email']    ?? '');
        $telefone    = sanitizeString($_POST['telefone'] ?? '');
        $senha       = $_POST['senha']         ?? '';
        $confirma    = $_POST['senha_confirm'] ?? '';
        $bairroId    = (int) ($_POST['bairro_id'] ?? 0);

        $errors = [];

        if (!isValidHumanName($nome)) {
            $errors['nome'] = 'Insira um nome verdadeiro e válido (apenas letras e espaços, sem números ou códigos aleatórios).';
        }

        // Validação flexível: E-mail OU Telefone
        if ($tipoContato === 'telefone') {
            if (empty($telefone) || !isValidAngolaPhone($telefone)) {
                $errors['telefone'] = 'Insira um número de telefone válido de Angola (ex: 923 000 000 ou +244 923 000 000).';
            } elseif ($this->userModel->telefoneExists($telefone)) {
                $errors['telefone'] = 'Este número de telefone já se encontra registado.';
            }
            // Se o e-mail não for informado, fica nulo
            if (!empty($email)) {
                if (!isValidEmail($email)) {
                    $errors['email'] = 'Insira um endereço de e-mail válido.';
                } elseif ($this->userModel->emailExists($email)) {
                    $errors['email'] = 'Este endereço de e-mail já se encontra registado.';
                }
            } else {
                $email = null;
            }
        } else {
            // Padrão e-mail
            if (empty($email) || !isValidEmail($email)) {
                $errors['email'] = 'Insira um endereço de e-mail válido.';
            } elseif ($this->userModel->emailExists($email)) {
                $errors['email'] = 'Este endereço de e-mail já se encontra registado.';
            }
            if (!empty($telefone)) {
                if (!isValidAngolaPhone($telefone)) {
                    $errors['telefone'] = 'Insira um número de telefone válido de Angola.';
                } elseif ($this->userModel->telefoneExists($telefone)) {
                    $errors['telefone'] = 'Este número de telefone já se encontra registado.';
                }
            } else {
                $telefone = null;
            }
        }

        // Upload do Scanner de Bilhete de Identidade (Frente e Verso)
        $biFrentePath = null;
        $biVersoPath  = null;

        if (!empty($_FILES['bi_frente']['name']) && $_FILES['bi_frente']['error'] !== UPLOAD_ERR_NO_FILE) {
            $biFrentePath = $this->processarUploadDocumento($_FILES['bi_frente'], 'bi_frente', $errors);
        }

        if (!empty($_FILES['bi_verso']['name']) && $_FILES['bi_verso']['error'] !== UPLOAD_ERR_NO_FILE) {
            $biVersoPath = $this->processarUploadDocumento($_FILES['bi_verso'], 'bi_verso', $errors);
        }

        // Política de senha segura: mínimo 8 caracteres com letra e número
        if (strlen($senha) < 8) {
            $errors['senha'] = 'A palavra-passe deve conter pelo menos 8 caracteres para sua segurança.';
        } elseif (!preg_match('/[A-Za-z]/', $senha) || !preg_match('/[0-9]/', $senha)) {
            $errors['senha'] = 'A palavra-passe deve conter pelo menos uma letra e um número.';
        }

        if ($senha !== $confirma) {
            $errors['senha_confirm'] = 'As palavras-passe inseridas não coincidem.';
        }

        $db = Database::getInstance();
        $municipioAtivoId = (int) Env::get('MUNICIPIO_ATIVO_ID', 0);
        $municipioAtivoNome = '';
        if ($municipioAtivoId > 0) {
            $stmtM = $db->prepare("SELECT nome FROM municipios WHERE id = :mid LIMIT 1");
            $stmtM->execute([':mid' => $municipioAtivoId]);
            $municipioAtivoNome = $stmtM->fetchColumn() ?: 'Cacuaco';
        }

        if ($bairroId <= 0) {
            $errors['bairro'] = 'Por favor, seleccione o seu bairro.';
        } else {
            // Validação de restrição territorial ativa (ex: Cacuaco)
            $stmtB = $db->prepare("SELECT b.id, b.municipio_id, m.nome AS municipio_nome FROM bairros b JOIN municipios m ON m.id = b.municipio_id WHERE b.id = :bid LIMIT 1");
            $stmtB->execute([':bid' => $bairroId]);
            $bairroInfo = $stmtB->fetch();

            if (!$bairroInfo) {
                $errors['bairro'] = 'O bairro seleccionado é inválido.';
            } elseif ($municipioAtivoId > 0 && (int) $bairroInfo['municipio_id'] !== $municipioAtivoId) {
                $errors['bairro'] = "Esta opção de localização não está disponível de momento. O sistema está disponível apenas para a Administração de {$municipioAtivoNome}.";
            }
        }

        if (!empty($errors)) {
            RateLimiter::hit('cadastro', 3600);

            $provincias = $db->query("SELECT id, nome FROM provincias ORDER BY nome ASC")->fetchAll();
            $municipios = $db->query("SELECT id, nome, provincia_id FROM municipios ORDER BY nome ASC")->fetchAll();
            $bairros    = $db->query("SELECT id, nome, municipio_id FROM bairros ORDER BY nome ASC")->fetchAll();

            $this->render('auth/cadastro', [
                'titulo'              => 'Criar Conta – Djumbai',
                'errors'              => $errors,
                'provincias'          => $provincias,
                'municipios'          => $municipios,
                'bairros'             => $bairros,
                'municipioAtivoId'   => $municipioAtivoId,
                'municipioAtivoNome' => $municipioAtivoNome,
                'old'                 => compact('nome', 'email', 'telefone', 'tipoContato'),
                'hideFooter'          => true,
            ]);
            return;
        }

        // Criação segura com Bcrypt
        $userId = $this->userModel->create([
            'nome'       => $nome,
            'email'      => $email ?: null,
            'telefone'   => $telefone ?: null,
            'bi_frente'  => $biFrentePath ?: null,
            'bi_verso'   => $biVersoPath ?: null,
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
        // Proteção CSRF: logout só pode ser feito via POST com token válido
        if (!verifyCsrf()) {
            SecurityLogger::log('CSRF_VIOLATION', 'Tentativa de logout sem token CSRF válido.');
            flash('error', 'Pedido inválido ou sessão expirada.');
            $this->redirect('/');
        }

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
        $this->redirect('/');
    }

    /**
     * Processamento estritamente seguro de upload de documentos (BI Frente e Verso)
     */
    private function processarUploadDocumento(array $file, string $campo, array &$errors): ?string
    {
        $maxBytes     = 8 * 1024 * 1024; // 8 MB
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
        $allowedExts  = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
        $uploadDir    = ROOT_PATH . '/storage/uploads/documentos/';

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[$campo] = 'Erro no envio do documento.';
            return null;
        }

        if ($file['size'] > $maxBytes || $file['size'] === 0) {
            $errors[$campo] = 'O ficheiro não pode exceder 8 MB.';
            return null;
        }

        $rawExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($rawExt, $allowedExts, true)) {
            $errors[$campo] = 'Apenas formatos JPG, PNG, WebP ou PDF são aceites.';
            return null;
        }

        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimes, true)) {
            $errors[$campo] = 'Formato de ficheiro não reconhecido ou inválido.';
            return null;
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $safeExt    = ($rawExt === 'jpeg') ? 'jpg' : $rawExt;
        $uniqueName = 'bi_' . bin2hex(random_bytes(16)) . '.' . $safeExt;
        $targetFile = $uploadDir . $uniqueName;

        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            $errors[$campo] = 'Falha ao guardar o documento no servidor.';
            return null;
        }

        @chmod($targetFile, 0644);

        return 'documentos/' . $uniqueName;
    }
}
