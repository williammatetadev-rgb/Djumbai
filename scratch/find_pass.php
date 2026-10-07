<?php
$passes = ['', 'root', 'root123', 'admin', '123456', '12345678', 'mysql', 'Djumbai@2026', 'Admin@2026', 'teste', 'password'];
$mysql = '"C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysql.exe"';

foreach ($passes as $p) {
    $cmd = "$mysql -u root -p\"$p\" -e \"SHOW DATABASES;\" 2>&1";
    $output = shell_exec($cmd);
    if ($output && !str_contains($output, 'Access denied')) {
        echo "FOUND PASSWORD: '$p'\nOutput: $output\n";
        file_put_contents(__DIR__ . '/found_pass.txt', $p);
        exit;
    }
}
echo "No password found in list.\n";
