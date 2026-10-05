<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
require_once APP_PATH . '/core/helpers.php';

$testNames = [
    '67849tgjfi35orjsfnvbe45rifwnr' => false, // Garbage com números
    'João Mbemba'                  => true,  // Nome válido
    'Maria da Silva'               => true,  // Nome válido
    'Admin123'                     => false, // Contém números
    '123456789'                    => false, // Apenas números
    'Ana-Paula'                    => true,  // Nome com hífen
];

echo "=== TESTE DE VALIDAÇÃO DE NOME ===\n";
foreach ($testNames as $name => $expected) {
    $result = isValidHumanName($name);
    $status = ($result === $expected) ? "PASS" : "FAIL";
    echo sprintf("[%s] '%s' => Result: %s (Expected: %s)\n", $status, $name, $result ? 'VALID' : 'INVALID', $expected ? 'VALID' : 'INVALID');
}
