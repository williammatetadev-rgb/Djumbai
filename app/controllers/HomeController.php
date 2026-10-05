<?php
/**
 * Djumbai — HomeController
 * Trata da Homepage e páginas de conteúdo público
 */

require_once APP_PATH . '/Models/ProblemaModel.php';

class HomeController extends Controller
{
    private ProblemaModel $problemaModel;

    public function __construct()
    {
        $this->problemaModel = new ProblemaModel();
    }

    /**
     * GET /
     * Exibe a homepage com panorama geral e problemas em destaque
     */
    public function index(): void
    {
        $panorama         = $this->problemaModel->getPanorama();
        $destaque         = $this->problemaModel->getMaisReportados(5);

        $this->render('home/index', [
            'titulo'   => 'Djumbai – A voz do povo de Angola',
            'panorama' => $panorama,
            'destaque' => $destaque,
        ]);
    }

    /**
     * GET /como-funciona
     * Página estática de explicação do processo cívico
     */
    public function comoFunciona(): void
    {
        $this->render('home/como_funciona', [
            'titulo' => 'Como Funciona – Djumbai',
        ]);
    }
}
