<?php

namespace Framework\Config;

use DLRoute\Config\Controller as BaseController;

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
}