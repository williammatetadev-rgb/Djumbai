<?php
define('ROOT_PATH', __DIR__ . '/..');
define('APP_PATH', ROOT_PATH . '/app');
require_once APP_PATH . '/core/Env.php';
Env::load(ROOT_PATH . '/.env');
require_once ROOT_PATH . '/config/database.php';
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/core/helpers.php';
require_once APP_PATH . '/models/ProblemaModel.php';
require_once APP_PATH . '/models/CategoriaModel.php';

$model = new ProblemaModel();
$pontos = $model->getPontosMapa();

echo "=== TESTE MODELO DE PONTOS MAPA ===\n";
echo "Total de pontos no mapa: " . count($pontos) . "\n";
foreach ($pontos as $p) {
    echo sprintf(
        "- [%s] ID #%d | %s | Bairro: %s | Mun: %s | Prov: %s | Lat: %f, Lng: %f | Confirm: %d\n",
        $p['estado_nome'],
        $p['id'],
        $p['titulo'],
        $p['bairro_nome'],
        $p['municipio_nome'],
        $p['provincia_nome'],
        $p['latitude'],
        $p['longitude'],
        $p['total_confirmacoes']
    );
}

echo "\n✔ Teste de dados do mapa concluído com sucesso!\n";
