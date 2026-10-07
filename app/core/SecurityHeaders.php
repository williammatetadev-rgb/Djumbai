<?php
/**
 * Djumbai — Middleware de Cabeçalhos de Segurança (Security Headers)
 * Blindagem contra XSS, Clickjacking, MIME-Sniffing e vazamento de referrer
 */

class SecurityHeaders
{
    /**
     * Aplica os cabeçalhos de segurança essenciais a todas as respostas HTTP
     */
    public static function apply(): void
    {
        if (headers_sent()) {
            return;
        }

        // 1. Proteção contra Clickjacking (Frame Embedding)
        header('X-Frame-Options: SAMEORIGIN');

        // 2. Impede que navegadores adivinhem o MIME-type (MIME Sniffing)
        header('X-Content-Type-Options: nosniff');

        // 3. Controle rigoroso de envio de Referrer
        header('Referrer-Policy: strict-origin-when-cross-origin');

        // 4. Desativa APIs sensíveis do navegador não utilizadas
        header('Permissions-Policy: camera=(), microphone=(), payment=(), usb=()');

        // 5. Proteção XSS legada para navegadores antigos
        header('X-XSS-Protection: 1; mode=block');

        // 6. Content Security Policy (CSP) robusta e compatível com SVGs/CSS locais
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://unpkg.com https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://unpkg.com https://cdn.jsdelivr.net",
            "img-src 'self' data: blob: https://*.tile.openstreetmap.org https://unpkg.com https://cdn.jsdelivr.net",
            "font-src 'self'",
            "connect-src 'self' https://*.tile.openstreetmap.org",
            "media-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ];
        header('Content-Security-Policy: ' . implode('; ', $csp));

        // 7. HSTS se a conexão estiver rodando em HTTPS
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }

        // 8. Remove assinatura do PHP
        header_remove('X-Powered-By');
    }
}
