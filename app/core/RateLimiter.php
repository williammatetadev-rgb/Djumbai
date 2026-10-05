<?php
/**
 * Djumbai — Sistema de Rate Limiting & Proteção Anti-Brute Force
 * Limita tentativas de login, cadastro, envios de formulários e acessos excessivos por IP
 */

class RateLimiter
{
    private static string $storageDir = ROOT_PATH . '/storage/logs/ratelimit';

    /**
     * Verifica se uma determinada acção excedeu o limite permitido
     *
     * @param string $action      Nome da acção (ex: 'login', 'cadastro', 'reportar')
     * @param int    $maxAttempts Número máximo de tentativas
     * @param int    $decaySeconds Tempo de bloqueio/janela em segundos
     * @return bool True se excedeu o limite (bloqueado), False se permitido
     */
    public static function tooManyAttempts(string $action, int $maxAttempts = 5, int $decaySeconds = 900): bool
    {
        $key  = self::getKey($action);
        $data = self::read($key);

        if (!$data) {
            return false;
        }

        // Se a janela expirou, limpa e libera
        if (time() > $data['expires_at']) {
            self::clear($action);
            return false;
        }

        return $data['attempts'] >= $maxAttempts;
    }

    /**
     * Incrementa o contador de tentativas para a acção
     */
    public static function hit(string $action, int $decaySeconds = 900): int
    {
        $key  = self::getKey($action);
        $data = self::read($key);

        if (!$data || time() > $data['expires_at']) {
            $data = [
                'attempts'   => 1,
                'expires_at' => time() + $decaySeconds,
            ];
        } else {
            $data['attempts']++;
        }

        self::write($key, $data);
        return $data['attempts'];
    }

    /**
     * Retorna quantos segundos restam até que o bloqueio expire
     */
    public static function availableIn(string $action): int
    {
        $key  = self::getKey($action);
        $data = self::read($key);

        if (!$data) {
            return 0;
        }

        return max(0, $data['expires_at'] - time());
    }

    /**
     * Limpa as tentativas após sucesso (ex: login bem-sucedido)
     */
    public static function clear(string $action): void
    {
        $key      = self::getKey($action);
        $filePath = self::getFilePath($key);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }

    /**
     * Gera uma chave única combinando acção e IP do cliente
     */
    private static function getKey(string $action): string
    {
        $ip = SecurityLogger::getClientIp();
        return md5("djumbai_rl_{$action}_{$ip}");
    }

    private static function getFilePath(string $key): string
    {
        if (!is_dir(self::$storageDir)) {
            mkdir(self::$storageDir, 0750, true);
        }
        return self::$storageDir . '/' . $key . '.json';
    }

    private static function read(string $key): ?array
    {
        $file = self::getFilePath($key);
        if (!file_exists($file)) {
            return null;
        }

        $content = file_get_contents($file);
        if (!$content) {
            return null;
        }

        $data = json_decode($content, true);
        return is_array($data) ? $data : null;
    }

    private static function write(string $key, array $data): void
    {
        $file = self::getFilePath($key);
        file_put_contents($file, json_encode($data), LOCK_EX);
    }
}
