<?php

namespace Framework\Config;

use Exception;

/**
 * Sistema de tokens
 */
trait Token {

    /**
     * Cifra una contraseña utilizando el algoritmo `Argon2id` con opciones personalizadas.
     *
     * Esta función toma una contraseña en forma de cadena de texto y utiliza el algoritmo `Argon2id`
     * para generar un hash seguro.
     *
     * @param string $password La contraseña que se desea cifrar.
     *
     * @return string El hash de la contraseña cifrada.
     *
     * @throws Exception Si ocurre un error durante el cifrado de la contraseña.
     */
    protected function get_password_hash(string $password): string {
        $config = [
            "memory_cost" => 131072,
            "time_cost" => 4,
            "threads" => 2
        ];

        /**
         * Hash de la contraseña.
         * 
         * @var string
         */
        $hash = password_hash($password, PASSWORD_ARGON2ID, $config);

        if ($hash === false) {
            throw new Exception("Error al cifrar la contraseña.");
        }

        return $hash;
    }

    /**
     * Devuelve el token aleatorio de la aplicación.
     *
     * @return string
     */
    protected function get_token_app(): string {
        /**
         * bytes aleatorio.
         * 
         * @var string
         */
        $bytes = random_bytes(100);

        /**
         * Token en formato hexadecimal
         * 
         * @var string
         */
        $token = bin2hex($bytes);

        return $token;
    }

    /**
     * Devuelve un token de referencia cruzada (CSRF) para asegurarse
     * que el origen de la petición sea legítima.
     *
     * @return string
     */
    protected function get_csrf_token(): string {
        /**
         * Token de referencia cruzada.
         * 
         * @var string
         */
        $csrf_token = null;

        if (array_key_exists('csrf_token', $_SESSION)) {
            $csrf_token = $_SESSION['csrf_token'];
        }

        if (is_null($csrf_token)) {
            $csrf_token = $this->get_token_app();
            $_SESSION['csrf_token'] = $csrf_token;
        }

        return $csrf_token;
    }

    /**
     * Devuelve un Identificador Único Universal (UUID).
     *
     * @return string
     */
    protected function get_app_uuid(): string {
        /**
         * Bytes aleatorios
         * 
         * @var string
         */
        $data = random_bytes(16);

        # Establece los bits de versión y variante
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Versión 4
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Variante RFC 4122

        /**
         * Identificador UUID
         * 
         * @var string
         */
        $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));

        return $uuid;
    }
}
