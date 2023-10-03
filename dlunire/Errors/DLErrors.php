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
     * Devuelve un error HTTP 400
     *
     * @return void
     */
    public static function invalid_ref(): void {
        header("Content-Type: application/json; charset=utf-8", true, 400);

        /**
         * Error de referencia inválida.
         * 
         * @var array<string, string|false>
         */
        $errors = [
            "status" => false,
            "error" => "Referencia inválida"
        ];

        echo DLOutput::get_json($errors);

        exit;
    }
}