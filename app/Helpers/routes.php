<?php

use DLRoute\Routes\ResourceManager;
use DLRoute\Routes\RouteDebugger;
use DLRoute\Server\DLServer;

if (!function_exists('route')) {

    /**
     * Devuelve la ruta HTTP de un recurso.
     *
     * @param string $uri URI del recurso.
     * @return string
     */
    function asset(string $uri): string {
        $uri = "public/{$uri}";

        /**
         * URL completa del archivo
         * 
         * @var string
         */
        $url = ResourceManager::asset($uri);

        return trim($url);
    }
}

if (!function_exists('route')) {

    /**
     * Permite establecer la ruta HTTP tomando en cuenta que el proyecto puede estar
     * en cualquier _subdirectorio_, si fuese el caso.
     *
     * @param string $uri
     * @param boolean $extension Opcional. Indica si la ruta a la que apunta lleva extensión de archivo
     * @return string
     */
    function route(string $uri, bool $extension = false) {

        if (!$extension) {
            $uri = RouteDebugger::dot_to_slash($uri);
        }

        $uri = RouteDebugger::trim_slash($uri);
        $uri = RouteDebugger::clear_route($uri);

        /**
         * Ruta HTTP base de ejecución de la aplicación
         * 
         * @var string
         */
        $host = DLServer::get_base_url();

        /**
         * URL real del recurso o ruta.
         * 
         * @var string
         */
        $url = "{$host}/{$uri}";
        $url = RouteDebugger::url_encode($url);

        return trim($url);
    }
}
