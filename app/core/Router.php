<?php
/**
 * Djumbai — Router (Despachante de Rotas)
 * Suporta rotas GET e POST com parâmetros dinâmicos ({id})
 */

class Router
{
    private array $routes = [];

    /**
     * Regista uma rota GET
     */
    public function get(string $path, string $controller, string $method): void
    {
        $this->addRoute('GET', $path, $controller, $method);
    }

    /**
     * Regista uma rota POST
     */
    public function post(string $path, string $controller, string $method): void
    {
        $this->addRoute('POST', $path, $controller, $method);
    }

    /**
     * Armazena a rota internamente, convertendo {param} em regex
     */
    private function addRoute(string $verb, string $path, string $controller, string $method): void
    {
        // Converte {id} → grupo de captura numérico
        $pattern = preg_replace('/\{([a-z_]+)\}/', '([0-9]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'verb'       => $verb,
            'pattern'    => $pattern,
            'controller' => $controller,
            'method'     => $method,
        ];
    }

    /**
     * Analisa o URI actual e invoca o controlador correcto
     */
    public function dispatch(): void
    {
        $verb = $_SERVER['REQUEST_METHOD'];
        $uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove o prefixo de instalação (/djumbai/public) do URI
        $base = rtrim(BASE_URL, '/');
        if (str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }

        $uri = '/' . trim($uri, '/');
        if ($uri === '//') $uri = '/';

        foreach ($this->routes as $route) {
            if ($route['verb'] !== $verb) continue;

            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // Remove o match completo, fica só os grupos

                // Carrega e instancia o Controlador
                $controllerFile = APP_PATH . '/Controllers/' . $route['controller'] . '.php';

                if (!file_exists($controllerFile)) {
                    $this->abort(500, 'Controlador não encontrado: ' . $route['controller']);
                    return;
                }

                require_once $controllerFile;
                $controllerClass = $route['controller'];
                $controllerObj   = new $controllerClass();
                $actionMethod    = $route['method'];

                if (!method_exists($controllerObj, $actionMethod)) {
                    $this->abort(500, 'Método não encontrado: ' . $actionMethod);
                    return;
                }

                // Chama o método do controlador passando os parâmetros da rota
                call_user_func_array([$controllerObj, $actionMethod], $matches);
                return;
            }
        }

        // Nenhuma rota encontrada → 404
        $this->abort(404);
    }

    /**
     * Exibe uma página de erro HTTP
     */
    private function abort(int $code, string $message = ''): void
    {
        http_response_code($code);

        $labels = [
            404 => 'Página não encontrada',
            500 => 'Erro interno do servidor',
        ];

        $label = $labels[$code] ?? 'Erro';

        echo '<!DOCTYPE html><html lang="pt"><head><meta charset="UTF-8">
              <title>' . $code . ' – ' . $label . ' | Djumbai</title>
              <link rel="stylesheet" href="' . BASE_URL . '/css/style.css">
              </head><body style="display:flex;align-items:center;justify-content:center;min-height:100vh;">
              <div style="text-align:center;padding:40px;">
                <p style="font-size:4rem;font-weight:800;color:var(--brand-terra);line-height:1;">' . $code . '</p>
                <h1 style="font-size:1.5rem;margin-block:12px;">' . $label . '</h1>
                <p style="color:var(--text-secondary);margin-bottom:24px;">' . htmlspecialchars($message) . '</p>
                <a href="' . BASE_URL . '/" style="color:var(--brand-terra);font-weight:700;">← Voltar ao Início</a>
              </div></body></html>';
    }
}
