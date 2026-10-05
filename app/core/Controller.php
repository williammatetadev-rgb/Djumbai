<?php
/**
 * Djumbai — Classe Base Controller
 * Todos os controladores herdam desta classe
 */

abstract class Controller
{
    /**
     * Renderiza uma View passando os dados necessários.
     *
     * @param string $view   Caminho da view relativo a app/Views/ (ex: 'home/index')
     * @param array  $data   Variáveis a injectar no escopo da view
     * @param string $layout Layout a usar (padrão: 'main')
     */
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        // Extrai o array $data para variáveis locais acessíveis na view
        extract($data);

        // Captura o conteúdo da view para injectá-lo no layout
        ob_start();
        $viewFile = APP_PATH . '/Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            ob_end_clean();
            throw new RuntimeException("View não encontrada: {$view}");
        }

        require $viewFile;
        $content = ob_get_clean();

        // Renderiza o layout com o conteúdo injectado
        $layoutFile = APP_PATH . '/Views/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            throw new RuntimeException("Layout não encontrado: {$layout}");
        }

        require $layoutFile;
    }

    /**
     * Redireciona para um URL relativo ao BASE_URL
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    /**
     * Retorna uma resposta JSON
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Verifica se o utilizador está autenticado.
     * Redireciona para login se não estiver.
     */
    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['flash_warning'] = 'Precisa de iniciar sessão para aceder a esta página.';
            $this->redirect('/login');
        }
    }

    /**
     * Verifica se o utilizador autenticado é administrador.
     */
    protected function requireAdmin(): void
    {
        $this->requireAuth();

        if (($_SESSION['user_tipo'] ?? '') !== 'admin') {
            $_SESSION['flash_error'] = 'Acesso restrito a administradores.';
            $this->redirect('/');
        }
    }
}
