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
    public static function route(string $uri, array $routes): void
    {
        if (isset($routes[$uri])) {
            require base_path('src/controllers/' . $routes[$uri] . '.php');

            return;
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
