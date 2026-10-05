<?php
/**
 * Djumbai — AdminController com Auditoria Estrita, Moderação de Utilizadores, Comentários e Definições
 */

require_once APP_PATH . '/Models/ProblemaModel.php';
require_once APP_PATH . '/Models/UserModel.php';
require_once APP_PATH . '/Models/CategoriaModel.php';

class AdminController extends Controller
{
    private ProblemaModel  $problemaModel;
    private UserModel      $userModel;
    private CategoriaModel $categoriaModel;

    public function __construct()
    {
        $this->requireAdmin(); // Barreira de autorização estrita

        $this->problemaModel  = new ProblemaModel();
        $this->userModel      = new UserModel();
        $this->categoriaModel = new CategoriaModel();
    }

    public function dashboard(): void
    {
        $panorama               = $this->problemaModel->getPanorama();
        $recentes               = $this->problemaModel->listar(0, 0, 8, 0);
        $distribuicaoCategorias = $this->problemaModel->getDistribuicaoCategorias();
        $distribuicaoMunicipios = $this->problemaModel->getDistribuicaoMunicipios(5);
        $historicoAuditoria     = $this->problemaModel->getHistoricoAuditoriaRecente(6);
        $totalUtilizadores      = $this->userModel->count();

        // Taxa de resolução
        $total = (int) ($panorama['total'] ?? 0);
        $resolvidos = (int) ($panorama['resolvidos'] ?? 0);
        $taxaResolucao = $total > 0 ? round(($resolvidos / $total) * 100, 1) : 0;

        $this->render('admin/dashboard', [
            'titulo'                 => 'Painel Admin – Djumbai',
            'panorama'               => $panorama,
            'taxaResolucao'          => $taxaResolucao,
            'recentes'               => $recentes,
            'distribuicaoCategorias' => $distribuicaoCategorias,
            'distribuicaoMunicipios' => $distribuicaoMunicipios,
            'historicoAuditoria'     => $historicoAuditoria,
            'totalUtilizadores'      => $totalUtilizadores,
        ], 'admin');
    }

    public function problemas(): void
    {
        $categoriaId = (int) ($_GET['categoria'] ?? 0);
        $estadoId    = (int) ($_GET['estado']    ?? 0);
        $pagina      = max(1, (int) ($_GET['pagina'] ?? 1));
        $limite      = 12;
        $offset      = ($pagina - 1) * $limite;

        $problemas  = $this->problemaModel->listar($categoriaId, $estadoId, $limite, $offset);
        $total      = $this->problemaModel->totalFiltrado($categoriaId, $estadoId);
        $categorias = $this->categoriaModel->findAll();

        $db      = Database::getInstance();
        $estados = $db->query("SELECT id, nome, cor FROM estados_problema ORDER BY id ASC")->fetchAll();

        $this->render('admin/problemas', [
            'titulo'       => 'Gestão de Ocorrências – Djumbai',
            'problemas'    => $problemas,
            'categorias'   => $categorias,
            'estados'      => $estados,
            'categoriaId'  => $categoriaId,
            'estadoId'     => $estadoId,
            'pagina'       => $pagina,
            'porPagina'    => $limite,
            'totalPaginas' => (int) ceil($total / ($limite ?: 1)),
            'total'        => $total,
            'filtros'      => ['categoria' => $categoriaId, 'estado' => $estadoId],
        ], 'admin');
    }

    public function mudarEstado(int $id): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Token de segurança inválido.');
            $this->redirect('/admin/problemas');
        }

        $novoEstadoId = (int) ($_POST['estado_id'] ?? 0);
        $nota         = sanitizeString($_POST['nota'] ?? '');

        if ($novoEstadoId < 1 || $novoEstadoId > 4) {
            flash('error', 'Estado inválido seleccionado.');
            $this->redirect('/admin/problemas');
        }

        $problema = $this->problemaModel->findById($id);
        if (!$problema) {
            flash('error', 'Problema não encontrado.');
            $this->redirect('/admin/problemas');
        }

        $estadoAnteriorId = (int) $problema['estado_id'];

        // Atualização do estado
        $this->problemaModel->update($id, [
            'estado_id'    => $novoEstadoId,
            'resolvido_em' => $novoEstadoId === 3 ? date('Y-m-d H:i:s') : null,
        ]);

        // Registo de auditoria
        $db = Database::getInstance();
        $db->prepare("
            INSERT INTO historico_estados
                (problema_id, estado_anterior_id, estado_novo_id, admin_id, observacao, criado_em)
            VALUES
                (:prob_id, :ant_id, :nov_id, :adm_id, :obs, NOW())
        ")->execute([
            ':prob_id' => $id,
            ':ant_id'  => $estadoAnteriorId,
            ':nov_id'  => $novoEstadoId,
            ':adm_id'  => (int) $_SESSION['user_id'],
            ':obs'     => $nota ?: null,
        ]);

        SecurityLogger::log('ADMIN_STATE_CHANGE', "Estado do problema #{$id} alterado de {$estadoAnteriorId} para {$novoEstadoId}", [
            'problema_id' => $id,
            'antigo'      => $estadoAnteriorId,
            'novo'        => $novoEstadoId,
            'admin_id'    => $_SESSION['user_id']
        ]);

        flash('success', "Estado da ocorrência '{$problema['titulo']}' actualizado com sucesso.");
        $this->redirect('/admin/problemas');
    }

    // ── GESTÃO DE UTILIZADORES & MODERAÇÃO DE BONS MODOS ──────────────────

    public function usuarios(): void
    {
        $utilizadores = $this->userModel->listarComEstatisticas();

        $this->render('admin/usuarios', [
            'titulo'       => 'Gestão de Utilizadores & Moderação – Djumbai',
            'utilizadores' => $utilizadores,
        ], 'admin');
    }

    public function toggleBan(int $id): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Token de segurança inválido.');
            $this->redirect('/admin/usuarios');
        }

        if ($id === (int)$_SESSION['user_id']) {
            flash('error', 'Não pode banir a sua própria conta de administrador.');
            $this->redirect('/admin/usuarios');
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            flash('error', 'Utilizador não encontrado.');
            $this->redirect('/admin/usuarios');
        }

        $novoStatus = ($user['status'] ?? 'ativo') === 'ativo' ? 'banido' : 'ativo';
        $this->userModel->setStatus($id, $novoStatus);

        SecurityLogger::log('ADMIN_USER_STATUS_CHANGE', "Status do utilizador #{$id} ({$user['email']}) alterado para {$novoStatus}", [
            'target_user_id' => $id,
            'novo_status'    => $novoStatus,
            'admin_id'       => $_SESSION['user_id']
        ]);

        $acaoText = $novoStatus === 'banido' ? 'suspenso' : 'reativado';
        flash('success', "Utilizador '{$user['nome']}' foi {$acaoText} com sucesso.");
        $this->redirect('/admin/usuarios');
    }

    public function notificarBonsModos(int $id): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Token de segurança inválido.');
            $this->redirect('/admin/usuarios');
        }

        $mensagem = sanitizeString($_POST['mensagem'] ?? '');
        if (empty($mensagem)) {
            $mensagem = 'Notificação da Administração: Por favor, mantenha a cordialidade, o respeito e a veracidade nas suas publicações e comentários na plataforma Djumbai.';
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            flash('error', 'Utilizador não encontrado.');
            $this->redirect('/admin/usuarios');
        }

        $this->problemaModel->criarNotificacao($id, null, "⚠️ Notificação de Bons Modos: " . $mensagem);

        SecurityLogger::log('ADMIN_USER_WARNING_SENT', "Notificação de bons modos enviada ao utilizador #{$id}", [
            'target_user_id' => $id,
            'mensagem'       => $mensagem,
            'admin_id'       => $_SESSION['user_id']
        ]);

        flash('success', "Notificação de bons modos enviada com sucesso a '{$user['nome']}'.");
        $this->redirect('/admin/usuarios');
    }

    public function eliminarUsuario(int $id): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Token de segurança inválido.');
            $this->redirect('/admin/usuarios');
        }

        if ($id === (int)$_SESSION['user_id']) {
            flash('error', 'Não pode eliminar a sua própria conta de administrador.');
            $this->redirect('/admin/usuarios');
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            flash('error', 'Utilizador não encontrado.');
            $this->redirect('/admin/usuarios');
        }

        if ($user['tipo'] === 'admin') {
            flash('error', 'Não é possível eliminar contas de administrador.');
            $this->redirect('/admin/usuarios');
        }

        $sucesso = $this->userModel->eliminarUsuario($id);

        if ($sucesso) {
            SecurityLogger::log('ADMIN_USER_DELETED', "Utilizador #{$id} ({$user['email']}) eliminado definitivamente pelo admin", [
                'target_user_id' => $id,
                'target_email'   => $user['email'],
                'admin_id'       => $_SESSION['user_id']
            ]);
            flash('success', "Utilizador '{$user['nome']}' foi eliminado definitivamente com sucesso.");
        } else {
            flash('error', "Não foi possível eliminar o utilizador '{$user['nome']}'.");
        }

        $this->redirect('/admin/usuarios');
    }

    // ── MODERAÇÃO DE COMENTÁRIOS E RECLAMAÇÕES ──────────────────────────

    public function comentarios(): void
    {
        $comentarios = $this->problemaModel->listarTodosComentarios();

        $this->render('admin/comentarios', [
            'titulo'      => 'Moderação de Comentários – Djumbai',
            'comentarios' => $comentarios,
        ], 'admin');
    }

    public function eliminarComentario(int $id): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Token de segurança inválido.');
            $this->redirect('/admin/comentarios');
        }

        $sucesso = $this->problemaModel->eliminarComentario($id);

        if ($sucesso) {
            SecurityLogger::log('ADMIN_COMMENT_DELETED', "Comentário #{$id} eliminado por moderação", [
                'comentario_id' => $id,
                'admin_id'      => $_SESSION['user_id']
            ]);
            flash('success', 'Comentário eliminado com sucesso.');
        } else {
            flash('error', 'Não foi possível eliminar o comentário.');
        }

        $this->redirect('/admin/comentarios');
    }

    // ── DEFINIÇÕES DO SISTEMA (SETTINGS) ───────────────────────────────

    public function definicoes(): void
    {
        $settings = allSiteSettings();

        $this->render('admin/definicoes', [
            'titulo'   => 'Definições da Plataforma – Djumbai',
            'settings' => $settings,
        ], 'admin');
    }

    public function salvarDefinicoes(): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Token de segurança inválido.');
            $this->redirect('/admin/definicoes');
        }

        $nomeSite           = sanitizeString($_POST['nome_site'] ?? 'Djumbai');
        $slogan             = sanitizeString($_POST['slogan'] ?? '');
        $emailSuporte       = sanitizeString($_POST['email_suporte'] ?? '');
        $telefoneEmergencia = sanitizeString($_POST['telefone_emergencia'] ?? '');
        $modoManutencao     = isset($_POST['modo_manutencao']);
        $aprovacaoAuto      = isset($_POST['aprovacao_automatica']);
        $maxReportesHora    = max(1, min(100, (int) ($_POST['max_reportes_hora'] ?? 10)));
        $regrasComunidade   = sanitizeString($_POST['regras_comunidade'] ?? '');

        if (mb_strlen($nomeSite, 'UTF-8') < 2) {
            flash('error', 'O nome da plataforma deve ter pelo menos 2 caracteres.');
            $this->redirect('/admin/definicoes');
        }

        if (!empty($emailSuporte) && !isValidEmail($emailSuporte)) {
            flash('error', 'Por favor insira um e-mail de suporte válido.');
            $this->redirect('/admin/definicoes');
        }

        $newSettings = [
            'nome_site'            => $nomeSite,
            'slogan'               => $slogan,
            'email_suporte'        => $emailSuporte,
            'telefone_emergencia'  => $telefoneEmergencia,
            'modo_manutencao'      => $modoManutencao,
            'aprovacao_automatica' => $aprovacaoAuto,
            'max_reportes_hora'    => $maxReportesHora,
            'regras_comunidade'    => $regrasComunidade,
            'atualizado_em'        => date('Y-m-d H:i:s'),
            'atualizado_por'       => $_SESSION['user_nome'] ?? 'Administrador',
        ];

        $configDir = ROOT_PATH . '/storage/config';
        if (!is_dir($configDir)) {
            mkdir($configDir, 0755, true);
        }

        $configFile = $configDir . '/site_settings.json';
        file_put_contents($configFile, json_encode($newSettings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        SecurityLogger::log('ADMIN_SETTINGS_UPDATED', 'Definições do site atualizadas pelo administrador', [
            'admin_id'        => $_SESSION['user_id'] ?? 0,
            'modo_manutencao' => $modoManutencao ? 'ON' : 'OFF',
            'aprovacao_auto'  => $aprovacaoAuto ? 'ON' : 'OFF',
        ]);

        flash('success', 'Definições da plataforma guardadas com sucesso.');
        $this->redirect('/admin/definicoes');
    }
}
