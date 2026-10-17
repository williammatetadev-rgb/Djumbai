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
        // camera=self é necessário para digitalização de BI em definições do utilizador
        header('Permissions-Policy: camera=(self), microphone=(), payment=(), usb=()');

        // X-XSS-Protection foi depreciado e removido dos navegadores modernos (não usar)

        // 6. Content Security Policy (CSP) robusta com nonce por pedido
        //    - 'unsafe-inline' REMOVIDO de script-src: scripts inline só executam se tiverem o nonce válido
        //    - style-src mantém 'unsafe-inline' porque os estilos dinâmicos do design system são amplamente usados
        //      (baixo risco de XSS via CSS, e a conversão completa impactaria a identidade visual)
        $nonce = defined('CSP_NONCE') ? CSP_NONCE : '';
        $scriptSrc = "script-src 'self'";
        if ($nonce !== '') {
            $scriptSrc .= " 'nonce-{$nonce}'";
        }

        $csp = [
            "default-src 'self'",
            $scriptSrc,
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: blob: https://*.tile.openstreetmap.org",
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
