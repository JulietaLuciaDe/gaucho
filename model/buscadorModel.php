<?php

class buscadorModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function buscar($viaje){
        $sqlTravel="SELECT * FROM vuelos WHERE destino = '".$viaje."' OR origen = '".$viaje."'";
        //TODO: competar la ejecución de la consulta.
    }
}