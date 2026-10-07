<?php
define('ROOT_PATH', __DIR__ . '/..');
define('APP_PATH', ROOT_PATH . '/app');
define('BASE_URL', '/djumbai/public');

require_once APP_PATH . '/core/Env.php';
Env::load(ROOT_PATH . '/.env');
require_once APP_PATH . '/core/SecurityHeaders.php';
require_once APP_PATH . '/core/SecurityLogger.php';
require_once APP_PATH . '/core/RateLimiter.php';
require_once ROOT_PATH . '/config/database.php';
require_once APP_PATH . '/core/Router.php';
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/core/helpers.php';
require_once APP_PATH . '/controllers/ProblemaController.php';

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/djumbai/public/mapa';

echo "=== TESTE RENDERING DA PÁGINA /mapa ===\n";
ob_start();
$controller = new ProblemaController();
$controller->mapa();
$output = ob_get_clean();

if (str_contains($output, 'Mini-Mapa Visual de Ocorrências') && str_contains($output, 'djumbai-leaflet-map')) {
    echo "✔ Renderização da view /mapa concluída com SUCESSO!\n";
    echo "Tamanho da resposta: " . strlen($output) . " bytes\n";
} else {
    echo "✖ Falha na renderização da view /mapa.\n";
    echo substr($output, 0, 500);
}

echo "\n=== TESTE ENDPOINT /api/mapa-ocorrencias ===\n";
ob_start();
$controller->apiPontosMapa();
$jsonOutput = ob_get_clean();

$data = json_decode($jsonOutput, true);
if ($data && isset($data['sucesso']) && $data['sucesso'] === true) {
    echo "✔ API JSON /api/mapa-ocorrencias retornou SUCESSO!\n";
    echo "Total de pontos no JSON: " . $data['total'] . "\n";
} else {
    echo "✖ Falha na API JSON.\n";
    echo substr($jsonOutput, 0, 500);
}
