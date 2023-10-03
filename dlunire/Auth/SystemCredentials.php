<?php

namespace Framework\Auth;

use DLRoute\Server\DLServer;
use DLTools\Config\Credentials;
use DLTools\Config\DLConfig;

final class SystemCredentials {

    use DLConfig;

    /**
     * Instanciade clase
     *
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * Credenciales tomadas a partir de las variables de entorno
     *
     * @var object|null
     */
    private static ?object $credentials = null;

    /**
     * Determina si el sistema está o no en modo producción.
     *
     * @var boolean
     */
    private static bool $is_production = false;

    private function __construct() {
        $this->parse_file();
        self::$credentials = $this->get_environments_as_object();
        
        /**
         * Credenciales de las variables de entorno
         * 
         * @var Credentials $credentials
         */
        $credentials = $this->get_credentials();

        self::$is_production = $credentials->is_production();
    }

    /**
     * Carga las credenciales y sesiones del sistema
     *
     * @return void
     */
    public static function load() {
        self::get_instance();

        ini_set('session.cookie_httponly', 1);
        ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30 * 6);

        session_start();

        self::validate_time();
        self::validate_origin();
    }

    /**
     * Valida el origen de la petición
     *
     * @return void
     */
    private static function validate_origin(): void {
        /**
         * Autenticador
         * 
         * @var AuthBase $auth
         */
        $auth = AuthBase::get_instance();

        /**
         * Datos de la sesión
         * 
         * @var array $session_data
         */
        $session_data = $auth->get_session_value('auth');

        if (!is_array($session_data)) {
            $_SESSION['auth'] = null;
            return;
        }
        
        if (!array_key_exists('user_agent', $session_data)) {
            $_SESSION['auth'] = null;
            return;
        }
        
        if (!array_key_exists('hostname', $session_data)) {
            $_SESSION['auth'] = null;
            return;
        }
        
        if (!array_key_exists('http_host', $session_data)) {
            $_SESSION['auth'] = null;
            return;
        }
        
        if (!array_key_exists('server_software', $session_data)) {
            $_SESSION['auth'] = null;
            return;
        }
        
        if (!array_key_exists('port', $session_data)) {
            $_SESSION['auth'] = null;
            return;
        }
        
        if (!array_key_exists('expire_time', $session_data)) {
            $_SESSION['auth'] = null;
            return;
        }
        
        /**
         * Agente de usuario almacenada en la sesión
         * 
         * @var string $user_agent
         */
        $user_agent = $session_data['user_agent'];
        
        if ($user_agent !== DLServer::get_user_agent()) {
            $_SESSION['auth'] = null;
            return;
        }
        
        /**
         * Nombre de host almacenada en la sesión
         * 
         * @var string $hostname
         */
        $hostname = $session_data['hostname'];
        
        if ($hostname !== DLServer::get_hostname()) {
            $_SESSION['auth'] = null;
            return;
        }

        /**
         * Nombre HTTP HOST almacenada en la sesión
         * 
         * @var string $http_host
         */
        $http_host = $session_data['http_host'];
        
        if ($http_host !== DLServer::get_http_host()) {
            $_SESSION['auth'] = null;
            return;
        }

        /**
         * Software del servidor almacenada en la sesión
         * 
         * @var string $server_software
         */
        $server_software = $session_data['server_software'];
        
        if ($server_software !== DLServer::get_server_software()) {
            $_SESSION['auth'] = null;
            return;
        }
        
        /**
         * Número de puerto almacenada en la variable de sesión
         * 
         * @var integer $port
         */
        $port = (int) $session_data['port'];
        
        if ($port !== DLServer::get_port()) {
            $_SESSION['auth'] = null;
            return;
        }
        
        self::validate_token();
    }

    /**
     * Mediante un token enviado previamente al cliente, valida que el 
     * origen de la petición sea válida.
     * 
     * El token es de un solo uso.
     *
     * @return void
     */
    private static function validate_token(): void {
        
        /**
         * Autenticador
         * 
         * @var AuthBase $auth
         */
        $auth = AuthBase::get_instance();

        /**
         * Token aleatorio de autenticación envaida previamente al cliente
         * 
         * @var string $token
         */
        $token = $_COOKIE['__auth__'] ?? null;

        if (is_null($token)) {
            $_SESSION['auth'] = null;
            // return;
        }

        if ($token !== $auth->get_session_value('__auth__')) {
            $_SESSION['auth'] = null;
            return;

        }

        /**
         * Bytes aleatorios
         * 
         * @var string $bytes
         */
        $bytes = random_bytes(128);

        /**
         * Token nuevo
         * 
         * @var string $new_token
         */
        $new_token = bin2hex($bytes);

        setcookie(
            '__auth__',
            $new_token,
            time() + 60 * 60 * 24 * 30 * 6,
            "/",
            DLServer::get_hostname(),
            self::$is_production,
            true
        );

        $_SESSION['__auth__'] = $new_token;
        
        header("DLUnire: {$new_token}");
        header("Framework: DLUnire");
    }

    /**
     * Devuelve el tiempo de vida de la sesión
     *
     * @return int
     */
    private static function get_lifetime(): int {
        /**
         * Tiempo de vida definida en la variable de sesión
         * 
         * @var integer $lifetime
         */
        $lifetime = 0;

        if (isset(self::$credentials->DL_LIFETIME)) {
            $lifetime = (int) self::$credentials->DL_LIFETIME['value'] ?? 0;
        }

        $lifetime = time() + $lifetime;

        return $lifetime;
    }

    /**
     * Valida el tiempo de expiración y lo actualiza si no se ha vencido
     *
     * @return void
     */
    private static function validate_time(): void {
        /**
         * Tiempo de vida de la sesión del usuario.
         * 
         * @var integer $time;
         */
        $time = self::get_lifetime();

        /**
         * Datos de autenticación del usuario
         * 
         * @var array|null $auth
         */
        $auth = &$_SESSION['auth'] ?? null;

        if (is_null($auth)) {
            return;
        }

        /**
         * Tiempo restante de expiración de la sesión
         * 
         * @var integer|null
         */
        $expire_time = &$auth['expire_time'] ?? null;

        if (is_null($expire_time)) {
            $expire_time = $time;
        }

        /**
         * Tiempo transcurrido desde que se inició la sesión o se actualizó el tiempo de expiración
         * 
         * @var string $elapsed_time
         */
        $elapsed_time = $time - $expire_time;

        /**
         * Tiempo restante de la sesión
         * 
         * @var integer $remaining_time
         */
        $remaining_time = ($time - time()) - $elapsed_time;

        if ($remaining_time > 0) {
            $expire_time = $time;
            return;
        }

        $auth = null;
        setcookie('__auth__', null, time() - 60 * 60 * 30);
    }

    /**
     * Devuelve una instacia de clase siguiente el patrón singleton
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