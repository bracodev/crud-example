# CRUD
El proyecto esta diseñado con el patron MVC, se utilizaron unos script para gestionar las peticiones al servidor. La estrucura es la sigueinte:

Para acceder al móidulo de Usuarios, ingrese en http://crud.dev/usuarios

Para configurar la conexión con la Base de datos edite los parametros del archivo */core/config.php*

## Controladores
*/controllers*
En esta se encuentran los contralores y la lógica del proyecto

## Vistas
*/views*
En esta se encuentran las vistas.

## Modelos
*/models*
En esta se encuntran los modelos que interactuan con la base de datos. Estos modelos extienden de la clase Model ubicada en */core/model.php*, en esta se encuentran varios métodos para el acceso a los datos y el manejo de los mismos.


## Core
*/core*
En esta carpeta se encuentra el núcleo del sistema, las clases que hacen posible la reutilización del código fuente y el patrón MVC.

- **Controller**: Clase controlador, algunas de las funciones que permite esta clase es heredarle los siguientes métodos a los controladores: obtener variables POST, PUT, sanear parametros antes de ser pasados a consultas SQL, devolver JSON, Renderizar vistas, entre otros. A continuación se presnta la estructura básica de un contralador:
```php
<?php
class MiController extends Controller
{
    public function __construct() {
        parent::__construct();
    }
    
    public function index() { }

    public function find( $id = NULL) {}

    public function store()  {}
    
    public function update() { }
    
    public function destroy( $id = NULL ){ }
}
```


- **Model**: Clases de modelos, tiene métodos preestablecidos para traer todos los registros (**all**), buscar un registro por id u otro campo (**where**), insertar y actualizar un registro (**insert**, **update**), eliminar un registro (**destroy**). Esta clase permite herdarle a sus hijas estos metodos para la gestion de datos. Vea por ejemplo: */model/Usuarios.php*.

```php
<?php
class Usuarios extends Model
{
    public function __construct() {
		parent::__construct( __CLASS__);
    }
}
```
Ejempos de los metodos que pueden ser accedidos:
```php
$modelo = new MiModelo();

$modelo->all();
$modelo->where();
$last_id = $modelo->insert(); //devuelve el último ID autonumérico insertado
$modelo->update( $id );
$modelo->destroy( $id );
```
- **Autoload**: Permite hacer autocarga de clases (Controladores, Modelos, y Clases del núcleo)
- **Database**: Responsable de la conexión y ejecución de consultas con la base de datos
- **Config**: parametros de configuración de la base de datos.

## Imágenes

<img src="http://hostingboxcp.com/crud-example/Screenshot_1.png" alt="Read">
<img src="http://hostingboxcp.com/crud-example/Screenshot_2.png" alt="Read">
<img src="http://hostingboxcp.com/crud-example/Screenshot_3.png" alt="Read">
<img src="http://hostingboxcp.com/crud-example/Screenshot_4.png" alt="Read">
