<?php

include_once("helper/Database.php");
include_once("controller/registroController.php");
include_once("model/registroModel.php");
include_once("controller/logInController.php");
include_once("model/logInModel.php");
include_once("controller/buscadorController.php");
include_once("model/buscadorModel.php");
include_once("controller/inicioController.php");
include_once("model/inicioModel.php");
include_once("helper/Printer.php");



class Configuration{

    public function __construct(){

    }

    public function getRegistroController(){
        return new RegistroController($this->getRegistroModel(),$this->getPrinter());
    }

    private function getRegistroModel(){
        return new RegistroModel($this->getDatabase());
    }

    public function getLogInController(){
        return new LogInController($this->getLogInModel(),$this->getPrinter());
    }

    private function getLogInModel(){
        return new LogInModel($this->getDatabase());
    }

    public function getBuscadorController(){
        return new BuscadorController($this->getPrinter(),$this->getBuscadorModel());
    }

    private function getBuscadorModel(){
        return new BuscadorModel($this->getDatabase());
    }

    public function getInicioController(){
        return new InicioController($this->getPrinter());
    }

    private function getInicioModel(){
        return new InicioModel($this->getDatabase());
    }

    private function getDatabase(){
        return new Database("config/config.ini");
    }

    private function getPrinter(){
        return new Printer();
    }

    

}

?>