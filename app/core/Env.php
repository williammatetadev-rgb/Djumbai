<?php
/**
 * Djumbai — Carregador Seguro de Variáveis de Ambiente (.env)
 * Evita expor credenciais no código-fonte
 */

class Env
{
    private static array $variables = [];

    /**
     * Carrega o arquivo .env se existir
     */
    public static function load(string $filePath): void
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);

            // Ignora comentários
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            // Separa chave e valor
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key   = trim($key);
                $value = trim($value);

                // Remove aspas simples ou duplas envolventes
                if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                    (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                    $value = substr($value, 1, -1);
                }

                self::$variables[$key] = $value;
                $_ENV[$key]            = $value;
                putenv("{$key}={$value}");
            }
        }
    }

    /**
     * Obtém uma variável de ambiente com valor padrão de fallback
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (isset(self::$variables[$key])) {
            return self::$variables[$key];
        }

        $envVal = getenv($key);
        if ($envVal !== false) {
            return $envVal;
        }

        return $default;
    }
}
