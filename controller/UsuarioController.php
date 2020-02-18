<?php
namespace App\Controller;

//GESTIONA DENTRO DEL PANEL TODOLO RELATIVO A LOS USUARIOS COMO LA ENTRADA DE ELLOS, MODIFICARLOS,...


use App\Helper\ViewHelper;
use App\Helper\DbHelper;
use App\Model\Partida;
use App\Model\Usuario;


class UsuarioController
{
    var $db;
    var $view;

    function __construct()
    {
        //Conexión a la BBDD
        $dbHelper = new DbHelper();
        $this->db = $dbHelper->db;

        //Instancio el ViewHelper
        $viewHelper = new ViewHelper();
        $this->view = $viewHelper;
    }

    public function admin(){

        //Compruebo permisos
        $this->view->permisos();

        //LLamo a la vista
        $this->view->vista("admin","index");

    }

    public function comprobar(){

        //$usuario = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_STRING);
        $campo_usuario = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_STRING);
        $campo_clave = filter_input(INPUT_POST, "contrasena", FILTER_SANITIZE_STRING);

        //Busco al usuario en la base de datos, con el LIMIT 1 es para que me de solo 1 resultado
        $rowset = $this->db->query("SELECT * FROM usuarios WHERE usuario='$campo_usuario' AND activo=1 LIMIT 1");

        //Asigno resultado a una instancia del modelo
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $usuario = new Usuario($row);
        //Si existe el usuario
        if ($usuario->usuario){
            //Compruebo la clave al cifrarlo coinciden no que sean iguales
            if (password_verify($campo_clave,$usuario->clave)) {

                //Guardo la fecha de último acceso
                $ahora = new \DateTime("now", new \DateTimeZone("Europe/Madrid"));
                $fecha = $ahora->format("Y-m-d H:i:s");
                $this->db->exec("UPDATE usuarios SET fecha_acceso='$fecha' WHERE usuario='$campo_usuario'");

                echo "si";

            }
            else{
                //Redirección con mensaje
                echo "no";
            }
        }
        else{
            //Redirección con mensaje
            echo "no";
        }


    }

    public function entrar(){

        //Si ya está autenticado, le llevo a la página de inicio del panel
        if (isset($_SESSION['usuario'])){

            $this->admin();

        }
        else if (isset($_POST["registrar"])){
            $this->registrar();
        }
        //Si ha pulsado el botón de acceder, tramito el formulario
        else if (isset($_POST["acceder"])){

            //Recupero los datos del formulario
            $campo_usuario = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_STRING);
            $campo_clave = filter_input(INPUT_POST, "clave", FILTER_SANITIZE_STRING);

            //Busco al usuario en la base de datos, con el LIMIT 1 es para que me de solo 1 resultado
            $rowset = $this->db->query("SELECT * FROM usuarios WHERE usuario='$campo_usuario' AND activo=1 LIMIT 1");

            //Asigno resultado a una instancia del modelo
            $row = $rowset->fetch(\PDO::FETCH_OBJ);
            $usuario = new Usuario($row);

            //Si existe el usuario
            if ($usuario->usuario){
                //Compruebo la clave al cifrarlo coinciden no que sean iguales
                if (password_verify($campo_clave,$usuario->clave)) {

                    //Asigno el usuario y los permisos la sesión
                    $_SESSION["usuario"] = $usuario->usuario;
                    $_SESSION["usuarios"] = $usuario->usuarios;
                    $_SESSION["partidas"] = $usuario->partidas;

                    //Guardo la fecha de último acceso
                    $ahora = new \DateTime("now", new \DateTimeZone("Europe/Madrid"));
                    $fecha = $ahora->format("Y-m-d H:i:s");
                    $this->db->exec("UPDATE usuarios SET fecha_acceso='$fecha' WHERE usuario='$campo_usuario'");

                    //Redirección con mensaje
                    $this->view->redireccionConMensaje("admin","green","Bienvenido al panel de administración.");
                }
                else{
                    //Redirección con mensaje
                    $this->view->redireccionConMensaje("admin","red","Contraseña incorrecta.");
                }
            }
            else{
                //Redirección con mensaje
                $this->view->redireccionConMensaje("admin","red","No existe ningún usuario con ese nombre.");
            }
        }
        //Le llevo a la página de acceso
        else{
            $this->view->vista("admin","usuarios/entrar");
        }

    }

    public function salir(){

        //Borro al usuario de la sesión
        unset($_SESSION['usuario']);

        //Redirección con mensaje
        $this->view->redireccionConMensaje("admin","green","Te has desconectado con éxito.");

    }

    //Listado de usuarios
    public function index(){

        //Permisos
        $this->view->permisos("usuarios");

        //Recojo los usuarios de la base de datos
        $rowset = $this->db->query("SELECT * FROM usuarios ORDER BY usuario ASC");

        //Asigno resultados a un array de instancias del modelo
        $usuarios = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($usuarios,new Usuario($row));
        }

        $this->view->vista("admin","usuarios/index", $usuarios);

    }

    //Para activar o desactivar
    public function activar($id){

        //Permisos
        $this->view->permisos("usuarios");

        //Obtengo el usuario
        $rowset = $this->db->query("SELECT * FROM usuarios WHERE id='$id' LIMIT 1");
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $usuario = new Usuario($row);

        if ($usuario->activo == 1){

            //Desactivo el usuario
            $consulta = $this->db->exec("UPDATE usuarios SET activo=0 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/usuarios","green","El usuario <strong>$usuario->usuario</strong> se ha desactivado correctamente.") :
                $this->view->redireccionConMensaje("admin/usuarios","red","Hubo un error al guardar en la base de datos.");
        }

        else{

            //Activo el usuario
            $consulta = $this->db->exec("UPDATE usuarios SET activo=1 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/usuarios","green","El usuario <strong>$usuario->usuario</strong> se ha activado correctamente.") :
                $this->view->redireccionConMensaje("admin/usuarios","red","Hubo un error al guardar en la base de datos.");
        }

    }

    public function borrar($id){

        //Permisos
        $this->view->permisos("usuarios");

        //Borro el usuario
        $consulta = $this->db->exec("DELETE FROM usuarios WHERE id='$id'");

        //Mensaje y redirección
        ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
            $this->view->redireccionConMensaje("admin/usuarios","green","El usuario se ha borrado correctamente.") :
            $this->view->redireccionConMensaje("admin/usuarios","red","Hubo un error al guardar en la base de datos.");

    }

    public function crear(){

        //Permisos
        $this->view->permisos("usuarios");

        //Creo un nuevo usuario vacío
        $usuario = new Usuario();

        //Llamo a la ventana de edición
        $this->view->vista("admin","usuarios/editar", $usuario);

    }

    public function crearPartida(){

        //Permisos
        $this->view->permisos("usuarios");

        //Creo un nuevo usuario vacío
        $partida = new Partida();

        //Llamo a la ventana de edición
        $this->view->vista("admin","noticias/editar", $partida);

    }

    public function editar($id){

        //Permisos
        $this->view->permisos("usuarios");

        //Si ha pulsado el botón de guardar
        if (isset($_POST["guardar"])){

            //Recupero los datos del formulario
            $usuario = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_STRING);
            $clave = filter_input(INPUT_POST, "clave", FILTER_SANITIZE_STRING);
            $usuarios = (filter_input(INPUT_POST, 'usuarios', FILTER_SANITIZE_STRING) == 'on') ? 1 : 0;
            $partidas = (filter_input(INPUT_POST, 'partidas', FILTER_SANITIZE_STRING) == 'on') ? 1 : 0;
            $cambiar_clave = (filter_input(INPUT_POST, 'cambiar_clave', FILTER_SANITIZE_STRING) == 'on') ? 1 : 0;

            //Encripto la clave
            $clave_encriptada = ($clave) ? password_hash($clave,  PASSWORD_BCRYPT, ['cost'=>12]) : "";

            //Imagen
            $imagen_recibida = $_FILES['imagen'];
            $imagen = ($_FILES['imagen']['name']) ? $_FILES['imagen']['name'] : "";
            $imagen_subida = ($_FILES['imagen']['name']) ? '/var/www/html'.$_SESSION['public']."img/".$_FILES['imagen']['name'] : "";
            $texto_img = ""; //Para el mensaje

            if ($id == "nuevo"){

                //Creo un nuevo usuario
                $this->db->exec("INSERT INTO usuarios (usuario, clave, partidas, usuarios, imagen) VALUES ('$usuario','$clave_encriptada','$partidas','$usuarios','$imagen')");

                //Subo la imagen
                if ($imagen){
                    if (is_uploaded_file($imagen_recibida['tmp_name']) && move_uploaded_file($imagen_recibida['tmp_name'], $imagen_subida)){
                        //$texto_img = " La imagen se ha subido correctamente.";
                        $this->view->redireccionConMensaje("admin","green"," La imagen se ha subido correctamente.");

                    }
                    else{
                        //$texto_img = " Hubo un problema al subir la imagen.";
                        $this->view->redireccionConMensaje("admin","green","  Hubo un problema al subir la imagen.");

                    }
                }

                //Mensaje y redirección
                $this->view->redireccionConMensaje("admin/usuarios","green","El usuario <strong>$usuario</strong> se creado correctamente.");
            }
            else{

                //Actualizo el usuario
                ($cambiar_clave) ?
                    $this->db->exec("UPDATE usuarios SET usuario='$usuario',clave='$clave_encriptada',partidas=$partidas,usuarios=$usuarios WHERE id='$id'") :
                    $this->db->exec("UPDATE usuarios SET usuario='$usuario',partidas=$partidas,usuarios=$usuarios WHERE id='$id'");

                //Subo y actualizo la imagen
                if ($imagen){
                    if (is_uploaded_file($imagen_recibida['tmp_name']) && move_uploaded_file($imagen_recibida['tmp_name'], $imagen_subida)){
                        //$texto_img = " La imagen se ha subido correctamente.";
                        $this->view->redireccionConMensaje("admin","green"," La imagen se ha subido correctamente.");
                        $this->db->exec("UPDATE usuarios SET imagen='$imagen' WHERE id='$id'");
                    }
                    else{
                        $this->view->redireccionConMensaje("admin","green","  Hubo un problema al subir la imagen.");
                        //$texto_img = " Hubo un problema al subir la imagen.";
                    }
                }

                //Mensaje y redirección
                $this->view->redireccionConMensaje("admin/usuarios","green","El usuario <strong>$usuario</strong> se actualizado correctamente.");
            }
        }

        //Si no, obtengo usuario y muestro la ventana de edición
        else{

            //Obtengo el usuario
            $rowset = $this->db->query("SELECT * FROM usuarios WHERE id='$id' LIMIT 1");
            $row = $rowset->fetch(\PDO::FETCH_OBJ);
            $usuario = new Usuario($row);

            //Llamo a la ventana de edición
            $this->view->vista("admin","usuarios/editar", $usuario);
        }

    }

    public function registrar(){

        //echo "registar";

        //Recupero los datos del formulario
        $usuario = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_STRING);
        $clave = filter_input(INPUT_POST, "clave", FILTER_SANITIZE_STRING);

        if($usuario == "" || $clave == ""){
            $this->view->redireccionConMensaje("admin","red","Comprueba que has rellenado los campos.");

        }else{
            //Encripto la clave
            $clave_encriptada =  password_hash($clave,  PASSWORD_BCRYPT, ['cost'=>12]);

            //Guardo la fecha de registro
            $ahora = new \DateTime("now", new \DateTimeZone("Europe/Madrid"));
            $fecha = $ahora->format("Y-m-d H:i:s");
            //$this->db->exec("UPDATE usuarios SET fecha_acceso='$fecha' WHERE usuario='$campo_usuario'");

            //Creo un nuevo usuario
            $this->db->exec("INSERT INTO usuarios (usuario, clave,fecha_acceso) VALUES ('$usuario','$clave_encriptada','$fecha')");

            //echo "registrado";
            $this->view->redireccionConMensaje("admin","green","Usuario registrado correctamente");

        }



        //Mensaje y redirección
        //$this->view->redireccionConMensaje("admin/usuarios","green","El usuario <strong>$usuario</strong> se registro correctamente.");

    }


}