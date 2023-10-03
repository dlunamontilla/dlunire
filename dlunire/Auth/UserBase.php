<?php
namespace Framework\Auth;

use DLTools\Auth\DLAuth;
use DLTools\Auth\DLUser;
use Error;

abstract class UserBase extends DLUser {

    /**
     * Nombre del campo usuario del formulario
     *
     * @var string|null
     */
    protected static ?string $username_field = null;

    /**
     * Nombre del campo de contraseña del formulario
     *
     * @var string|null
     */
    protected static ?string $password_field = null;

    /**
     * Columna de usuario de la tabla usuarios
     *
     * @var string|null
     */
    protected static ?string $username_column = null;

    /**
     * Nombre de la columna de contraseñas de la tabla de usuarios
     *
     * @var string|null
     */
    protected static ?string $password_column = null;

    /**
     * Nombre de la columna tokens de la tabla de usuarios. Se recomienda usarla para 
     * facilitar el cierre de sesión en múltiples dispositivos.
     *
     * @var string|null
     */
    protected static ?string $token_column = null;

    /**
     * Captura del cliente HTTP las credenciales del usuario
     *
     * @return void
     * 
     * @throws Error
     */
    public function capture_credentials(): void {
        
        /**
         * Autenticación del usuario
         * 
         * @var DLAuth
         */
        $auth = DLAuth::get_instance();

        /**
         * Nombre de usuario
         * 
         * @var string
         */
        $username = $this->get_required(
            static::$username_field ?? 'username'
        );

        if ($username < 4) {
            throw new Error("El nombre de usuario debe contar, con al menos, 4 caracteres");
        }
        
        $this->set_username(
            $username
        );

        /**
         * Contraseña captura del formulario del cliente HTTP.
         * 
         * @var string
         */
        $password = $this->get_password_valid(
            static::$password_field ?? 'password'
        );

        $this->set_password(
            $password
        );

        $auth->auth($this, [
            "username_field" => static::$username_column ?? 'username',
            "password_field" => static::$password_column ?? 'password',
            "token_field" => static::$token_column ?? 'token'
        ]);
    }
}