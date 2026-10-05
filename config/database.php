<?php
/**
 * Djumbai — Configuração Segura da Base de Dados
 * Conexão via PDO com parâmetros carregados do .env e proteção contra vazamento de credenciais
 */

class Database
{
    private static ?PDO $instance = null;

    /**
     * Retorna a instância única de PDO (Singleton)
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $host    = Env::get('DB_HOST', 'localhost');
            $dbName  = Env::get('DB_NAME', 'djumbai');
            $user    = Env::get('DB_USER', 'root');
            $pass    = Env::get('DB_PASS', '');
            $charset = Env::get('DB_CHARSET', 'utf8mb4');

            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $dbName, $charset);

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // Registra o erro de segurança sem exibir credenciais no ecrã
                if (class_exists('SecurityLogger')) {
                    SecurityLogger::log('DATABASE_CONNECTION_ERROR', 'Falha na conexão com o banco de dados.');
                }
                error_log('[Djumbai DB Error] ' . $e->getMessage());

                $isDebug = Env::get('APP_DEBUG', false) === 'true';
                if ($isDebug) {
                    die('Erro na Base de Dados: Não foi possível estabelecer conexão.');
                } else {
                    http_response_code(503);
                    die('<!DOCTYPE html><html lang="pt"><head><meta charset="UTF-8"><title>Serviço Indisponível</title></head><body style="font-family:sans-serif;padding:40px;text-align:center;"><h2>Serviço temporariamente indisponível</h2><p>Estamos a efetuar uma manutenção técnica. Por favor, tente novamente dentro de instantes.</p></body></html>');
                }
            }
        }

        return self::$instance;
    }

    private function __clone() {}
    public function __wakeup() {}
}
