<?php
define('ROOT_PATH', __DIR__ . '/..');
define('APP_PATH', ROOT_PATH . '/app');
require_once APP_PATH . '/core/Env.php';
Env::load(ROOT_PATH . '/.env');
require_once ROOT_PATH . '/config/database.php';

$db = Database::getInstance();

echo "A atualizar base de dados para suporte ao Mini-Mapa Visual...\n";

// 1. Adicionar lat/lng a provincias
try {
    $db->exec("ALTER TABLE provincias ADD COLUMN latitude DECIMAL(10,8) DEFAULT NULL, ADD COLUMN longitude DECIMAL(11,8) DEFAULT NULL");
    echo "✔ Colunas 'latitude' e 'longitude' adicionadas à tabela 'provincias'.\n";
} catch (PDOException $e) {
    if (str_contains($e->getMessage(), 'Duplicate column')) {
        echo "ℹ Colunas 'latitude' e 'longitude' já existem em 'provincias'.\n";
    } else {
        echo "✖ Erro provincias: " . $e->getMessage() . "\n";
    }
}

// 2. Adicionar lat/lng a bairros
try {
    $db->exec("ALTER TABLE bairros ADD COLUMN latitude DECIMAL(10,8) DEFAULT NULL, ADD COLUMN longitude DECIMAL(11,8) DEFAULT NULL");
    echo "✔ Colunas 'latitude' e 'longitude' adicionadas à tabela 'bairros'.\n";
} catch (PDOException $e) {
    if (str_contains($e->getMessage(), 'Duplicate column')) {
        echo "ℹ Colunas 'latitude' e 'longitude' já existem em 'bairros'.\n";
    } else {
        echo "✖ Erro bairros: " . $e->getMessage() . "\n";
    }
}

// 3. Adicionar lat/lng a problemas
try {
    $db->exec("ALTER TABLE problemas ADD COLUMN latitude DECIMAL(10,8) DEFAULT NULL, ADD COLUMN longitude DECIMAL(11,8) DEFAULT NULL");
    echo "✔ Colunas 'latitude' e 'longitude' adicionadas à tabela 'problemas'.\n";
} catch (PDOException $e) {
    if (str_contains($e->getMessage(), 'Duplicate column')) {
        echo "ℹ Colunas 'latitude' e 'longitude' já existem em 'problemas'.\n";
    } else {
        echo "✖ Erro problemas: " . $e->getMessage() . "\n";
    }
}

// 4. Preencher coordenadas das Províncias de Angola
$provinciasCoords = [
    'Luanda'         => [-8.838333, 13.234444],
    'Benguela'       => [-12.5763,  13.4055],
    'Huambo'         => [-12.7761,  15.7392],
    'Bié'            => [-12.3833,  16.9333],
    'Malanje'        => [-9.5400,   16.3410],
    'Kwanza Norte'   => [-9.2978,   14.9108],
    'Kwanza Sul'     => [-11.2061,  13.8437],
    'Uíge'           => [-7.6083,   15.0611],
    'Zaire'          => [-6.2670,   14.2400],
    'Cabinda'        => [-5.5500,   12.2000],
    'Moxico'         => [-11.7833,  19.9167],
    'Lunda Norte'    => [-7.3800,   20.8300],
    'Lunda Sul'      => [-9.6600,   20.3900],
    'Huíla'          => [-14.9167,  13.4917],
    'Namibe'         => [-15.1961,  12.1522],
    'Cunene'         => [-17.0667,  15.7333],
    'Cuando Cubango' => [-14.6583,  17.6917],
    'Bengo'          => [-8.5783,   13.6644],
];

$stmtProv = $db->prepare("UPDATE provincias SET latitude = :lat, longitude = :lng WHERE nome = :nome");
foreach ($provinciasCoords as $nome => $coords) {
    $stmtProv->execute([':lat' => $coords[0], ':lng' => $coords[1], ':nome' => $nome]);
}
echo "✔ Coordenadas de " . count($provinciasCoords) . " províncias atualizadas.\n";

// 5. Preencher coordenadas dos Bairros de Luanda e Municípios
$bairrosCoords = [
    'Alvalade'         => [-8.8350, 13.2420],
    'Ingombota'        => [-8.8120, 13.2360],
    'Maianga'          => [-8.8320, 13.2300],
    'Rangel'           => [-8.8250, 13.2620],
    'Sambizanga'       => [-8.7980, 13.2510],
    'Samba'            => [-8.8550, 13.2100],
    'Miramar'          => [-8.8080, 13.2480],
    'Mutamba'          => [-8.8160, 13.2330],
    'Cidade Alta'      => [-8.8180, 13.2350],
    'Bairro Operário'  => [-8.8140, 13.2540],
    'Marçal'           => [-8.8280, 13.2680],
    'Bairro Azul'      => [-8.8290, 13.2380],
    'Talatona'         => [-8.9180, 13.1850],
    'Camama'           => [-8.9120, 13.2450],
    'Kilamba'          => [-8.9850, 13.2350],
    'Benfica'          => [-8.9480, 13.1620],
    'Morro Bento'      => [-8.8950, 13.1950],
    'Cacuaco Centro'   => [-8.7750, 13.3650],
    'Funda'            => [-8.8500, 13.5500],
    'Sequele'          => [-8.8020, 13.4350],
    'Cazenga'          => [-8.8280, 13.2920],
    'Hoji-ya-Henda'    => [-8.8200, 13.2800],
    'Tala Hady'        => [-8.8350, 13.3050],
    'Kilamba Kiaxi'    => [-8.8750, 13.2650],
    'Palanca'          => [-8.8620, 13.2750],
    'Golfe'            => [-8.8800, 13.2550],
    'Cassenda'         => [-8.8550, 13.2380],
    'Viana Centro'     => [-8.9050, 13.3750],
    'Mulenvos'         => [-8.8650, 13.3450],
    'Terra Nova'       => [-8.8450, 13.2750],
    'Prenda'           => [-8.8450, 13.2320],
];

$stmtBairro = $db->prepare("UPDATE bairros SET latitude = :lat, longitude = :lng WHERE nome = :nome");
foreach ($bairrosCoords as $nome => $coords) {
    $stmtBairro->execute([':lat' => $coords[0], ':lng' => $coords[1], ':nome' => $nome]);
}
echo "✔ Coordenadas de " . count($bairrosCoords) . " bairros atualizadas.\n";

// 6. Atualizar problemas existentes para herdarem lat/lng com pequenos offsets realistas
$stmtProbs = $db->query("
    SELECT pr.id, b.latitude, b.longitude 
    FROM problemas pr 
    JOIN bairros b ON b.id = pr.bairro_id 
    WHERE pr.latitude IS NULL OR pr.longitude IS NULL
");
$probsToUpdate = $stmtProbs->fetchAll();

$stmtUpdateProb = $db->prepare("UPDATE problemas SET latitude = :lat, longitude = :lng WHERE id = :id");
foreach ($probsToUpdate as $index => $row) {
    if ($row['latitude'] && $row['longitude']) {
        // Offset determinístico pequeno para múltiplos pontos no mesmo bairro não colidirem
        $offsetLat = (($row['id'] * 7) % 17 - 8) * 0.0015;
        $offsetLng = (($row['id'] * 13) % 17 - 8) * 0.0015;
        $stmtUpdateProb->execute([
            ':lat' => $row['latitude'] + $offsetLat,
            ':lng' => $row['longitude'] + $offsetLng,
            ':id'  => $row['id']
        ]);
    }
}
echo "✔ Coordenadas de " . count($probsToUpdate) . " problemas preenchidas.\n";

echo "Concluído!\n";
