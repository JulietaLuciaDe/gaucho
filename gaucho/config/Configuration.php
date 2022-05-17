<?php
class Configuration{
    private $config;

    public function createReservasController(){
        require_once("controller/ReservasController.php");
        return new ReservasController($this->createReservasModel(),$this->createPrinter());
    }

    public function createBusquedaController(){
        requiere_once("controller/BusquedaController.php");
        return new BusquedaController($this->createBusquedaModel(),$this->createPrinter());
    }

    public function createLoginController(){
        requiere_once("controller/LoginController.php");
        return new LoginController($this->createLoginModel(),$this->createPrinter());
    }

}