<?php

namespace Framework\Config;

use DLRoute\Config\Controller as BaseController;
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
    }

    /**
     * Devuelve tokens aleatorio para evitar ejecución no atorizada de scripts
     *
     * @return string
     */
    public function get_random_token(): string {
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
    public function get_content(): string {
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
    public function get_values(): array {
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
    public function get_email(string $field): string {
        return $this->http->get_email($field);
    }

    public function get_uuid(string $field): string {
        return $this->http->get_uuid($field);
    }

    public function get_password(string $field): string {
        return $this->http->get_password_valid($field);
    }

    public function get_float(string $field): float {
        return $this->http->get_float($field);
    }

    public function get_integer(string $field): int {
        return $this->http->get_integer($field);
    }

    public function get_numeric(string $field): int|float {
        return $this->http->get_numeric($field);
    }

    public function get_string(string $field): string {
        return $this->http->get_string($field);
    }

    public function get_input(string $field): string {
        return $this->http->get_input($field);
    }

    public function get_required(string $field): string {
        return $this->http->get_required($field);
    }

    public function get_boolean(string $field): string {
        return $this->http->get_boolean($field);
    }
}