<?php

namespace Framework\Config;

use DLRoute\Config\Controller as BaseController;
use DLRoute\Server\DLServer;
use Framework\Errors\DLErrors;
use Framework\Requests\Request;

/**
 * Controlador base
 * 
 * @package DLUnire\Config
 * 
 * @version 0.0.1 (release)
 * @author David E Luna M <davidlunamontilla@gmail.com>
 * @copyright 2023 David E Luna M
 * @license MIT
 */
abstract class Controller extends BaseController {

    use Token;

    /**
     * Valores de la petición en un array asociativo
     *
     * @var array $values
     */
    private array $values = [];

    /**
     * Contenido del cliente HTTP
     *
     * @var string $content
     */
    private string $content = "";

    /**
     * Peticiones del cliente HTTP
     *
     * @var Request|null
     */
    private ?Request $http = null;

    public function __construct() {
        parent::__construct();
        $this->http = Request::get_instance();
        $this->send_csrf_token();
    }

    /**
     * Devuelve tokens aleatorio para evitar ejecución no atorizada de scripts
     *
     * @return string
     */
    protected function get_random_token(): string {
        /**
         * Bytes aleatorio
         * 
         * @var string $bytes
         */
        $bytes = random_bytes(36);

        /**
         * Token aleatorios
         * 
         * @var string $token
         */
        $token = bin2hex($bytes);

        return $token;
    }

    /**
     * Devuelve contenido en formato de texto de un cliente HTTP
     *
     * @return string
     */
    protected function get_content(): string {
        /**
         * Contenido de un cliente HTTP
         * 
         * @var string|array
         */
        $content = $this->request->get_values();

        return is_string($content) ? trim($content) : "";
    }

    /**
     * Devuelve en un array asociativo los valores de la petición
     *
     * @return array
     */
    protected function get_values(): array {
        /**
         * Contenido de un cliente HTTP
         * 
         * @var string|array
         */
        $content = $this->request->get_values();

        return is_array($content) ? $content : [];
    }

    /**
     * Devuelve una dirección de correo. Si el correo enviado por el cliente
     * HTTP es inválido devolverá un error.
     *
     * @param string $field
     * @return string
     */
    protected function get_email(string $field): string {
        return $this->http->get_email($field);
    }

    /**
     * Obtiene un UUID (Identificador Único Universal) para el campo especificado.
     *
     * @param string $field El nombre del campo para el que se desea obtener el UUID.
     * @return string El UUID generado para el campo.
     */
    protected function get_uuid(string $field): string {
        return $this->http->get_uuid($field);
    }


    /**
     * Obtiene una contraseña válida para el campo especificado.
     *
     * @param string $field El nombre del campo para el que se desea obtener la contraseña.
     * @return string La contraseña válida generada para el campo.
     */
    protected function get_password(string $field): string {
        return $this->http->get_password_valid($field);
    }


    /**
     * Obtiene un valor flotante para el campo especificado.
     *
     * @param string $field El nombre del campo para el que se desea obtener el valor flotante.
     * @return float El valor flotante obtenido para el campo.
     */
    protected function get_float(string $field): float {
        return $this->http->get_float($field);
    }


    /**
     * Obtiene un valor entero para el campo especificado.
     *
     * @param string $field El nombre del campo para el que se desea obtener el valor entero.
     * @return int El valor entero obtenido para el campo.
     */
    protected function get_integer(string $field): int {
        return $this->http->get_integer($field);
    }

    /**
     * Obtiene un valor numérico para el campo especificado.
     *
     * @param string $field El nombre del campo para el que se desea obtener el valor numérico.
     * @return float | int El valor numérico obtenido para el campo, que puede ser un entero o un número de punto flotante.
     */
    protected function get_numeric(string $field): float|int {
        return $this->http->get_numeric($field);
    }

    /**
     * Obtiene una cadena de texto para el campo especificado.
     *
     * @param string $field El nombre del campo para el que se desea obtener la cadena de texto.
     * @return string La cadena de texto obtenida para el campo.
     */
    protected function get_string(string $field): string {
        return $this->http->get_string($field);
    }

    /**
     * Obtiene una entrada de usuario para el campo especificado.
     *
     * @param string $field El nombre del campo para el que se desea obtener la entrada de usuario.
     * @return string La entrada de usuario obtenida para el campo.
     */
    protected function get_input(string $field): string {
        return $this->http->get_input($field);
    }

    /**
     * Obtiene un valor requerido para el campo especificado.
     *
     * @param string $field El nombre del campo para el que se desea obtener el valor requerido.
     * @return string El valor requerido obtenido para el campo.
     */
    protected function get_required(string $field): string {
        return $this->http->get_required($field);
    }

    /**
     * Obtiene un valor booleano para el campo especificado.
     *
     * @param string $field El nombre del campo para el que se desea obtener el valor booleano.
     * @return string El valor booleano obtenido para el campo.
     */
    protected function get_boolean(string $field): string {
        return $this->http->get_boolean($field);
    }

    /**
     * Método para enviar un token CSRF (Cross-Site Request Forgery) como encabezado HTTP.
     * Este token se utiliza para proteger contra ataques de referencia cruzada.
     *
     * @return void
     */
    private function send_csrf_token(): void {
        /**
         * Token CSRF
         *
         * Este token se genera para proteger las solicitudes contra ataques de referencia cruzada.
         *
         * @var string $token
         */
        $token = $this->get_csrf_token();
        setcookie('__csrf', $token, time() + 3600 * 24 * 6, "/", DLServer::get_hostname(), false, true);
    }

    /**
     * Validar un token CSRF en una solicitud HTTP.
     *
     * Esta función verifica si el token CSRF enviado por el cliente coincide con el token CSRF
     * almacenado en la sesión actual. Si no coinciden, se genera un mensaje de error y se
     * devuelve una respuesta HTTP 403 Forbidden para indicar que la solicitud ha sido rechazada
     * por razones de seguridad.
     *
     * @return void
     */
    protected function validate_csrf_token(): void {
        /**
         * Mensaje de error en caso de token CSRF inválido.
         *
         * @var string $error
         */
        $error = "Token CSRF inválido. La solicitud ha sido rechazada por motivos de seguridad.";

        /**
         * Token enviado por el cliente HTTP.
         *
         * @var string $client_token
         */
        $client_token = $_COOKIE['__csrf'] ?? null;

        /**
         * Token CSRF almacenado en la sesión actual.
         *
         * @var string $token
         */
        $token = $this->get_csrf_token();

        if (is_null($client_token)) {
            DLErrors::message($error, 403);
        }

        if ($token !== $client_token) {
            DLErrors::message($error, 403);
        }

        $_SESSION['csrf_token'] = null;
    }

}