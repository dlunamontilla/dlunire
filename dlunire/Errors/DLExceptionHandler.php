<?php

namespace Framework\Errors;

use DLRoute\Requests\DLOutput;
use DLTools\Config\Logs;
use Framework\Config\Environment;
use Error;
use Exception;

/**
 * Captura los errores del sistema y devuelve un error 500 según esté
 * en producción o desarrollo.
 * 
 * Si se encuentra en modo producción devolverá lo siguiente:
 * 
 * ```json
 * {
 *  "status": false,
 *  "error": "Error 500"
 * }
 * ```
 * 
 * Pero si `DL_PRODUCTION` es `false`, entonces, devolverá en formato JSON un error detallado.
 * 
 * @package Framework\Errors
 * 
 * @version 1.0.0 (release)
 * @author David E Luna M <davidlunamontilla@gmail.com>
 * @copyright 2023 David E Luna M
 * @license MIT
 */
final class DLExceptionHandler {

    /**
     * Establece el entorno en modo producción o desarrollo.
     *
     * @param boolean $production
     * @return void
     */
    public static function is_production(): bool {
        /**
         * Instancia de la variable de entorno.
         * 
         * @var Environment
         */
        $environment = Environment::get_instance();

        return $environment->is_production_environment();
    }

    /**
     * Captura todas las excepciones producidas en la aplicación
     *
     * @param Exception $exception
     * @return void
     */
    public static function handle(Exception|Error $exception): void {

        header("Content-Type: application/json; charset=utf-8", true, 500);

        /**
         * Mesaje de error genérico.
         * 
         * @var array $error_default
         */
        $error_default = [
            "status" => false,
            "error" => "Error 500"
        ];

        /**
         * Error detallado.
         * 
         * @var string $error
         */
        $error = DLOutput::get_json([
            "status" => false,
            "error" => $exception->getMessage(),

            "details" => [
                "filename" => $exception->getFile(),
                "line" => $exception->getLine(),
            ],
            "trace" => $exception->getTrace()
        ], true);

        if (self::is_production()) {
            echo DLOutput::get_json($error_default, true);
            Logs::save('exception.json', $error);

            exit;
        }

        echo $error;

        exit;
    }
}
