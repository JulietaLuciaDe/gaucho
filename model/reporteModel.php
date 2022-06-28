<?php

class reporteModel
{
    private $database;

    public function __construct($database){
        $this->database=$database;
    }

    public function getCantidadDeVuelosSegunFecha($fecha){
        $query = "SELECT COUNT(fecha) FROM vuelos_confirmados WHERE fecha LIKE '".$fecha."'";
        return $this->database->queryResult($query);
    }

    public function getVuelosConfirmadosSegunFecha($fecha){
        $query = "SELECT * FROM vuelos_confirmados WHERE fecha LIKE '".$fecha."'";
        return $this->database->queryResult($query);
    }
}