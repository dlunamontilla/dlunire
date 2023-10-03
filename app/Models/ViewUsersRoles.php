<?php

namespace DLUnire\Models;

// use DLRoute\Requests\DLOutput;
use Framework\Auth\Roles;

/**
 * Este es un modelo. Debe conectarse a una base de datos para empezar
 * a usarlo.
 * 
 * Los nombres de las clases, generalmente, son los nombres de las tablas, por lo tanto,
 * si la clase se llama `ViewUsersRoles`, la tabla se llamará: `view_users_roles`, pero
 * si le ha agregado un prefijo, por ejemplo, `dl_`, entonces, la tabla
 * se llamará `dl_view_users_roles`
 * 
 * @package DLUnire\Models
 * 
 * @version 1.0.0 (release)
 * @author David E Luna M <davidlunamontilla@gmail.com>
 * @copyright 2023 David E Luna M
 * @license MIT
 */
class ViewUsersRoles extends Roles {

    public function test() {
        // echo DLOutput::get_json($this->valid_session(), true);
    }
}