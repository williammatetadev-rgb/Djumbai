<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH',  ROOT_PATH . '/app');
require_once APP_PATH . '/core/Env.php';
Env::load(ROOT_PATH . '/.env');
require_once ROOT_PATH . '/config/database.php';

try {
    $db = Database::getInstance();
    $db->exec("ALTER TABLE user ADD COLUMN genero ENUM('M', 'F') DEFAULT 'M' AFTER nacionalidade");
    echo "Sucesso!";
} catch(Exception $e) {
    echo "Erro (pode já existir): " . $e->getMessage();
}
