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
        $totalReportes  = count($meusReportes);
        $totalResolvidos = 0;
        $totalEmAnalise  = 0;
        $totalPendentes  = 0;
        $totalApoios     = 0;

        foreach ($meusReportes as $rep) {
            $totalApoios += (int) ($rep['total_confirmacoes'] ?? 0);
            if ((int)$rep['estado_id'] === 3) $totalResolvidos++;
            elseif ((int)$rep['estado_id'] === 2) $totalEmAnalise++;
            elseif ((int)$rep['estado_id'] === 1) $totalPendentes++;
        }

        $this->render('user/dashboard', [
            'titulo'          => 'Meu Painel – Djumbai',
            'perfil'          => $perfil,
            'meusReportes'    => $meusReportes,
            'notificacoes'    => $notificacoes,
            'totalReportes'   => $totalReportes,
            'totalResolvidos' => $totalResolvidos,
            'totalEmAnalise'  => $totalEmAnalise,
            'totalPendentes'  => $totalPendentes,
            'totalApoios'     => $totalApoios,
            'isUserPanel'     => true,
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
        $this->redirect('/perfil');
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
        $this->redirect('/perfil');
    }
}
