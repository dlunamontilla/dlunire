<?php

use DLRoute\Routes\RouteDebugger;
use DLRoute\Server\DLServer;
use DLTools\Compilers\DLView;
use Dompdf\Dompdf;
use Framework\Errors\DLErrors;

if (!function_exists('view')) {
    /**
     * Cargar una vista a partir de una plantilla.
     *
     * @param string $view Ruta a la vista (nombre de archivo o ruta completa)
     * @param array $options Opcional. Variables que se pasan a la vista
     * @return string El contenido de la vista renderizada como una cadena
     */
    function view(string $view, array $options = []): string {
        ob_start();
        DLView::load($view, $options);
        $viewContent = ob_get_clean();

        return trim($viewContent);
    }
}

if (!function_exists('view_pdf')) {

    /**
     * ## ¿Qué hace?
     * 
     * Transforma código HTML a formato PDF, a la vez que permite introducir variables a las plantillas 
     * y cambiar el formato del documento PDF generado.
     * 
     * ### Variables (Opcional)
     * 
     * Para ingresar variables, debe pasar como segundo argumento un array asociativo, por ejemplo:
     * 
     * ```php
     * <?php
     * ...
     * 
     * view_pdf('ruta.vista', [
     *  "variable1" => "Valor de la variable 1",
     *  "variable2" => "Valor de la variable 2"
     * ]);
     * ```
     * 
     * ### Configuración de salida (Opcional)
     * 
     * Para configurar la salida de un documento PDF debe pasar un array asociativo como tercer argumento, 
     * por ejemplo:
     * 
     * ```php
     * <?php
     * ...
     * 
     * view_pdf('ruta.vista', $options, [
     *  "filename" => 'document.pdf',
     *  "compress" => 1,
     *  "attachment" => 0,
     *  "paper_size" => 'a4',
     *  "orientation" => 'portrait',
     *  "encoding" => 'utf-8'
     * ]);
     * ```
     * 
     * #### Explicación de las opciones
     * 
     * Lo que sigue es una breve descripción de lo anteriormente expuesto:
     * 
     * - **`filename`:** Opcional. Es el nombre del documento a descargar. El nombre por defecto es `document.pdf`.
     * 
     * - **`compress`:** Es la compresión de flujo de datos del documento PDF generado. Cuando vale `1` (predeterminado)
     * **Dompdf** aplica la compresión de contenido, lo cual, puede ayudar reducir el tamaño del archivo, pero al mismo
     * tiempo, aumento el uso de la CPU. Si vale `0`, entonces, no se comprimirá.
     * 
     * - **`attachment`: Establece el encabezado HTTP `Content-Disposition` en `attachment` cuando vale `1`. Esto hará que
     * el navegador ofresca el contenido como un archivo descargable con el nombre definido en `filename` o uno por defecto.
     * El valor por defecto es `0`, por lo que se cargará directarmente en el navegador (los que lo soporten) el documento generado.
     * 
     * - **`paper_size`:** Indica el tamaño de la hoja del documento PDF. Puede consultar [todos los tamaños en su repositorio](https://github.com/dompdf/dompdf/blob/master/src/Adapter/CPDF.php "Tamaños admitidos").
     * - **`encoding`:** Establece la codificación de caracteres. El valor por defecto es `utf-8`.
     * 
     * @param string $view Vista de plantilla a ser cargada
     * @param array|null $options Opcional. Variables disponibles en la plantilla
     * @param array $configs Opcional. Opciones de configuración de un documento PDF.
     * @return string
     */
    function view_pdf(string $view, ?array $options = null, array $config = []): string {
        /**
         * Nombre de archivo PDF.
         * 
         * @var string
         */
        $filename = "document.pdf";

        if (array_key_exists('filename', $config)) {
            $filename = $config['filename'];
        }

        /**
         * Aplicar compresión de flujo de contenido. Si vale 0, no se aplicará la compresión de flujo
         * de contenido.
         * 
         * @var integer
         */
        $compress = 1;

        if (array_key_exists('compress', $config)) {
            $compress = $config['compress'];
        }

        /**
         * Determina si se debe establecer el encabezado HTTP `Content-Disposition` en `attachement`. Cuando
         * vale `1`, entonces, se establece en `attachment`, provocando que el navegador Web ofrezca un PDF
         * descargable.
         * 
         * Si vale `0`, entonces, el PDF se mostrará directamente en los navegadores que lo soporten.
         */
        $attachment = 0;

        if (array_key_exists('attachment', $config)) {
            $attachment = $config['attachement'];
        }

        /**
         * Tamaño del lienzo que representará la hoja en el documento PDF.
         * 
         * @var string
         */
        $paper_size = 'a4';

        if (array_key_exists('paper_size', $config)) {
            $paper_size = $config['paper_size'];
        }

        /**
         * Establece la orientación de la hoja.
         * 
         * @var string
         */
        $orientation = "portrait";

        if (array_key_exists('orientation', $config)) {
            $orientation = $config['orienation'];
        }

        /**
         * Establece la codificación de caracteres para el documento PDF.
         * 
         * @var string
         */
        $encoding = "utf-8";

        if (array_key_exists('encoding', $config)) {
            $encoding = $config['encoding'];
        }

        /**
         * Vista cargada a partir de una plantilla.
         * 
         * @var string
         */
        $view = view($view, $options ?? []);    

        $pdf = new Dompdf();

        $pdf->loadHtml($view, $encoding);
        $pdf->setPaper($paper_size, $orientation);
        $pdf->render();

        return (string) $pdf->stream($filename, [
            "compress" => $compress,
            "Attachment" => $attachment
        ]);
    }
}

if (!function_exists('redirect')) {

    /**
     * Redirige a un usuario a una nueva `URL`
     *
     * @param string $uri URI a redirigir
     * @param integer $code Código de redirección.
     * @return never
     */
    function redirect(string $uri, int $code = 302) {
        /**
         * Patrón de búsqueda del código de redirección.
         * 
         * @var string $patern
         */
        $pattern = "/^[3][0-9]{2}$/";

        /**
         * Indica si el código de redirección es válido o no.
         * 
         * @var boolean $is_valid
         */
        $is_valid = preg_match($pattern, "{$code}");

        if (!$is_valid) {
            DLErrors::redirect_error();
        }

        $uri = RouteDebugger::trim_slash($uri);
        $uri = RouteDebugger::dot_to_slash($uri);
        $uri = RouteDebugger::clear_route($uri);

        /**
         * Ruta HTTP base.
         * 
         * @var string $http_host
         */
        $http_host = DLServer::get_http_host();
        $http_host = rtrim($http_host, "\/");

        /**
         * URL completa
         * 
         * @var string $url
         */
        $url = "{$http_host}/{$uri}";
        $url = RouteDebugger::url_encode($url);
        
        header("Location: {$url}", true, $code);
        exit;
    }
}