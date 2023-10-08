<?php

namespace Framework\Errors;
use DLRoute\Requests\DLOutput;

/**
 * Mensajes de errores personalizados
 * 
 * @package Framework\Errors
 * 
 * @version 1.0.0 (release)
 * @author David E Luna M <davidlunamontilla@gmail.com>
 * @copyright 2023 David E Luna M
 * @license MIT
 * 
 */
class DLErrors {

    /**
     * Permite establecer un mensaje personalizado
     *
     * @param string $message Mensaje personalizado
     * @param integer $code Código de estado
     * @return void
     */
    public static function message(string $message, int $code): void {
        header("Content-Type: application/json; charset=utf-8", true, $code);

        /**
         * Error de referencia inválida.
         * 
         * @var array<string, string|false>
         */
        $errors = [
            "status" => self::status($code),
            self::status($code) ? 'success': 'error' => $message
        ];

        echo DLOutput::get_json($errors, true);

        exit;
    }

    /**
     * Devuelve `true` o `false` en función del código de estado de una petición
     *
     * @param integer $code Código de estado
     * @return boolean
     */
    private static function status(int $code): bool {

        /**
         * Patrón de búsqueda del código de estado OK.
         * 
         * @var boolean $ok_pattern
         */
        $ok_pattern = "/^[2][0-9]{2}$/";

        /**
         * Patrón de búsqueda del código de redirección
         * 
         * @var string  $redirec_pattern
         */
        $redirec_pattern = "/^[3][0-9]{2}$/";

        /**
         * Indicador de código de estado OK.
         * 
         * @var boolean $is_ok
         */
        $is_ok = preg_match($ok_pattern, "{$code}");

        if ($is_ok) {
            return $is_ok;
        }

        /**
         * Indicador de código de redirección
         * 
         * @var boolean $is_redirect
         */
        $is_redirect = preg_match($redirec_pattern, "{$code}");

        if ($is_redirect) {
            return $is_redirect;
        }

        return false;
    }

    /**
     * Devuelve un error HTTP 400
     *
     * @return void
     */
    public static function invalid_ref(): void {
        self::message("Referencia inválida", 400);
    }

    /**
     * Error predeterminado de código inválido de redirección
     *
     * @return void
     */
    public static function redirect_error(): void {
        self::message('Código de redirección inválido', 500);
    }
}