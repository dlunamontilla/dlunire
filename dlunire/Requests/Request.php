<?php

namespace Framework\Requests;

use DLRoute\Requests\DLRequest;
use DLTools\Config\DLValues;

/**
 * Prcesa la petición del cliente HTTP
 * 
 * @package Framework\Requests
 * 
 * @author David E Luna M <davidlunamontilla@gmail.com>
 * @copyright 2023 David E Luna M
 * @license MIT
 */
final class Request extends DLRequest {

    use DLValues;

    /**
     * Instancia de clase
     *
     * @var self|null
     */
    private static ?self $instance = null;


    /**
     * Contenido del cliente HTTP
     *
     * @var string
     */
    private string $content = "";

    private function __construct() {

        /**
         * Valores de la petición
         * 
         * @var string|array $values
         */
        $values = $this->get_values();

        if (is_array($values)) {
            self::$values = $values;
        }

        if (is_string($values)) {
            $this->content = $values;
        }
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

    /**
     * Devuelve contenido enviado por un cliente HTTP en cualquier
     * método de envío.
     *
     * @return string
     */
    public function get_content(): string {
        return trim($this->content);
    }
}