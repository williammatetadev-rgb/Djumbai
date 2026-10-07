<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=djumbai;charset=utf8mb4", 'root', '');
    echo "SUCCESS CONNECTED TO MARIADB ON PORT 3307!\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(', ', $tables) . "\n";
} catch (PDOException $e) {
    echo "FAIL: " . $e->getMessage() . "\n";
}
