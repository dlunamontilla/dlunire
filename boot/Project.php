<?php

namespace Boot;

use DLRoute\Requests\DLRoute;
use DLRoute\Server\DLServer;
use DLUnire\Interfaces\ProjectInterface;
use Framework\Auth\SystemCredentials;

/**
 * Corre todo el proyecto
 * 
 * @package DLUnire
 * 
 * @version 0.0.0
 * @author David E Luna M <davidlunamontilla@gmail.com>
 * @copyright 2023 David E Luna M
 * @license MIT
 * 
 * Para uso personal
 */
class Project implements ProjectInterface {

    /**
     * Incluye archivos PHP en función del directorio seleccionado
     *
     * @param string $folder
     * @return void
     */
    private static function includes(string $folder = "app/Helpers"): void {
        $root = DLServer::get_document_root();
        $dir = "{$root}/{$folder}";

        
        if (!file_exists($dir) || !is_dir($dir)) {
            return;
        }

        /**
         * Patrón de búsqueda de archivos PHP.
         * 
         * @var string
         */
        $search_files = self::clear_route($dir);

        /**
         * Array de archivos
         * 
         * @var array<string>
         */
        $filenames = glob($search_files);

        foreach ($filenames as $filename) {
            $filename = trim($filename);
            include $filename;
        }
    }

    /**
     * Depura las rutas de directorio.
     *
     * @param string $route
     * @return string
     */
    private static function clear_route(string $route): string {
        $route = trim($route);
        $route = preg_replace("/\/+|\.+/", DIRECTORY_SEPARATOR, $route);
        $route = preg_replace("/\/+$/", "", $route);

        return "{$route}/*.php";
    }

    public static function run(): void {
        Authorizations::register_domain([
            "localhost"
        ]);

        Authorizations::init();

        SystemCredentials::load();
        
        self::includes();
        self::includes('app/Constants');
        self::includes("routes");

        DLRoute::execute();
    }
}