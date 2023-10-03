<?php

namespace DLUnire\Controllers;

use DLUnire\Models\Users;
use Framework\Config\Controller;

final class TestController extends Controller {

    /**
     * Página de Bienvenida
     *
     * @return string
     */
    public function index(): string {
        /**
         * Clave pública del sitio
         * 
         * @var string $site_key
         */
        $site_key = "";

        return view('welcome', [
            "button_type" => 'button--login',
            "label" => 'Realizar una prueba',
            "token" => $this->get_random_token()
        ]);
    }

    /**
     * Pruebas de autenticación de usuario.
     *
     * @return array
     */
    public function auth(): array {
        /**
         * Tabla usuarios.
         * 
         * @var Users
         */
        $users = new Users;

        $users->capture_credentials();

        return [
            "status" => true,
            "success" => "Se ha loggeado correctamente"
        ];
    }

    public function recaptcha(): array {
        validate_ref();
        
        /**
         * Indica si se trata de una persona
         * 
         * @var string $message
         */
        $message = "No es una pesona";

        /**
         * Indica si es una persona
         * 
         * @var boolean $is_human
         */
        $is_human = is_human();

        if ($is_human) {
            $message = "Es una persona";
        }

        return [
            "status" => $is_human,
            "message" => $message
        ];
    }
}