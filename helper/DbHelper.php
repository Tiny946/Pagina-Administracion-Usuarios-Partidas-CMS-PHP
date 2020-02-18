<?php

/*
 * Esta clase solo tendrá un construct encargado de generar una conexión a la base de datos que luego podremos utilizar para las diferentes consultas.
 */

namespace App\Helper;

class DbHelper {

    var $db;

    function __construct(){

        //Conexión mediante PDO
        $opciones = [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
        try {
            $this->db = new \PDO(
                'mysql:host=localhost;dbname=pacman',
                'usuario-pacman',
                'Abcd1234.',
                $opciones);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo 'Falló la conexión: ' . $e->getMessage();
        }

    }

}