<?php

namespace Boot;

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
            $pattern = "/(http:\/{2}|https:\/{2}){$domain}(:[0-9]{2,4})?/i";

            if (!preg_match($pattern, $origin)) {
                header("HTTP/1.1 403 Forbidden");
                exit();
            }
        }

        self::allow_origin($origin);
    }

    /**
     * Permite autorizar el dominio origen
     *
     * @return void
     */
    private static function allow_origin(string $origin): void {
        header("Access-Control-Allow-Origin: {$origin}");
        header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");
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
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';


        return $origin;
    }
}