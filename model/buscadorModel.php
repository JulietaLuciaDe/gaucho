<?php

class buscadorModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function buscar($viaje){
        $sqlTravel="SELECT * FROM vuelos WHERE destino = '".$viaje."' OR origen = '".$viaje."'";
        $array = [];
        $auxiliar = $this->database->queryResult($sqlTravel);
        $datos = $auxiliar->fetch_assoc();
        while($datos){
            $array[]=$datos;
            $datos=$auxiliar->fetch_assoc();
        }
        return $array;
    }
}