<?php
define('ROOT_PATH', __DIR__ . '/..');
define('APP_PATH', ROOT_PATH . '/app');
require_once APP_PATH . '/core/Env.php';
Env::load(ROOT_PATH . '/.env');
require_once ROOT_PATH . '/config/database.php';

$db = Database::getInstance();

echo "A atualizar base de dados...\n";

// 1. Adicionar status a user
try {
    $db->exec("ALTER TABLE user ADD COLUMN status ENUM('ativo', 'banido') NOT NULL DEFAULT 'ativo'");
    echo "✔ Coluna 'status' adicionada à tabela 'user'.\n";
} catch (PDOException $e) {
    if (str_contains($e->getMessage(), 'Duplicate column')) {
        echo "ℹ Coluna 'status' já existe.\n";
    } else {
        echo "✖ Erro: " . $e->getMessage() . "\n";
    }
}

// 2. Adicionar ultimo_acesso a user
try {
    $db->exec("ALTER TABLE user ADD COLUMN ultimo_acesso DATETIME DEFAULT NULL");
    echo "✔ Coluna 'ultimo_acesso' adicionada à tabela 'user'.\n";
} catch (PDOException $e) {
    if (str_contains($e->getMessage(), 'Duplicate column')) {
        echo "ℹ Coluna 'ultimo_acesso' já existe.\n";
    } else {
        echo "✖ Erro: " . $e->getMessage() . "\n";
    }
}

// 3. Adicionar parent_id a comentarios (para respostas encadeadas)
try {
    $db->exec("ALTER TABLE comentarios ADD COLUMN parent_id INT UNSIGNED DEFAULT NULL AFTER user_id");
    echo "✔ Coluna 'parent_id' adicionada à tabela 'comentarios'.\n";
} catch (PDOException $e) {
    if (str_contains($e->getMessage(), 'Duplicate column')) {
        echo "ℹ Coluna 'parent_id' já existe.\n";
    } else {
        echo "✖ Erro: " . $e->getMessage() . "\n";
    }
}

// 4. Atualizar djumbai.sql
$sqlFile = ROOT_PATH . '/database/djumbai.sql';
if (file_exists($sqlFile)) {
    $sql = file_get_contents($sqlFile);
    if (!str_contains($sql, 'status ENUM')) {
        $sql = str_replace(
            "tipo       ENUM('cidadao','admin') NOT NULL DEFAULT 'cidadao',",
            "tipo       ENUM('cidadao','admin') NOT NULL DEFAULT 'cidadao',\n  status     ENUM('ativo','banido')   NOT NULL DEFAULT 'ativo',\n  ultimo_acesso DATETIME DEFAULT NULL,",
            $sql
        );
        file_put_contents($sqlFile, $sql);
        echo "✔ Ficheiro djumbai.sql actualizado.\n";
    }
    if (!str_contains($sql, 'parent_id')) {
        $sql = str_replace(
            "user_id     INT UNSIGNED NOT NULL,",
            "user_id     INT UNSIGNED NOT NULL,\n  parent_id   INT UNSIGNED DEFAULT NULL,",
            $sql
        );
        file_put_contents($sqlFile, $sql);
        echo "✔ Ficheiro djumbai.sql (parent_id) actualizado.\n";
    }
}

echo "Migração concluída com sucesso.\n";
