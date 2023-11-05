<?php

use DLRoute\Routes\ResourceManager;
use DLRoute\Routes\RouteDebugger;
use DLRoute\Server\DLServer;

if (!function_exists('asset')) {

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

        $uri = trim($uri);
        $uri = ltrim($uri, "\/");

        /**
         * URL Base de la aplicación
         * 
         * @var string $url
         */
        $url = DLServer::get_base_url();
        
        $url = rtrim($url, "\/");
        $url = "{$url}/{$uri}";
        
        return trim($url);
    }
}
