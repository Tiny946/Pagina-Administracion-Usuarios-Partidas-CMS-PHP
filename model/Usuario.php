<?php

/*
 * Noticia y Usuario.

   En ambos cosas consistirán en un construct que recogerá los datos de la consulta a la base de datos (o null si estoy creando uno nuevo) y los asignará a las distintas variables.
 */

namespace App\Model;

class Usuario {

    //Variables o atributos
    var $id;
    var $usuario;
    var $clave;
    var $fecha_acceso;
    var $activo;
    var $partidas;
    var $imagen;
    function __construct($data=null){

        $this->id = ($data) ? $data->id : null;
        $this->usuario = ($data) ? $data->usuario : null;
        $this->clave = ($data) ? $data->clave : null;
        $this->fecha_acceso = ($data) ? $data->fecha_acceso : null;
        $this->activo = ($data) ? $data->activo : null;
        $this->partidas = ($data) ? $data->partidas : null;
        $this->usuarios = ($data) ? $data->usuarios : null;
        $this->imagen = ($data) ? $data->imagen : null;

    }
}