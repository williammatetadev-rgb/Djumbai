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
        $periodo = sanitizeString($_GET['periodo'] ?? 'todos');
        if (!in_array($periodo, ['todos', 'hoje', '7dias', '30dias'], true)) {
            $periodo = 'todos';
        }

        $panorama               = $this->problemaModel->getPanorama($periodo);
        $metricasSLA            = $this->problemaModel->getMetricasSLA();
        $ocorrenciasCriticas    = $this->problemaModel->getOcorrenciasCriticas(5);
        $pontosMapa             = $this->problemaModel->getPontosMapa(0, 0, 0, 0);
        $recentes               = $this->problemaModel->listar(0, 0, 8, 0);
        $distribuicaoCategorias = $this->problemaModel->getDistribuicaoCategorias();
        $distribuicaoMunicipios = $this->problemaModel->getDistribuicaoMunicipios(5);
        $historicoAuditoria     = $this->problemaModel->getHistoricoAuditoriaRecente(6);
        $totalUtilizadores      = $this->userModel->count();

        // Lista de estados para o modal de despacho rápido
        $db = Database::getInstance();
        $estados = $db->query("SELECT id, nome, cor FROM estados_problema ORDER BY id ASC")->fetchAll();

        // Taxa de resolução
        $total = (int) ($panorama['total'] ?? 0);
        $resolvidos = (int) ($panorama['resolvidos'] ?? 0);
        $taxaResolucao = $total > 0 ? round(($resolvidos / $total) * 100, 1) : 0;

        $this->render('admin/dashboard', [
            'titulo'                 => 'Painel de Despacho & Inteligência – Djumbai Admin',
            'periodo'                => $periodo,
            'panorama'               => $panorama,
            'metricasSLA'            => $metricasSLA,
            'ocorrenciasCriticas'    => $ocorrenciasCriticas,
            'pontosMapa'             => $pontosMapa,
            'estados'                => $estados,
            'taxaResolucao'          => $taxaResolucao,
            'recentes'               => $recentes,
            'distribuicaoCategorias' => $distribuicaoCategorias,
            'distribuicaoMunicipios' => $distribuicaoMunicipios,
            'historicoAuditoria'     => $historicoAuditoria,
            'totalUtilizadores'      => $totalUtilizadores,
        ], 'admin');
    }

    /**
     * Exibe o Mapa Interativo de Ocorrências dentro do Painel Admin
     */
    public function mapa(): void
    {
        $categoriaId = max(0, (int) ($_GET['categoria'] ?? 0));
        $estadoId    = max(0, (int) ($_GET['estado']    ?? 0));
        $provinciaId = max(0, (int) ($_GET['provincia'] ?? 0));
        $municipioId = max(0, (int) ($_GET['municipio'] ?? 0));

        $pontos     = $this->problemaModel->getPontosMapa($categoriaId, $estadoId, $provinciaId, $municipioId);
        $categorias = $this->categoriaModel->findAll();

        $db         = Database::getInstance();
        $provincias = $db->query("SELECT id, nome, latitude, longitude FROM provincias ORDER BY nome ASC")->fetchAll();
        $municipios = $db->query("SELECT id, nome, provincia_id FROM municipios ORDER BY nome ASC")->fetchAll();
        $estados    = $db->query("SELECT id, nome, cor FROM estados_problema ORDER BY id ASC")->fetchAll();

        $this->render('admin/mapa', [
            'titulo'     => 'Mapa de Ocorrências – Djumbai Admin',
            'pontos'     => $pontos,
            'categorias' => $categorias,
            'provincias' => $provincias,
            'municipios' => $municipios,
            'estados'    => $estados,
            'filtros'    => [
                'categoria' => $categoriaId,
                'estado'    => $estadoId,
                'provincia' => $provinciaId,
                'municipio' => $municipioId,
            ],
        ], 'admin_mapa');
    }

    public function problemas(): void
    {
        $categoriaId = (int) ($_GET['categoria'] ?? 0);
        $estadoId    = (int) ($_GET['estado']    ?? 0);
        $municipioId = (int) ($_GET['municipio'] ?? 0);
        $busca       = sanitizeString($_GET['q'] ?? '');
        $ordem       = sanitizeString($_GET['ordem'] ?? 'recentes');
        $pagina      = max(1, (int) ($_GET['pagina'] ?? 1));
        $limite      = 20;
        $offset      = ($pagina - 1) * $limite;

        $problemas  = $this->problemaModel->listar($categoriaId, $estadoId, $limite, $offset, $busca, $municipioId, $ordem);
        $total      = $this->problemaModel->totalFiltrado($categoriaId, $estadoId, $busca, $municipioId);
        $categorias = $this->categoriaModel->findAll();

        $db         = Database::getInstance();
        $estados    = $db->query("SELECT id, nome, cor FROM estados_problema ORDER BY id ASC")->fetchAll();
        $municipios = $db->query("SELECT id, nome FROM municipios ORDER BY nome ASC")->fetchAll();

        // Contagens globais para os cartões de KPI
        $kpiPendentes  = (int) $db->query("SELECT COUNT(*) FROM problemas WHERE estado_id = 1")->fetchColumn();
        $kpiAnalise    = (int) $db->query("SELECT COUNT(*) FROM problemas WHERE estado_id = 2")->fetchColumn();
        $kpiResolvidos = (int) $db->query("SELECT COUNT(*) FROM problemas WHERE estado_id = 3")->fetchColumn();
        $kpiTotalGeral = (int) $db->query("SELECT COUNT(*) FROM problemas")->fetchColumn();

        $this->render('admin/problemas', [
            'titulo'        => 'Gestão de Ocorrências – Djumbai Admin',
            'problemas'     => $problemas,
            'categorias'    => $categorias,
            'estados'       => $estados,
            'municipios'    => $municipios,
            'categoriaId'   => $categoriaId,
            'estadoId'      => $estadoId,
            'municipioId'   => $municipioId,
            'busca'         => $busca,
            'ordem'         => $ordem,
            'pagina'        => $pagina,
            'porPagina'     => $limite,
            'totalPaginas'  => (int) ceil($total / ($limite ?: 1)),
            'total'         => $total,
            'kpis'          => [
                'total'      => $kpiTotalGeral,
                'pendentes'  => $kpiPendentes,
                'analise'    => $kpiAnalise,
                'resolvidos' => $kpiResolvidos,
            ],
            'filtros'       => [
                'categoria' => $categoriaId,
                'estado'    => $estadoId,
                'municipio' => $municipioId,
                'q'         => $busca,
                'ordem'     => $ordem,
            ],
        ], 'admin');
    }

    public function eliminarProblema(int $id): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Token de segurança inválido.');
            $this->redirect('/admin/problemas');
        }

        $problema = $this->problemaModel->findById($id);
        if (!$problema) {
            flash('error', 'Ocorrência não encontrada.');
            $this->redirect('/admin/problemas');
        }

        $sucesso = $this->problemaModel->eliminarProblema($id);

        if ($sucesso) {
            SecurityLogger::log('ADMIN_PROBLEM_DELETED', "Ocorrência #{$id} eliminada por moderação", [
                'problema_id' => $id,
                'titulo'      => $problema['titulo'],
                'admin_id'    => $_SESSION['user_id']
            ]);
            flash('success', "Ocorrência '{$problema['titulo']}' eliminada com sucesso.");
        } else {
            flash('error', 'Não foi possível eliminar a ocorrência.');
        }

        $this->redirect('/admin/problemas');
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
        $origem = sanitizeString($_POST['origem'] ?? '');
        $destino = ($origem === 'dashboard') ? '/admin' : '/admin/problemas';
        $this->redirect($destino);
    }

    // ── GESTÃO DE UTILIZADORES & MODERAÇÃO DE BONS MODOS ──────────────────

    public function usuarios(): void
    {
        $aba = sanitizeString($_GET['aba'] ?? 'cidadaos');
        if (!in_array($aba, ['cidadaos', 'verificacoes', 'equipa'], true)) {
            $aba = 'cidadaos';
        }

        $busca       = sanitizeString($_GET['q'] ?? '');
        $status      = sanitizeString($_GET['status'] ?? '');
        $bi          = sanitizeString($_GET['bi'] ?? '');
        $municipioId = (int) ($_GET['municipio'] ?? 0);

        // Se estiver na aba de verificações e sem filtro explícito, focar nos pendentes
        if ($aba === 'verificacoes' && empty($bi)) {
            $bi = 'pendente';
        }

        $cidadaos = $this->userModel->listarComEstatisticas($busca, $status, $bi, $municipioId, 'cidadao');
        $equipaBruta = $this->userModel->listarComEstatisticas($busca, $status, '', 0, 'admin');

        // O Administrador Principal (Super Admin / Conta Raiz) não aparece na tabela operacional da equipa
        $equipa = array_values(array_filter($equipaBruta, fn($adm) => $adm['email'] !== 'admin@djumbai.ao' && (int)$adm['id'] !== 1));

        // Contagens globais para badges das abas
        $todosCidadaos    = $this->userModel->listarComEstatisticas('', '', '', 0, 'cidadao');
        $totalPendentesBi = count(array_filter($todosCidadaos, fn($u) => ($u['bi_status'] ?? '') === 'pendente'));

        $db = Database::getInstance();
        $municipios = $db->query("SELECT id, nome FROM municipios ORDER BY nome ASC")->fetchAll();

        $this->render('admin/usuarios', [
            'titulo'           => 'Gestão de Utilizadores, Verificação & Equipa – Djumbai',
            'aba'              => $aba,
            'cidadaos'         => $cidadaos,
            'equipa'           => $equipa,
            'totalCidadaos'    => count($todosCidadaos),
            'totalPendentesBi' => $totalPendentesBi,
            'totalEquipa'      => count($equipa),
            'municipios'       => $municipios,
            'filtros'          => [
                'q'         => $busca,
                'status'    => $status,
                'bi'        => $bi,
                'municipio' => $municipioId,
            ],
        ], 'admin');
    }

    public function avaliarDocumentoBi(int $id): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Token de segurança inválido.');
            $this->redirect('/admin/usuarios?aba=verificacoes');
        }

        $acao       = sanitizeString($_POST['acao'] ?? '');
        $motivo     = sanitizeString($_POST['motivo'] ?? '');
        $abaOrigem  = sanitizeString($_POST['aba'] ?? 'verificacoes');
        $novoStatus = ($acao === 'aprovar') ? 'verificado' : 'rejeitado';

        $user = $this->userModel->findById($id);
        if (!$user) {
            flash('error', 'Utilizador não encontrado.');
            $this->redirect('/admin/usuarios?aba=' . urlencode($abaOrigem));
        }

        $sucesso = $this->userModel->avaliarDocumentoBI($id, $novoStatus, $motivo, (int)$_SESSION['user_id']);

        if ($sucesso) {
            SecurityLogger::log('ADMIN_BI_EVALUATED', "BI do utilizador #{$id} ({$user['nome']}) avaliado como {$novoStatus}", [
                'target_user_id' => $id,
                'resultado'      => $novoStatus,
                'motivo'         => $motivo,
                'admin_id'       => $_SESSION['user_id']
            ]);

            $msg = ($novoStatus === 'verificado')
                ? "Documento de '{$user['nome']}' aprovado com sucesso! Selo de Munícipe Verificado atribuído."
                : "Documento de '{$user['nome']}' recusado e notificação enviada.";
            flash('success', $msg);
        } else {
            flash('error', 'Não foi possível atualizar o estado de verificação.');
        }

        $this->redirect('/admin/usuarios?aba=' . urlencode($abaOrigem));
    }

    public function criarAdmin(): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Token de segurança inválido.');
            $this->redirect('/admin/usuarios?aba=equipa');
        }

        $nome     = sanitizeString($_POST['nome'] ?? '');
        $email    = sanitizeEmail($_POST['email'] ?? '');
        $telefone = sanitizeString($_POST['telefone'] ?? '');
        $senha    = $_POST['senha'] ?? '';

        if (mb_strlen($nome, 'UTF-8') < 3) {
            flash('error', 'O nome do administrador deve ter pelo menos 3 caracteres.');
            $this->redirect('/admin/usuarios?aba=equipa');
        }

        if (!isValidEmail($email)) {
            flash('error', 'Por favor insira um endereço de e-mail institucional válido.');
            $this->redirect('/admin/usuarios?aba=equipa');
        }

        if ($this->userModel->emailExists($email)) {
            flash('error', 'Este e-mail já está em utilização por outro utilizador.');
            $this->redirect('/admin/usuarios?aba=equipa');
        }

        if (!empty($telefone) && !isValidAngolaPhone($telefone)) {
            flash('error', 'Insira um número de telefone válido de Angola.');
            $this->redirect('/admin/usuarios?aba=equipa');
        }

        if (mb_strlen($senha, 'UTF-8') < 8) {
            flash('error', 'A palavra-passe deve conter pelo menos 8 caracteres.');
            $this->redirect('/admin/usuarios?aba=equipa');
        }

        $adminId = $this->userModel->criarAdministrador($nome, $email, $senha, $telefone);

        SecurityLogger::log('ADMIN_CREATED', "Nova conta administrativa registada para {$email}", [
            'novo_admin_id' => $adminId,
            'criado_por'    => $_SESSION['user_id']
        ]);

        flash('success', "Novo administrador '{$nome}' adicionado à equipa com sucesso.");
        $this->redirect('/admin/usuarios?aba=equipa');
    }

    public function dossieUsuario(int $id): void
    {
        $dossie = $this->userModel->getDossieCompleto($id);
        if (!$dossie) {
            $this->json(['error' => 'Utilizador não encontrado.'], 404);
        }
        $this->json($dossie);
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

        $this->problemaModel->criarNotificacao($id, null, "Notificação de Bons Modos: " . $mensagem);

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

    /**
     * Visualização segura de documento de identificação (BI) por administradores
     */
    public function verDocumento(int $id, string $tipo): void
    {
        $user = $this->userModel->findById($id);
        if (!$user) {
            http_response_code(404);
            die('Utilizador não encontrado.');
        }

        $arquivo = ($tipo === 'frente') ? ($user['bi_frente'] ?? null) : ($user['bi_verso'] ?? null);
        if (empty($arquivo)) {
            http_response_code(404);
            die('Documento não disponível.');
        }

        $caminho = ROOT_PATH . '/storage/uploads/' . $arquivo;
        if (!file_exists($caminho)) {
            http_response_code(404);
            die('Ficheiro de documento não encontrado no armazenamento.');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $caminho);
        finfo_close($finfo);

        $nomeSeguro = basename($arquivo);
        header('Content-Type: ' . ($mime ?: 'application/octet-stream'));
        // Forçar download (attachment) para impedir que PDFs com JS sejam renderizados inline
        header('Content-Disposition: attachment; filename="' . $nomeSeguro . '"');
        header('Content-Length: ' . filesize($caminho));
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, no-cache, no-store');
        readfile($caminho);
        exit;
    }

    // ── MODERAÇÃO DE COMENTÁRIOS E RECLAMAÇÕES ──────────────────────────

    public function comentarios(): void
    {
        $comentarios = $this->problemaModel->listarTodosComentarios();

        $this->render('admin/comentarios', [
            'titulo'      => 'Moderação de Comentários – Djumbai Admin',
            'comentarios' => $comentarios,
        ], 'admin_comentarios');
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

    // ── REPORTAR OCORRÊNCIA PELO ADMIN (SEM SAIR PARA O FRONTEND) ─────────

    public function reportarForm(): void
    {
        $categorias = $this->categoriaModel->findAll();
        $db         = Database::getInstance();
        $provincias = $db->query("SELECT id, nome FROM provincias ORDER BY nome ASC")->fetchAll();

        $this->render('admin/reportar', [
            'titulo'     => 'Registo Oficial de Ocorrência – Djumbai Admin',
            'categorias' => $categorias,
            'provincias' => $provincias,
            'errors'     => [],
            'old'        => [],
        ], 'admin_reportar');
    }

    public function guardarReporte(): void
    {
        if (!verifyCsrf()) {
            flash('error', 'Token de segurança inválido. Tente novamente.');
            $this->redirect('/admin/reportar');
        }

        $titulo          = sanitizeString($_POST['titulo'] ?? '');
        $descricao       = sanitizeString($_POST['descricao'] ?? '');
        $referenciaLocal = sanitizeString($_POST['referencia_local'] ?? '');
        $categoriaId     = (int) ($_POST['categoria_id'] ?? 0);
        $bairroId        = (int) ($_POST['bairro_id'] ?? 0);

        $errors = [];

        if (mb_strlen($titulo, 'UTF-8') < 5 || mb_strlen($titulo, 'UTF-8') > 150) {
            $errors['titulo'] = 'O título deve ter entre 5 e 150 caracteres.';
        }

        if (mb_strlen($descricao, 'UTF-8') < 10) {
            $errors['descricao'] = 'A descrição deve ter pelo menos 10 caracteres.';
        }

        if ($categoriaId <= 0) {
            $errors['categoria'] = 'Seleccione uma categoria técnica válida.';
        }

        if ($bairroId <= 0) {
            $errors['bairro'] = 'Seleccione a localização territorial da ocorrência.';
        }

        // Upload de imagem opcional
        $fotoPath = null;
        if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $maxBytes     = 5 * 1024 * 1024; // 5 MB
            $allowedExts  = ['jpg', 'jpeg', 'png', 'webp'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

            $file    = $_FILES['foto'];
            $rawExt  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if ($file['size'] > $maxBytes) {
                $errors['foto'] = 'A fotografia não pode exceder 5 MB.';
            } elseif (!in_array($rawExt, $allowedExts, true)) {
                $errors['foto'] = 'Formato não suportado. Apenas JPG, PNG ou WebP.';
            } else {
                $finfo    = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mimeType, $allowedMimes, true)) {
                    $errors['foto'] = 'Tipo MIME de imagem inválido.';
                } else {
                    $uploadDir = ROOT_PATH . '/public/assets/images/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $safeExt    = ($rawExt === 'jpeg') ? 'jpg' : $rawExt;
                    $uniqueName = 'admin_prob_' . bin2hex(random_bytes(16)) . '.' . $safeExt;
                    $targetFile = $uploadDir . $uniqueName;

                    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                        $fotoPath = 'uploads/' . $uniqueName;
                    } else {
                        $errors['foto'] = 'Erro ao guardar fotografia no servidor.';
                    }
                }
            }
        }

        if (!empty($errors)) {
            $categorias = $this->categoriaModel->findAll();
            $db         = Database::getInstance();
            $provincias = $db->query("SELECT id, nome FROM provincias ORDER BY nome ASC")->fetchAll();

            $this->render('admin/reportar', [
                'titulo'     => 'Registo Oficial de Ocorrência – Djumbai Admin',
                'categorias' => $categorias,
                'provincias' => $provincias,
                'errors'     => $errors,
                'old'        => compact('titulo', 'descricao', 'referenciaLocal', 'categoriaId'),
            ], 'admin_reportar');
            return;
        }

        $problemaId = $this->problemaModel->create([
            'titulo'           => $titulo,
            'descricao'        => $descricao,
            'foto'             => $fotoPath,
            'referencia_local' => $referenciaLocal ?: null,
            'user_id'          => (int) $_SESSION['user_id'],
            'categoria_id'     => $categoriaId,
            'bairro_id'        => $bairroId,
            'estado_id'        => 1, // Pendente / Aberto
            'criado_em'        => date('Y-m-d H:i:s'),
        ]);

        SecurityLogger::log('ADMIN_PROBLEM_CREATED', "Nova ocorrência oficial registada pelo administrador ID #{$_SESSION['user_id']}", [
            'problema_id' => $problemaId,
            'admin_id'    => $_SESSION['user_id']
        ]);

        flash('success', "Ocorrência '{$titulo}' registada com sucesso no Centro de Gestão!");
        $this->redirect('/admin/problemas');
    }
}
