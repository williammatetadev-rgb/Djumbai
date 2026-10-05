<?php
/**
 * Djumbai — ProblemaController com Blindagem de Uploads, CSRF e Rate Limiting
 */

require_once APP_PATH . '/Models/ProblemaModel.php';
require_once APP_PATH . '/Models/CategoriaModel.php';

class ProblemaController extends Controller
{
    private ProblemaModel  $problemaModel;
    private CategoriaModel $categoriaModel;

    public function __construct()
    {
        $this->problemaModel  = new ProblemaModel();
        $this->categoriaModel = new CategoriaModel();
    }

    public function index(): void
    {
        $busca       = sanitizeString($_GET['q'] ?? '');
        $categoriaId = max(0, (int) ($_GET['categoria'] ?? 0));
        $estadoId    = max(0, (int) ($_GET['estado']    ?? 0));
        $municipioId = max(0, (int) ($_GET['municipio'] ?? 0));
        $ordem       = sanitizeString($_GET['ordem']    ?? 'populares');
        $pagina      = max(1, (int) ($_GET['pagina']    ?? 1));
        $porPagina   = 10;
        $offset      = ($pagina - 1) * $porPagina;

        $problemas  = $this->problemaModel->listar($categoriaId, $estadoId, $porPagina, $offset, $busca, $municipioId, $ordem);
        $total      = $this->problemaModel->totalFiltrado($categoriaId, $estadoId, $busca, $municipioId);
        $categorias = $this->categoriaModel->findAll();

        $db         = Database::getInstance();
        $municipios = $db->query("SELECT id, nome FROM municipios ORDER BY nome ASC")->fetchAll();

        $this->render('problemas/index', [
            'titulo'     => 'Explorar Ocorrências – Djumbai',
            'problemas'  => $problemas,
            'categorias' => $categorias,
            'municipios' => $municipios,
            'total'      => $total,
            'pagina'     => $pagina,
            'porPagina'  => $porPagina,
            'filtros'    => [
                'q'         => $busca,
                'categoria' => $categoriaId,
                'estado'    => $estadoId,
                'municipio' => $municipioId,
                'ordem'     => $ordem,
            ],
        ]);
    }

    public function show(int $id): void
    {
        $id = (int) $id;
        if ($id <= 0) {
            http_response_code(404);
            $this->render('errors/404', ['titulo' => 'Problema não encontrado']);
            return;
        }

        $problema = $this->problemaModel->getDetalhes($id);

        if (!$problema) {
            http_response_code(404);
            $this->render('errors/404', ['titulo' => 'Problema não encontrado']);
            return;
        }

        $jaConfirmou = false;
        if (isLoggedIn()) {
            $jaConfirmou = $this->problemaModel->jaConfirmou($id, (int)$_SESSION['user_id']);
        }

        $comentarios = $this->problemaModel->getComentarios($id);

        $this->render('problemas/show', [
            'titulo'      => e($problema['titulo']) . ' – Djumbai',
            'problema'    => $problema,
            'jaConfirmou' => $jaConfirmou,
            'comentarios' => $comentarios,
        ]);
    }

    public function criar(): void
    {
        $this->requireAuth();

        $categorias = $this->categoriaModel->findAll();
        $db         = Database::getInstance();
        $provincias = $db->query("SELECT id, nome FROM provincias ORDER BY nome ASC")->fetchAll();

        $this->render('problemas/criar', [
            'titulo'     => 'Reportar Problema – Djumbai',
            'categorias' => $categorias,
            'provincias' => $provincias,
            'errors'     => [],
        ]);
    }

    public function guardar(): void
    {
        $this->requireAuth();

        // 1. Rate limiting de criação para evitar spam de reportes (definido nas configurações)
        $maxReportes = (int) siteSetting('max_reportes_hora', 10);
        if (RateLimiter::tooManyAttempts('reportar', $maxReportes, 3600)) {
            SecurityLogger::log('RATE_LIMIT_REPORT', 'Muitos reportes submetidos pelo mesmo utilizador/IP.');
            flash('error', "Atingiu o limite de publicações por hora ({$maxReportes}/hora). Por favor, tente mais tarde.");
            $this->redirect('/reportar');
        }

        // 2. Verificação CSRF
        if (!verifyCsrf()) {
            flash('error', 'Pedido inválido ou sessão expirada.');
            $this->redirect('/reportar');
        }

        $titulo          = sanitizeString($_POST['titulo']          ?? '');
        $descricao       = sanitizeString($_POST['descricao']        ?? '');
        $referenciaLocal = sanitizeString($_POST['referencia_local'] ?? '');
        $categoriaId     = (int) ($_POST['categoria_id'] ?? 0);
        $bairroId        = (int) ($_POST['bairro_id']    ?? 0);

        $errors = [];

        if (mb_strlen($titulo, 'UTF-8') < 5 || mb_strlen($titulo, 'UTF-8') > 150) {
            $errors['titulo'] = 'O título deve ter entre 5 e 150 caracteres.';
        }

        if (mb_strlen($descricao, 'UTF-8') < 20 || mb_strlen($descricao, 'UTF-8') > 2000) {
            $errors['descricao'] = 'A descrição deve ter entre 20 e 2000 caracteres.';
        }

        if ($categoriaId <= 0) {
            $errors['categoria'] = 'Seleccione uma categoria válida.';
        }

        if ($bairroId <= 0) {
            $errors['bairro'] = 'Seleccione a localização do problema.';
        }

        // 3. Processamento e validação estrita de upload de imagem
        $fotoPath = null;
        if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $fotoPath = $this->processarUploadSeguro($_FILES['foto'], $errors);
        }

        if (!empty($errors)) {
            RateLimiter::hit('reportar', 3600);

            $categorias = $this->categoriaModel->findAll();
            $db         = Database::getInstance();
            $provincias = $db->query("SELECT id, nome FROM provincias ORDER BY nome ASC")->fetchAll();

            $this->render('problemas/criar', [
                'titulo'      => 'Reportar Problema – Djumbai',
                'categorias'  => $categorias,
                'provincias'  => $provincias,
                'errors'      => $errors,
                'old'         => compact('titulo', 'descricao', 'referenciaLocal', 'categoriaId'),
            ]);
            return;
        }

        $aprovacaoAuto = (bool) siteSetting('aprovacao_automatica', true);
        $estadoInicial = $aprovacaoAuto ? 1 : 2; // 1 = Pendente (Aberto), 2 = Em Análise / Moderação

        $problemaId = $this->problemaModel->create([
            'titulo'           => $titulo,
            'descricao'        => $descricao,
            'foto'             => $fotoPath,
            'referencia_local' => $referenciaLocal ?: null,
            'user_id'          => (int) $_SESSION['user_id'],
            'categoria_id'     => $categoriaId,
            'bairro_id'        => $bairroId,
            'estado_id'        => $estadoInicial,
            'criado_em'        => date('Y-m-d H:i:s'),
        ]);

        SecurityLogger::log('PROBLEM_CREATED', "Novo problema reportado com ID: {$problemaId} (Aprovação Auto: " . ($aprovacaoAuto ? 'SIM' : 'NÃO') . ")");

        if ($aprovacaoAuto) {
            flash('success', 'O seu reporte foi submetido com sucesso! A comunidade já pode confirmá-lo.');
        } else {
            flash('info', 'O seu reporte foi submetido e está em fila de moderação prévia pelo administrador.');
        }
        $this->redirect('/problemas/' . $problemaId);
    }

    public function confirmar(int $id): void
    {
        $this->requireAuth();

        // 1. Proteção CSRF (aceita tanto formulário quanto header X-CSRF-Token)
        if (!verifyCsrf()) {
            $this->json(['erro' => 'Token de segurança inválido.'], 403);
        }

        // 2. Rate limiting em votações
        if (RateLimiter::tooManyAttempts('confirmar', 30, 60)) {
            $this->json(['erro' => 'Demasiadas acções em pouco tempo. Aguarde um instante.'], 429);
        }

        $problema = $this->problemaModel->findById($id);
        if (!$problema) {
            $this->json(['erro' => 'Problema não encontrado.'], 404);
        }

        $userId      = (int) $_SESSION['user_id'];
        $jaConfirmou = $this->problemaModel->jaConfirmou($id, $userId);

        if ($jaConfirmou) {
            $this->problemaModel->removerConfirmacao($id, $userId);
            $total = $this->problemaModel->totalConfirmacoes($id);
            $this->json(['confirmado' => false, 'total' => $total]);
        } else {
            $this->problemaModel->adicionarConfirmacao($id, $userId);
            $total = $this->problemaModel->totalConfirmacoes($id);
            $this->json(['confirmado' => true, 'total' => $total]);
        }
    }

    // ── Validação e Processamento Seguro de Uploads ────────────

    private function processarUploadSeguro(array $file, array &$errors): ?string
    {
        $maxBytes        = 5 * 1024 * 1024; // 5 MB
        $allowedMimes    = ['image/jpeg', 'image/png', 'image/webp'];
        $allowedExts     = ['jpg', 'jpeg', 'png', 'webp'];
        $uploadDir       = ROOT_PATH . '/public/assets/images/uploads/';

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['foto'] = 'Erro ao processar o arquivo enviado.';
            return null;
        }

        if ($file['size'] > $maxBytes || $file['size'] === 0) {
            $errors['foto'] = 'A fotografia não pode exceder 5 MB.';
            return null;
        }

        // 1. Validação de Extensão real (Whitelist estrita)
        $rawExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($rawExt, $allowedExts, true)) {
            SecurityLogger::log('UPLOAD_BLOCKED_EXT', "Extensão perigosa bloqueada: {$rawExt}");
            $errors['foto'] = 'Apenas formatos JPG, PNG e WebP são permitidos.';
            return null;
        }

        // 2. Validação do MIME type através de análise binária (libmagic)
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedMimes, true)) {
            SecurityLogger::log('UPLOAD_BLOCKED_MIME', "MIME type inválido bloqueado: {$mimeType}");
            $errors['foto'] = 'O arquivo enviado não é uma imagem válida.';
            return null;
        }

        // 3. Validação de integridade de imagem com getimagesize (Impede polyglots/scripts embutidos)
        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            SecurityLogger::log('UPLOAD_BLOCKED_INTEGRITY', 'Arquivo falhou na verificação de integridade de imagem.');
            $errors['foto'] = 'Arquivo corrompido ou formato de imagem não reconhecido.';
            return null;
        }

        // 4. Criação do directório com permissões restritas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 5. Geração de nome criptograficamente aleatório e seguro
        $safeExt   = ($rawExt === 'jpeg') ? 'jpg' : $rawExt;
        $uniqueName = 'djumbai_' . bin2hex(random_bytes(16)) . '.' . $safeExt;
        $targetFile = $uploadDir . $uniqueName;

        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            $errors['foto'] = 'Falha ao guardar imagem no servidor.';
            return null;
        }

        // Permissões seguras no ficheiro gravado (não-executável)
        @chmod($targetFile, 0644);

        return 'assets/images/uploads/' . $uniqueName;
    }

    /**
     * Adiciona um comentário a uma ocorrência (Cidadão autenticado)
     */
    public function comentar(int $id): void
    {
        $this->requireAuth();

        if (!verifyCsrf()) {
            flash('error', 'Token de segurança inválido.');
            $this->redirect('/problemas/' . $id);
        }

        $texto    = sanitizeString($_POST['texto'] ?? '');
        $parentId = (int) ($_POST['parent_id'] ?? 0);

        if (mb_strlen($texto, 'UTF-8') < 3 || mb_strlen($texto, 'UTF-8') > 1000) {
            flash('error', 'O seu comentário deve conter entre 3 e 1000 caracteres.');
            $this->redirect('/problemas/' . $id);
        }

        $problema = $this->problemaModel->findById($id);
        if (!$problema) {
            flash('error', 'Ocorrência não encontrada.');
            $this->redirect('/problemas');
        }

        $this->problemaModel->adicionarComentario(
            $id,
            (int) $_SESSION['user_id'],
            $texto,
            $parentId > 0 ? $parentId : null
        );

        SecurityLogger::log('COMMENT_ADDED', "Comentário/Resposta adicionado ao problema #{$id} pelo utilizador #{$_SESSION['user_id']}");

        flash('success', $parentId > 0 ? 'A sua resposta foi publicada com sucesso!' : 'O seu comentário foi publicado com sucesso!');
        $this->redirect('/problemas/' . $id);
    }
}

