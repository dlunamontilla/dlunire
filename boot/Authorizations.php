<?php

namespace Boot;

use DLRoute\Requests\DLOutput;
use DLRoute\Server\DLServer;

class Authorizations implements AuthorizationsInterface {

    /**
     * Dominios registrados que serán permitidos
     *
     * @var array $domains
     */
    private static array $domains = [];

    public static function register_domain(array $domains): void {
        static::$domains = $domains;
    }

    public static function init(): void {

        /**
         * Devuelve el dominio de origen que hace la petición
         * 
         * @var string
         */
        $origin = self::get_origin();

        foreach (self::$domains as $domain) {
            if (!is_string($domain)) {
                continue;
            }

            $domain = trim($domain);

            if (empty($domain)) {
                continue;
            }
            
            $pattern = "/((http:\/{2}|https:\/{2}){$domain}\b)(:[0-9]{3,4})?/i";

            if (preg_match($pattern, $origin)) {
                self::allow_origin($origin);
                break;
            }
        }
    }

    /**
     * Permite autorizar el dominio origen
     *
     * @return void
     */
    private static function allow_origin(string $origin): void {
        header("Access-Control-Allow-Origin: {$origin}");
        header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS"); 
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");

        if (DLServer::get_method() === "OPTIONS") {
            http_response_code(200);
            exit;
        }        
    }

    /**
     * Devuelve el dominio desde donde se hace petición
     *
     * @return string
     */
    private static function get_origin(): string {
        /**
         * Origen HTTP
         * 
         * @var string $origin
         */
        $origin = $_SERVER['HTTP_ORIGIN'] ?? null;

        return $origin ?? '';
    }
}