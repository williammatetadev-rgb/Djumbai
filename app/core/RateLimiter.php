<?php
/**
 * Djumbai — Sistema de Rate Limiting & Proteção Anti-Brute Force
 * Limita tentativas de login, cadastro, envios de formulários e acessos excessivos por IP.
 * Protegido com flock() atómico contra race conditions e limpeza periódica de ficheiros expirados.
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
        self::garbageCollect();

        $key  = self::getKey($action);
        $file = self::getFilePath($key);

        if (!file_exists($file)) {
            return false;
        }

        $fp = @fopen($file, 'r');
        if (!$fp) {
            return false;
        }

        $data = null;
        if (flock($fp, LOCK_SH)) {
            $content = stream_get_contents($fp);
            if ($content) {
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $data = $decoded;
                }
            }
            flock($fp, LOCK_UN);
        }
        fclose($fp);

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
     * Incrementa o contador de tentativas para a acção de forma atómica com flock(LOCK_EX)
     */
    public static function hit(string $action, int $decaySeconds = 900): int
    {
        self::garbageCollect();

        $key  = self::getKey($action);
        $file = self::getFilePath($key);

        $fp = fopen($file, 'c+');
        if (!$fp) {
            return 1;
        }

        $attempts = 1;

        if (flock($fp, LOCK_EX)) {
            $content = stream_get_contents($fp);
            $data    = null;

            if ($content) {
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $data = $decoded;
                }
            }

            if (!$data || time() > ($data['expires_at'] ?? 0)) {
                $data = [
                    'attempts'   => 1,
                    'expires_at' => time() + $decaySeconds,
                ];
            } else {
                $data['attempts']++;
            }

            $attempts = $data['attempts'];

            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, json_encode($data));
            fflush($fp);
            flock($fp, LOCK_UN);
        }

        fclose($fp);
        return $attempts;
    }

    /**
     * Retorna quantos segundos restam até que o bloqueio expire
     */
    public static function availableIn(string $action): int
    {
        $key  = self::getKey($action);
        $file = self::getFilePath($key);

        if (!file_exists($file)) {
            return 0;
        }

        $fp = @fopen($file, 'r');
        if (!$fp) {
            return 0;
        }

        $data = null;
        if (flock($fp, LOCK_SH)) {
            $content = stream_get_contents($fp);
            if ($content) {
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $data = $decoded;
                }
            }
            flock($fp, LOCK_UN);
        }
        fclose($fp);

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
     * Limpeza probabilística (garbage collection) de ficheiros expirados (1 em 50 pedidos)
     */
    public static function garbageCollect(): void
    {
        if (mt_rand(1, 50) !== 1) {
            return;
        }

        if (!is_dir(self::$storageDir)) {
            return;
        }

        $now   = time();
        $files = @glob(self::$storageDir . '/*.json');

        if (!is_array($files)) {
            return;
        }

        foreach ($files as $filePath) {
            $fp = @fopen($filePath, 'r');
            if (!$fp) {
                continue;
            }

            $shouldDelete = false;
            if (flock($fp, LOCK_SH)) {
                $content = stream_get_contents($fp);
                if ($content) {
                    $data = json_decode($content, true);
                    if (is_array($data) && isset($data['expires_at']) && $now > $data['expires_at']) {
                        $shouldDelete = true;
                    }
                } else {
                    $shouldDelete = true; // Arquivo vazio/corrompido
                }
                flock($fp, LOCK_UN);
            }
            fclose($fp);

            if ($shouldDelete) {
                @unlink($filePath);
            }
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
}
