<?php
class Configuration{
    private $config;

    public function createReservasController(){
        require_once("controller/ReservasController.php");
        return new ReservasController($this->createReservasModel(),$this->createPrinter());
    }
}