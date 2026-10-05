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
     * Obtém o IP do cliente de forma segura
     */
    public static function getClientIp(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        // Valida se o IP é válido (IPv4 ou IPv6)
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }

        return '0.0.0.0';
    }
}
