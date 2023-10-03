<?php
use DLRoute\Requests\DLRequest;
use DLTools\Auth\DLRecaptcha;
use Framework\Auth\AuthBase;
use Framework\Config\Environment;
use Framework\Errors\DLErrors;

if (!function_exists('get_token')) {

    /**
     * Devuelve token CSRF
     *
     * @return string
     */
    function get_token(): string {
        /**
         * Variables de entorno
         * 
         * @var Environment $environment
         */
        $environment = Environment::get_instance();

        /**
         * Token de prevención de ataques CSRF.
         * 
         * @var string $token
         */
        $token = $environment->get_token_csrf();

        return $token;
    }
}

if (!function_exists('get_sitekey')) {

    /**
     * Devuelve la clave del sitio si está definida en la variable de entorno
     *
     * @return string
     */
    function get_sitekey(): string {
        /**
         * Variables de entorno
         * 
         * @var Environment $environment
         */
        $environment = Environment::get_instance();

        /**
         * Api Key del sitio Web
         * 
         * @var string $sitekey
         */
        $sitekey = $environment->get_sitekey();

        return trim($sitekey);
    }
}

if (!function_exists('is_human')) {

    /**
     * Verifica si el cliente HTTP es una persona
     *
     * @return boolean
     */
    function is_human(): bool {
        $recaptcha = DLRecaptcha::get_instance();
        return $recaptcha->post();
    }
}

if (!function_exists('validate_ref')) {

    function validate_ref(string $token_field = 'csrf-token') {
        /**
         * Peticiones del cliente HTTP
         * 
         * @var DLRequest $request
         */
        $request = DLRequest::get_instance();

        /**
         * Campos de la petición
         */
        $values = $request->get_values();

        /**
         * Token enviado por el usuario
         * 
         * @var string|null $token
         */
        $token = $values[$token_field] ?? null;

        if (is_null($token)) {
            DLErrors::invalid_ref();
        }

        if ($token !== get_token()) {
            DLErrors::invalid_ref();
        }

        $_SESSION['csrf-token'] = trim($token);
    }
}