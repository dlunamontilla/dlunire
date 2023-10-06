<?php

namespace Boot;

/**
 * Interfaz para gestionar autorizaciones de acceso a las peticiones.
 * 
 * @package Boot
 * 
 * @version v0.0.1 (release)
 * @author David E Luna M <davidlunamontilla@gmail.com>
 * @copyright 2023 David E Luna M
 * @license MIT
 */
interface AuthorizationsInterface {

    /**
     * Registra los dominios que tendrán acceso a las peticiones.
     *
     * Esta función permite configurar los dominios desde los cuales se aceptarán
     * peticiones. Debería implementarse para controlar el acceso a recursos
     * específicos en la aplicación.
     * 
     * @param array $domains Lista de dominios permitidos.
     * @return void
     */
    public static function register_domain(array $domains): void;

    /**
     * Inicializa la gestión de accesos.
     *
     * Esta función se utiliza para realizar cualquier inicialización necesaria
     * relacionada con la gestión de autorizaciones. Puede incluir la configuración
     * de reglas de acceso, el establecimiento de permisos, entre otras tareas.
     *
     * @return void
     */
    public static function init(): void;
}
