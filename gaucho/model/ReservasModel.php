<?php

class ReservasModel
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function getReservas(){
        return $this->database->query("SELECT * FROM reservas");
    }
}