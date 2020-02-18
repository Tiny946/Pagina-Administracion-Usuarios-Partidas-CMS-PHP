<?php

namespace App\Model;

class Partida {

    //Variables o atributos
    var $usuario;
    var $puntuacion;
    var $duracion;
    var $fecha_acceso;
    function __construct($data=null){
        $this->usuario = ($data) ? $data->usuario : null;
        $this->puntuacion = ($data) ? $data->puntuacion : null;
        $this->duracion = ($data) ? $data->duracion : null;
        $this->fecha_acceso = ($data) ? $data->fecha_acceso : null;

    }
}