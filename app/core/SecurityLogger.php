<?php
/**
 * Djumbai — Sistema de Logs e Monitorização de Segurança
 * Regista incidentes, tentativas de invasão, auditoria de admins sem expor dados sensíveis
 */

class SecurityLogger
{
    private static string $logFile = ROOT_PATH . '/storage/logs/security.log';

    /**
     * Regista um evento de segurança no log
     *
     * @param string $eventType Tipo do evento (ex: FAILED_LOGIN, CSRF_VIOLATION, etc.)
     * @param string $message   Descrição do evento
     * @param array  $context   Metadados adicionais (sem senhas/tokens)
     */
    public static function log(string $eventType, string $message, array $context = []): void
    {
        // Sanitiza contexto para garantir que nenhuma senha ou token seja gravado
        unset($context['senha'], $context['password'], $context['senha_hash'], $context['_csrf_token']);

        $ip        = self::getClientIp();
        $userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 150);
        $userId    = $_SESSION['user_id'] ?? 'guest';
        $timestamp = date('Y-m-d H:i:s');

        $logEntry = sprintf(
            "[%s] [%s] [IP:%s] [UID:%s] %s | Context: %s | UA: %s\n",
            $timestamp,
            strtoupper($eventType),
            $ip,
            $userId,
            $message,
            !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : '{}',
            $userAgent
        );

        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0750, true);
        }

        error_log($logEntry, 3, self::$logFile);
    }

    /**
     * Obtém o IP do cliente de forma segura.
     *
     * Se REMOTE_ADDR pertencer à lista TRUSTED_PROXIES (definida no .env),
     * lê o cabeçalho X-Forwarded-For e devolve o IP mais à esquerda (o real cliente).
     * Caso contrário, usa sempre REMOTE_ADDR (seguro contra spoofing).
     */
    public static function getClientIp(): string
    {
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        // Constrói lista de proxies confiáveis a partir da variável de ambiente
        // Ex.: TRUSTED_PROXIES=10.0.0.1,10.0.0.2
        $trustedProxies = [];
        $envProxies = $_ENV['TRUSTED_PROXIES'] ?? getenv('TRUSTED_PROXIES') ?? '';
        if ($envProxies !== '' && $envProxies !== false) {
            foreach (explode(',', $envProxies) as $proxy) {
                $proxy = trim($proxy);
                if (filter_var($proxy, FILTER_VALIDATE_IP)) {
                    $trustedProxies[] = $proxy;
                }
            }
        }

        // Só lê X-Forwarded-For se o pedido vier de um proxy de confiança
        if (!empty($trustedProxies) && in_array($remoteAddr, $trustedProxies, true)) {
            $xff = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
            if ($xff !== '') {
                // X-Forwarded-For: client, proxy1, proxy2 — o cliente real é o primeiro
                $parts = explode(',', $xff);
                $clientIp = trim($parts[0]);
                if (filter_var($clientIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $clientIp;
                }
            }
        }

        // Fallback seguro: usa sempre REMOTE_ADDR
        if (filter_var($remoteAddr, FILTER_VALIDATE_IP)) {
            return $remoteAddr;
        }

        return '0.0.0.0';
    }
}
