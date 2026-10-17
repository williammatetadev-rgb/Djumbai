<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/core/Env.php';
Env::load(ROOT_PATH . '/.env');
require_once ROOT_PATH . '/config/database.php';

try {
    $db = Database::getInstance();
    $db->exec("UPDATE user SET genero = 'M', nacionalidade = 'Angolano' WHERE id = 6");
    echo "User 6 atualizado com sucesso!\n";
} catch(Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
