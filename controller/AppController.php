<?php
namespace App\Controller;

//todolo que tenga que ver con el front

use App\Model\Noticia;
use App\Model\Partida;
use App\Helper\ViewHelper;
use App\Helper\DbHelper;


class AppController
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

    public function index(){

        //echo "hols";
        //Consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM partidas WHERE activa=1 ORDER BY fecha DESC");

        //Asigno resultados a un array de instancias del modelo
        $partidas = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($partidas,new Partida($row));
        }

        //Llamo a la vista
        $this->view->vista("app", "index", $partidas);
    }

    public function acercade(){

        //Llamo a la vista
        $this->view->vista("app", "acerca-de");

    }

    public function noticias(){

        //Consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM partidas WHERE activa=1 ORDER BY fecha DESC");

        //Asigno resultados a un array de instancias del modelo
        $noticias = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($noticias,new Noticia($row));
        }

        //Llamo a la vista
        $this->view->vista("app", "noticias", $noticias);

    }

    public function noticia($slug){

        //Consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM noticias WHERE activo=1 AND slug='$slug' LIMIT 1");

        //Asigno resultado a una instancia del modelo
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $noticia = new Noticia($row);

        //Llamo a la vista
        $this->view->vista("app", "noticia", $noticia);

    }

    public function mostrar(){

        //mostrar un listado de noticias activas
        //Consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM partidas WHERE activa=1 ORDER BY fecha DESC");

        //Asigno resultados a un array de instancias del modelo
        $noticias = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($noticias,new Noticia($row));
        }

        //Compongo el array con la info que necesita la API
        $array_noticias = array();
        foreach ($noticias as $row){
            $array_noticias[] = [
                'titulo' => $row->titulo,
                'entradilla' => $row->entradilla,
                'texto' => $row->texto,
                'autor' => $row->autor,
                'fecha' => date("d/m/y", strtotime($row->fecha)),
                'enlace'=>$_SESSION['home']."noticia/".$row->slug,
                'imagen'=>$_SESSION['public']."img/".$row->imagen
                ];
        }

        //muestro en formato JSON con opciones para tildes y caracteres de escape
        echo json_encode($array_noticias,
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    }
    //Acceder a un JSON y lo muestra de otra forma
    public function leer(){

        $url = "http://35.180.254.5/ejerciciosPropuestos/CMS/public/index.php/mostrar";

        $noticias = json_decode(file_get_contents($url, true));

        foreach ($noticias as $row){
            echo "<a href='".$row->enlace."'>".$row->titulo."</a>";
            echo "<img src='".$row->imagen."'>";
        }
    }


    public function mostrarJSONpartida(){
        //echo "hola";
        //mostrar un listado de noticias activas
        //Consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM partidas WHERE activa=1 ORDER BY fecha DESC");

        //Asigno resultados a un array de instancias del modelo
        $partidas = array();

        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($partidas,new Partida($row));
        }
        //Compongo el array con la info que necesita la API
        $array_partidas = array();
        foreach ($partidas as $row){
            $array_partidas[] = [
                'usuario' => $row->usuario,
                'puntuacion' => $row->puntuacion,
                'duracion' => $row->duracion,
                'fecha' => date("d/m/y", strtotime($row->fecha))
            ];
        }

        //muestro en formato JSON con opciones para tildes y caracteres de escape
        echo json_encode($array_partidas,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    }

    //mostar listado de partidas
    public function partidas(){

        //echo $_SESSION['usuario'];
        $usuarioActivo = $_SESSION['usuario'];

        //Consulta a la bbdd
        if($usuarioActivo == "admin"){
            $rowset = $this->db->query("SELECT * FROM partidas WHERE activa=1 ORDER BY fecha DESC");

        }else {
            $rowset = $this->db->query("SELECT * FROM partidas WHERE activa=1 AND usuario='$usuarioActivo' ORDER BY fecha DESC");
        }
        //Asigno resultados a un array de instancias del modelo
        $partida = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($partida,new Partida($row));
        }
        //echo "llego";
        //Llamo a la vista
        $this->view->vista("admin", "noticias/index", $partida);

    }
}