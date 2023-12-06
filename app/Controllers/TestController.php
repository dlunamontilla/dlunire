<?php

namespace DLUnire\Controllers;

use Framework\Config\Controller;

final class TestController extends Controller {
    
    /**
     * Página de Bienvenida
     *
     * @return string
     */
    public function index(): string {
        return view('welcome', [
            "button_type" => 'button--login',
            "label" => 'Realizar una prueba',
            "token" => $this->get_random_token()
        ]);
    }
}