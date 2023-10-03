<?php

namespace Framework\Auth;

/**
 * Autenticación por roles de usuario a partir de una relación
 * de muchos a muchos
 * 
 * @package Framework\Auth
 * 
 * @version 1.0.0 (release)
 * @author David E Luna M <davidlunamontilla@gmail.com>
 * @copyright 2023 David E Luna M
 * @license MIT
 */
abstract class Roles extends UserBase {

    /**
     * Nombre de la columna del token
     *
     * @var string $token_column_name
     */
    private string $token_column_name = "token";

    /**
     * Nombre del campo de token de los datos de la sesión
     *
     * @var string
     */
    private string $token_field_name = "token";

    /**
     * Undocumented variable
     *
     * @var string
     */
    private string $user_id = 'user_id';
    /**
     * Establece el nombre de la columna del token
     *
     * @param string $column
     * @return void
     */
    protected function set_token_column(string $column): void {
        $this->token_column_name = trim($column);
    }

    /**
     * Nombre del campo token de los datos de la sesión
     *
     * @param string $field
     * @return void
     */
    protected function set_token_field(string $field): void {
        $this->token_field_name = trim($field);
    }

    /**
     * Devuelve los datos de la consulta
     *
     * @return array
     */
    protected function valid_session(): array {

        /**
         * Autenticador
         * 
         * @var AuthBase $authbase
         */
        $authbase = AuthBase::get_instance();

        /**
         * Datos de la sesión del usuario
         * 
         * @var array $auth
         */
        $auth = $authbase->get_session_value('auth');

        /**
         * Token de autenticación
         * 
         * @var string|null
         */
        $token = null;

        if (array_key_exists($this->token_field_name, $auth)) {
            $token = $auth[$this->token_field_name];
        }

        if (is_null($token)) {
            return [];
        }

        return static::where(
            $this->token_column_name,
            $token
        )->first();
    }
}