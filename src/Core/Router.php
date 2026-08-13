<?php

namespace Core;

class Router
{
    /**
     * Route the request to the appropriate controller.
     *
     * @param string $uri
     * @param array $routes
     * @return mixed
     */
    public static function route(string $uri, array $routes)
    {
        foreach ($routes as $route => $controller) {
            if ($route === $uri) {
                return require base_path('src/controllers/' . $controller . '.php');
            }
        }

        self::abort(404);
    }

    public static function abort(int $code): void
    {
        http_response_code($code);

        require base_path('src/views/errors/' . $code . '.php');

        die;
    }
}
