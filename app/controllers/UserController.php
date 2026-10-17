<?php
/**
 * Djumbai — UserController
 * Gestão do Painel de Cidadão, Perfil e Notificações
 */

require_once APP_PATH . '/Models/UserModel.php';
require_once APP_PATH . '/Models/ProblemaModel.php';

class UserController extends Controller
{
    private UserModel $userModel;
    private ProblemaModel $problemaModel;

    public function __construct()
    {
        $this->requireAuth();
        $this->userModel     = new UserModel();
        $this->problemaModel = new ProblemaModel();
    }

    /**
     * Exibe o Painel Principal do Cidadão (Dashboard de Utilizador)
     */
    public function dashboard(): void
    {
        $userId = (int) $_SESSION['user_id'];
        $perfil = $this->userModel->getPerfil($userId);

        if (!$perfil) {
            flash('error', 'Perfil não encontrado.');
            $this->redirect('/');
        }

        $meusReportes = $this->problemaModel->findByUser($userId);
        $notificacoes = $this->problemaModel->getNotificacoesUser($userId);

        // Agregação de Estatísticas Pessoais
        $totalReportes   = count($meusReportes);
        $totalResolvidos = 0;
        $totalEmAnalise  = 0;
        $totalPendentes  = 0;
        $totalRejeitados = 0;
        $totalApoios     = 0;
        $totalComentarios = 0;

        foreach ($meusReportes as $rep) {
            $totalApoios += (int) ($rep['total_confirmacoes'] ?? 0);
            $totalComentarios += (int) ($rep['total_comentarios'] ?? 0);
            if ((int)$rep['estado_id'] === 3) $totalResolvidos++;
            elseif ((int)$rep['estado_id'] === 2) $totalEmAnalise++;
            elseif ((int)$rep['estado_id'] === 1) $totalPendentes++;
            elseif ((int)$rep['estado_id'] === 4) $totalRejeitados++;
        }

        // Cálculo de Score Cívico do Cidadão (0 a 100)
        $score = 50;
        $biStatus = $perfil['bi_status'] ?? 'nao_enviado';
        if ($biStatus === 'verificado') $score += 30;
        elseif ($biStatus === 'rejeitado') $score -= 20;
        $score += min(20, $totalReportes * 4);
        $score += min(10, (int)($perfil['total_confirmacoes'] ?? 0) * 2);
        if (($perfil['status'] ?? 'ativo') === 'banido') $score = max(0, $score - 40);
        $scoreCivico = min(100, max(0, $score));

        $this->render('user/dashboard', [
            'titulo'           => 'Meu Painel – Djumbai',
            'perfil'           => $perfil,
            'meusReportes'     => $meusReportes,
            'notificacoes'     => $notificacoes,
            'totalReportes'    => $totalReportes,
            'totalResolvidos'  => $totalResolvidos,
            'totalEmAnalise'   => $totalEmAnalise,
            'totalPendentes'   => $totalPendentes,
            'totalRejeitados'  => $totalRejeitados,
            'totalApoios'      => $totalApoios,
            'totalComentarios' => $totalComentarios,
            'scoreCivico'      => $scoreCivico,
            'isUserPanel'      => true,
        ], 'main');
    }

    /**
     * Marca uma notificação como lida
     */
    public function marcarLida(int $id): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Sessão expirada.');
            $this->redirect('/perfil');
        }

        $userId = (int) $_SESSION['user_id'];
        $this->problemaModel->marcarNotificacaoLida($id, $userId);

        flash('success', 'Notificação marcada como lida.');
        $this->redirect('/perfil#notificacoes');
    }

    /**
     * Marca todas as notificações do utilizador como lidas de uma só vez
     */
    public function marcarTodasLidas(): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Sessão expirada.');
            $this->redirect('/perfil');
        }

        $userId = (int) $_SESSION['user_id'];
        $this->problemaModel->marcarTodasNotificacoesLidas($userId);

        flash('success', 'Todas as notificações foram marcadas como lidas.');
        $this->redirect('/perfil#notificacoes');
    }

    /**
     * Exibe o formulário de Definições / Alteração de Dados do Perfil
     */
    public function settings(): void
    {
        $userId = (int) $_SESSION['user_id'];
        $perfil = $this->userModel->getPerfil($userId);

        if (!$perfil) {
            flash('error', 'Perfil não encontrado.');
            $this->redirect('/');
        }

        $this->render('user/settings', [
            'titulo'      => 'Definições da Conta – Djumbai',
            'perfil'      => $perfil,
            'errors'      => [],
            'isUserPanel' => true,
        ], 'main');
    }

    /**
     * Guarda as alterações de nome, e-mail e telefone do cidadão
     */
    public function salvarSettings(): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Sessão expirada.');
            $this->redirect('/perfil/definicoes');
        }

        $userId   = (int) $_SESSION['user_id'];
        $nome     = sanitizeString($_POST['nome'] ?? '');
        $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $telefone = sanitizeString($_POST['telefone'] ?? '');

        $errors = [];

        if (!isValidHumanName($nome)) {
            $errors['nome'] = 'Insira um nome verdadeiro e válido (apenas letras e espaços, sem números ou códigos aleatórios).';
        }

        if (!$email) {
            $errors['email'] = 'Endereço de e-mail inválido.';
        } elseif ($this->userModel->emailExistsExcept($email, $userId)) {
            $errors['email'] = 'Este e-mail já está em uso por outro membro.';
        }

        if (!empty($telefone) && !isValidAngolaPhone($telefone)) {
            $errors['telefone'] = 'Insira um número de telefone válido de Angola (ex: 923 000 000 ou +244 923 000 000).';
        }

        if (!empty($errors)) {
            $perfil = $this->userModel->getPerfil($userId);
            $perfil['nome']     = $nome;
            $perfil['email']    = $email ?: $_POST['email'];
            $perfil['telefone'] = $telefone;

            $this->render('user/settings', [
                'titulo'      => 'Definições da Conta – Djumbai',
                'perfil'      => $perfil,
                'errors'      => $errors,
                'isUserPanel' => true,
            ], 'main');
            return;
        }

        $this->userModel->updateDadosPerfil($userId, $nome, $email, $telefone);

        // Atualizar nome de sessão
        $_SESSION['user_nome'] = $nome;

        flash('success', 'Os seus dados pessoais foram atualizados com sucesso!');
        $this->redirect('/perfil/definicoes');
    }

    /**
     * Altera a palavra-passe do utilizador autenticado
     */
    public function alterarSenha(): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Sessão expirada. Por favor tente novamente.');
            $this->redirect('/perfil/definicoes');
        }

        // Rate limiting: máx 5 tentativas de alteração de senha por utilizador em 15 minutos
        $rlKey = 'alterar_senha_' . (int) $_SESSION['user_id'];
        if (RateLimiter::tooManyAttempts($rlKey, 5, 900)) {
            flash('error_senha', 'Demasiadas tentativas de alteração de senha. Aguarde 15 minutos.');
            $this->redirect('/perfil/definicoes');
        }
        RateLimiter::hit($rlKey, 900);

        $userId        = (int) $_SESSION['user_id'];
        $senhaAtual    = $_POST['senha_atual'] ?? '';
        $novaSenha     = $_POST['nova_senha'] ?? '';
        $confirmaSenha = $_POST['confirma_senha'] ?? '';

        $user = $this->userModel->findById($userId);
        if (!$user || !password_verify($senhaAtual, $user['senha_hash'])) {
            flash('error_senha', 'A palavra-passe atual inserida está incorreta.');
            $this->redirect('/perfil/definicoes');
        }

        if (strlen($novaSenha) < 8) {
            flash('error_senha', 'A nova palavra-passe deve conter pelo menos 8 caracteres.');
            $this->redirect('/perfil/definicoes');
        }

        if (!preg_match('/[A-Za-z]/', $novaSenha) || !preg_match('/[0-9]/', $novaSenha)) {
            flash('error_senha', 'A nova palavra-passe deve conter pelo menos uma letra e um número.');
            $this->redirect('/perfil/definicoes');
        }

        if ($novaSenha !== $confirmaSenha) {
            flash('error_senha', 'A confirmação não coincide com a nova palavra-passe.');
            $this->redirect('/perfil/definicoes');
        }

        $this->userModel->alterarSenha($userId, $novaSenha);

        SecurityLogger::log('USER_PASSWORD_CHANGED', "Utilizador #{$userId} alterou a sua palavra-passe", [
            'user_id' => $userId
        ]);

        flash('success', 'A sua palavra-passe foi alterada com sucesso!');
        $this->redirect('/perfil/definicoes');
    }

    /**
     * Submete a verificação de BI a partir das definições do utilizador
     */
    public function submeterVerificacaoBi(): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Sessão expirada. Por favor tente novamente.');
            $this->redirect('/perfil/definicoes');
        }

        $userId = (int) $_SESSION['user_id'];
        $errors = [];

        // Tratar BI Frente (Upload ou Base64 Scanner)
        $biFrentePath = null;
        if (!empty($_POST['bi_frente_base64'])) {
            $biFrentePath = $this->salvarImagemBase64($_POST['bi_frente_base64'], 'frente', $errors);
        } elseif (!empty($_FILES['bi_frente']) && $_FILES['bi_frente']['error'] !== UPLOAD_ERR_NO_FILE) {
            $biFrentePath = $this->processarUploadDocumento($_FILES['bi_frente'], 'bi_frente', $errors);
        }

        // Tratar BI Verso (Upload ou Base64 Scanner)
        $biVersoPath = null;
        if (!empty($_POST['bi_verso_base64'])) {
            $biVersoPath = $this->salvarImagemBase64($_POST['bi_verso_base64'], 'verso', $errors);
        } elseif (!empty($_FILES['bi_verso']) && $_FILES['bi_verso']['error'] !== UPLOAD_ERR_NO_FILE) {
            $biVersoPath = $this->processarUploadDocumento($_FILES['bi_verso'], 'bi_verso', $errors);
        }

        if (empty($biFrentePath)) {
            $errors['bi_frente'] = $errors['bi_frente'] ?? 'Por favor digitalize ou carregue a frente do Bilhete de Identidade.';
        }
        if (empty($biVersoPath)) {
            $errors['bi_verso'] = $errors['bi_verso'] ?? 'Por favor digitalize ou carregue o verso do Bilhete de Identidade.';
        }

        if (!empty($errors)) {
            flash('error', reset($errors));
            $this->redirect('/perfil/definicoes');
        }

        $biNumero = sanitizeString($_POST['bi_numero'] ?? '');
        $biNumero = strtoupper(trim($biNumero));

        if (!empty($biNumero)) {
            if ($this->userModel->biNumeroExistsExcept($biNumero, $userId)) {
                flash('error', 'Aviso de Verificação: O número de Bilhete de Identidade informado já se encontra registado e associado a outra conta.');
                $this->redirect('/perfil/definicoes');
            }
        }

        $dadosCivis = [];
        if (!empty($_POST['nome_completo_bi'])) {
            $dadosCivis['nome_completo_bi'] = sanitizeString($_POST['nome_completo_bi']);
        }
        if (!empty($_POST['data_nascimento'])) {
            $dadosCivis['data_nascimento'] = sanitizeString($_POST['data_nascimento']);
            $nasc = DateTime::createFromFormat('Y-m-d', $dadosCivis['data_nascimento']);
            if ($nasc) {
                $hoje = new DateTime();
                $dadosCivis['idade'] = $hoje->diff($nasc)->y;
            }
        } elseif (!empty($_POST['idade'])) {
            $dadosCivis['idade'] = (int)$_POST['idade'];
        }
        if (!empty($_POST['estado_civil'])) {
            $dadosCivis['estado_civil'] = sanitizeString($_POST['estado_civil']);
        }
        if (!empty($_POST['nacionalidade'])) {
            $dadosCivis['nacionalidade'] = sanitizeString($_POST['nacionalidade']);
        }
        if (!empty($_POST['genero']) || !empty($_POST['sexo'])) {
            $sexoRaw = strtoupper(trim($_POST['genero'] ?? $_POST['sexo'] ?? ''));
            $dadosCivis['genero'] = in_array($sexoRaw, ['F', 'FEMININO', 'FEM']) ? 'F' : 'M';
            $dadosCivis['nacionalidade'] = ($dadosCivis['genero'] === 'F') ? 'Angolana' : 'Angolano';
        }

        $this->userModel->atualizarDocumentosBI($userId, $biFrentePath, $biVersoPath, $biNumero, $dadosCivis);

        SecurityLogger::log('USER_BI_VERIFIED', "Utilizador #{$userId} concluiu verificação com BI", [
            'user_id'   => $userId,
            'bi_numero' => $biNumero,
            'bi_frente' => $biFrentePath,
            'bi_verso'  => $biVersoPath,
        ]);

        flash('success', 'Verificação do Bilhete de Identidade concluída com sucesso! O seu perfil foi verificado.');
        $this->redirect('/perfil/definicoes');
    }

    /**
     * Endpoint AJAX para validação instantânea de BI duplicado
     */
    public function checkBiDuplicado(): void
    {
        $userId = (int)($_SESSION['user_id'] ?? 0);
        $bi = sanitizeString($_GET['bi'] ?? '');
        $bi = strtoupper(trim($bi));

        if (empty($bi)) {
            $this->json(['duplicado' => false]);
            return;
        }

        $duplicado = $this->userModel->biNumeroExistsExcept($bi, $userId);
        $this->json([
            'duplicado' => $duplicado,
            'mensagem'  => $duplicado ? 'Aviso: Este número de Bilhete de Identidade já está associado a outra conta.' : ''
        ]);
    }

    /**
     * Salva imagem capturada pela câmara (Base64 Data URL)
     */
    private function salvarImagemBase64(string $base64Data, string $lado, array &$errors): ?string
    {
        if (!preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,/', $base64Data, $matches)) {
            $errors['bi_' . $lado] = 'Formato de captura inválido.';
            return null;
        }

        $ext = ($matches[1] === 'jpeg') ? 'jpg' : $matches[1];
        $data = substr($base64Data, strpos($base64Data, ',') + 1);
        $decoded = base64_decode($data);

        if ($decoded === false) {
            $errors['bi_' . $lado] = 'Erro ao processar a captura do scanner.';
            return null;
        }

        $uploadDir = ROOT_PATH . '/storage/uploads/documentos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uniqueName = 'bi_' . $lado . '_' . bin2hex(random_bytes(16)) . '.' . $ext;
        $targetFile = $uploadDir . $uniqueName;

        if (file_put_contents($targetFile, $decoded) === false) {
            $errors['bi_' . $lado] = 'Falha ao guardar a captura no servidor.';
            return null;
        }

        @chmod($targetFile, 0644);
        return 'documentos/' . $uniqueName;
    }

    /**
     * Valida e guarda ficheiro tradicional de documento
     */
    private function processarUploadDocumento(array $file, string $campo, array &$errors): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[$campo] = 'Falha no carregamento do ficheiro. Tente novamente.';
            return null;
        }

        $maxSize = 10 * 1024 * 1024; // 10MB
        if ($file['size'] > $maxSize) {
            $errors[$campo] = 'O ficheiro não pode exceder 10 MB.';
            return null;
        }

        $allowedExts  = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];

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

        $uploadDir = ROOT_PATH . '/storage/uploads/documentos/';
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
