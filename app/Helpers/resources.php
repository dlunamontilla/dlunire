<?php

use DLRoute\Routes\ResourceManager;

if (!function_exists('js')) {

    /**
     * Incorpora código JavaScript, por defecto, entre las etiquetas `<script...>...</script>`, a menos, que se indique lo contrario en el segundo parámetro.
     *
     * ### Claves del segundo parámetro
     * 
     * - **`external`:** Para indicar con true si queremos que una salida para uso externo o si el código JavaScript se incorpora directamente entre las etiquetas `<script...>...</script>` con el valor false (valor por defecto).
     * - **`behavior_attributes`:** Permite cualquiera de los siguientes valores: defer o async. El primero es para indicar si deseamos que nuestro script cargue diferido o asíncrono con la segunda.
     * - **`type`:** Permite establer si el script será tratado como un módulo o no, por ejemplo, `type="module"` o `type="text/javascript"`.
     * - **`token`:** Permite establecer el token de seguridad, por lo tanto, haría que las etiquetas `<script...>...</script>` tengan el siguiente atributo con su valor:
     * `nonce="69c3b8278585e68071b5ce7035ea52a80d76408be7f04949ee1b0fd7b5927898"`
     * 
     * > **Importante:** si external vale `true`, es decir, `external => true`, las demás claves quedarán sin efecto.
     * 
     * @param string $filename Archivo a ser accedido.
     * @param array $options Es un array asociativo que solo permiten las siguientes claves.
     * @return string
     */
    function js(string $filename, array $options = []): string {

        /**
         * Código fuente capturado
         * 
         * @var string
         */
        $source = ResourceManager::js($filename, $options);

        return trim($source);
    }
}

if (!function_exists('js_external')) {

    /**
     * Devuelve el código fuente del archivo directamente.
     *
     * @param string $filename Archivo a ser leído
     * @return string
     */
    function js_external(string $filename): string {
        /**
         * Código fuente del archivo
         * 
         * @var string
         */
        $source = ResourceManager::js($filename, [
            "external" => true
        ]);

        return trim($source);
    }
}

if (!function_exists('image')) {

    /**
     * Procesa las imágenes. Este método permite definir si la imagen se cargará directamente como archivo binario o base64 en el código HTML.
     *
     *   Ejemplo de uso:
     *
     * ```
     * $output = ResourceManager::image('ruta/a/la/imagen.jpg', [
     *    "title" => "Título de la imagen",
     *    "html" => true
     * ]);
     * ```
     *
     * @param string $uri
     * @return string
     */
    function image(string $uri, array|object|null $config = null): string {

        /**
         * Imagen devuelta
         * 
         * @var string $images
         */
        $images = ResourceManager::image($uri, $config);

        return $images;
    }
}