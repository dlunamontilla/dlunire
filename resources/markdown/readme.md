## Bienvenido a este Mini Framework PHP

---

> **Importante:** la documentación aún no se ha terminado. En la medida de lo posible, iré actualizando la documentación de cómo funciona esta herramienta.
> De hecho, cuenta con un motor de plantillas similar a Laravel y cuenta con directivas.
>
> Puede [obtener el Framework aqui](https://github.com/dlunamontilla/dlunire "Framework DLUnire") o escribiendo el siguiente comando en la terminal:
>
> ```bash
> composer create-project dlunamontilla/dlunire tu-app
> ```
>
> Antes de usar esta herramienta, debes instalar una extensión para **Visual Studio Code** para obtener el resaltador de sintaxis de las variables de entorno con tipos estáticos.
> El nombre del archivo para variables de entorno que debe crear es `.env.type`.
>
> ### Instalación del resaltador de sintaxis de las Variables de Entorno
>
> Vaya al instalador de extensiones de **Visual Studio Code** y busque `DL Typed Environment` y si no aparece, [visite este enlace para descargar la extensión](https://marketplace.visualstudio.com/items?itemName=dlunamontilla.envtype "Resaltador de sintaxis").
>
> ### Instalación de SASS
>
> Para escribir código `SCSS` debe instalar SASS escribiendo el siguiente comando:
>
> ```bash
> npm -g install sass
> ```

### Directorios | Estructura

```none
Raíz /
    |- /public/ → Directorio de ejecución de la aplicación. Ten en cuenta que es el directorio donde corre el proyecto.

    |- /app
        |- /Models → Directorio para crear modelos.
        |- /Auth → Para definir el sistema de autenticación.
        |- /Constants → Definir constantes globales. Requiere el uso de `require` ni `include`.
        |- /Controllers → Directorio de controladores.
        |- /Helpers → Creación de funciones globales personalizadas.
        |- /Interfaces → Definición de interfaces.
        
    |- /routes → Directorio de definición de rutas. No se requiere utilizar `include` ni `require`
    |- /resoures → Directorio para definir las plantillas con sintaxis similares a Laravel.
    |- /tests → Directorio para las pruebas automatizadas.
    |- /dlunire → Funcionalidad propias de `DLUnire`
```

### Instalación

Para crear una aplicación con esta herramienta, debe escribir el siguiente comando:

```bash
composer create-project dlunamontilla/dlunire tu-app
```

Y luego, del paso anterior, solo tienes que correrla:

```bash
composer run dev
```

### Directorio de ejecución de la aplicación

El directorio de ejecutución de la aplicación es `public/`, pero puedes cambiar su nombre si así lo desea, pero asegúrate que siempre apunte al directorio que hayas utilizado como directorio de ejecución de la aplicación.

Si cambias el nombre, también debes cambiar:

```json
"scripts": {
    "dev": "php -S localhost:3000 -t public/"
}
```

Por esta:

```json
"scripts": {
    "dev": "php -S localhost:3000 -t nuevo-directorio-de-ejecucion/"
}
```

Sin embargo, siempre se recomienda dejarle el mismo nombre al directorio.

### Métodos HTTP soportados, por ahora

Los métodos HTTP que soporta este _mini-framework_ son: `GET`, `POST`, `PUT`, `PATCH` y `DELETE`

### Definición de rutas

Tienes tres formas de definir las rutas con esta herramienta, pero antes, debes conocer dónde se definen las rutas.

Las rutas las puedes definir en cualquier en cualquier archivo ubicado en el directorio `/routes` que se encuentra ubicado en el directorio raíz de la aplicación.

Aquí tenemos tres formas de definirnas

1. Pasando como segundo argumento una cadena de texto:

   ```php
   use DLRoute\Requests\DLRoute;

   DLRoute::get('/', "DLUnire\\Controllers\\TestController@method");
   ```

   De esa manera, se estará apuntando al controlador que se encuentra en la ruta `app/Controllers/TestController.php`, a la vez, que se ejecuta el método `method`.

2. Pasando el segundo argumento como un `callback`:

   ```php
   use DLRoute\Requests\DLRoute;

   DLRoute::get("/", function() {
      return view('vista');
   });
   ```

   También puede pasar parámetros en la ruta:

   ```php
   use DLRoute\Requests\DLRoute;

   DLRoute::get("/user/{id}", function(object $param) {
      return view('vista', [
        "param" => $param
      ]);
   });
   ```

3. La tercera forma, es pasando un `array` como segundo argumento:

   ```php
   use DLRoute\Requests\DLRoute;

   DLRoute::get("/user/{id}", [TestController::class, 'method'])
   ```

Esta forma, aparte de ser más elegante, más potente. Tanto en la primera y tercera manera de definir las rutas, se pueden pasar parámetros y capturar de forma automática peticiones del usuario al momento de definir el controlador.

También se pueden subir archivos de forma simple con filtrado por tipos incluidos.

### Definición de controladores

Vamos a ver cómo definir un controlador, para ello debemos escribir las siguientes líneas:

```php
namespace DLUnire\Controllers;

use Framework\Config\Controller;

final class TestController extends Controller {

    public function method(object $params): string {

        return view('vista', [
            "variable1" => "Valor de la variable 1",
            "variable2" => "Valor de la variable 2"
        ]);
    }
}
```

Tome en cuenta, que el parámetro `$param` es opcional. No está obligado a definirlo.

Cada controlador que defina debe estar ubicado, preferiblemente en el directorio `/app/Controllers`. No importa, si los controladores que defina en esa ruta estén en subdirectorios.

Los parámetros definidos en `$param` son los que se han definido en una ruta similar a esta:

```php
/ruta/{param1}/{param2}{paramN}

# Estos parámetros se convierten a esto de forma automática:
$param->param1;
$param->param2;
$param->paramN;
```

### Captura de valores desde el controlador

Puede capturar valores desde el controlador de la petición, sin tener que escribir `$_GET`, `$_POST` o `$_REQUEST` de esta manera:

```php
namespace DLUnire\Controllers;
use Framework\Config\Controller;

final class TestController extends Controller {

    public function method(object $param): string {
        /*
         * Valores de la petición
         *
         * @var array $values
         */
        $values = $this->get_values();

        return view('vista');
    }
}
```

El contenido de `$values` es un array asociativo, donde la clave es el nombre del campo del formulario o JSON, si se envió un JSON o el valor, obviamente, el contenido del campo del formulario

### Validación de entradas del usuario

Si quiere recibir una entrada de usuario, como por ejemplo, un correo electrónico, una cadena `UUID` u otro formato, puede escribir las siguientes líneas:

```php
namespace DLUnire\Controllers;
use Framework\Config\Controller;

final class TestController extends Controller {

    public function method(object $param): string {

        /**
         * Correo electrónico
         * 
         * @var string|null $email
         */
        $email = $this->get_email('email');

        /**
         * Identificador único universal
         * 
         * @var string |null $uuid
         */
        $uuid = $this->get_uuid('uuid');

        return view('vista', [
            "email" => $email,
            "uuid" => $uuid
        ]);
    }
}
```

### Creación de Modelos

Para crear un modelo, puede escribir las siguientes líneas en un archivo que se ubique en `app/Models`:

```php
namespace DLUnire\Models;

use DLTools\Database\Model;

final class Tabla extends Model {}
```

No hace falta definir algo más, a menos que desee agregar funcionalidades personalizadas.

Cuando se define una clase llamada `Tabla` extendida en un modelo, `DLUnire` será capaz de mapear el nombre de la tabla, por ejemplo, si la clase se llama `Tabla`, entonces, apuntará de forma automática a `tabla` y si tiene prefijo las tablas deberá indicarlo en las variables de entorno con tipos estáticos (lo veremos más adelante).

Si desea que su clase `Tabla` apunte a otra tabla, solo tiene que definirla de esta forma:

```php
namespace DLUnire\Models;

use DLTools\Database\Model;

final class Tabla extends Model {
    protected static ?string $table = "otra_tabla";
}
```

Y eso es todo. No necesita hacer más cosas.

#### ¿Cómo uso el modelo para interactuar con la base de datos?

Para interactuar con la base de datos usando el model que recién creó, solo debe llamar a sus métodos disponibles, en función del contexto en el que se encuentre, por ejemplo, es va a obtener una lista de usuarios.

Para obtener una lista de usuarios, asegúrese de que su clase sea `PascalCase` con el nombre de la tabla usuarios, por ejemplo:

```php
namespace DLUnire\Models;

use DLTools\Database\Model;

class Users extends Model {}
```

Y luego, consulta la lista de usuarios de esta forma:

```php
$users = Users::get();
```

También, puede consultar la lista de usuarios con paginación incluida de esta forma:

```php
/**
 * Número de páginas
 * 
 * @var int $page
 */
$page = 1;

/**
 * Registros por página
 * 
 * @var integer $rows
 */
$rows = 100;

$users = Users::paginate($page, $rows);
```

---

### Continuará

Por ahora, lo dejo hasta aquí. Me queda mucho camino por recorrer para terminar de hacer este tutorial, **ya que el proyecto es bastante extenso.**
