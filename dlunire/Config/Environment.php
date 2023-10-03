<?php

namespace Framework\Config;

use DLTools\Config\Credentials;
use DLTools\Config\DLConfig;
use Framework\Auth\AuthBase;

/**
 * Permite saber si estamos en un entorno de producción o de desarrollo
 * 
 * @package DLUnire\Config;
 * 
 * @version 0.0.1 (release)
 * @author David E Luna M <davidlunamontilla@gmail.com>
 * @copyright 2023 David E Luna M
 * @license MIT
 */
class Environment {

    use DLConfig;

    /**
     * Instance de clase
     *
     * @var self|null
     */
    private static ?self $instance = null;

    private function __construct() {
        $this->parse_file();
    }

    /**
     * Devuelve las credenciales de las variables de entorno.
     *
     * @return bool
     */
    public function is_production_environment(): bool {
        /**
         * Devuelve las credenciales establecidas en las variables de entorno.
         * 
         * @var Credentials
         */
        $credentials = $this->get_credentials();

        return $credentials->is_production();
    }

    /**
     * Devuelve variables de entorno como objetos
     *
     * @return object
     */
    public function get_vars_as_object(): object {
        return $this->get_environments_as_object();
    }

    /**
     * Devuelve las credenciales críticas de la aplicacación
     *
     * @return Credentials
     */
    public function return_credentials(): Credentials {
        return $this->get_credentials();
    }

    /**
     * Devuelve token de prevención de ataques CSRF
     *
     * @return string
     */
    public function get_token_csrf(): string {
        $auth = AuthBase::get_instance();
        return $auth->get_token();
    }

    /**
     * Si la clave del sitio está definidas en la variables de entorno, las devolverá.
     * 
     * Para definirlas, debe ir a `.env.type` y agregar la siguiente línea:
     * 
     * ```envtype
     * G_SITE_KEY: string = "<tu-clave-del-sitio>"
     * ```
     *
     * @return string
     */
    public function get_sitekey(): string {
        /**
         * Variables de entorno
         * 
         * @var object $environments
         */
        $environments = $this->get_environments_as_object();

        /**
         * Clave del sitio
         * 
         * @var string|null $sitekey
         */
        $sitekey = null;

        if (!isset($environments->G_SITE_KEY)) {
            return "";
        }

        if (!array_key_exists('value', $environments->G_SITE_KEY)) {
            return "";
        }
        
        $sitekey = $environments->G_SITE_KEY['value'];

        return trim($sitekey);
    }

    /**
     * Devuelve una instancia de clase
     *
     * @return self
     */
    public static function get_instance(): self {

        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}