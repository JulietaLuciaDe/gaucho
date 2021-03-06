<?php

include_once("helper/Database.php");
include_once("controller/registroController.php");
include_once("model/registroModel.php");
include_once("controller/logInController.php");
include_once("model/logInModel.php");
include_once("controller/inicioController.php");
include_once("model/inicioModel.php");
include_once("model/reservaVueloModel.php");
include_once("controller/reservaVueloController.php");
include_once("model/misReservasModel.php");
include_once("controller/misReservasController.php");
include_once("controller/reporteController.php");
include_once("model/reporteModel.php");
include_once("helper/MustachePrinter.php");
include_once("helper/FechayHoraHelper.php");

class Configuration{

    public function __construct(){

    }

    public function getRegistroController(){
        return new RegistroController($this->getRegistroModel(),$this->getMustachePrinter());
    }

    private function getRegistroModel(){
        return new RegistroModel($this->getDatabase());
    }

    public function getMisReservasController(){
        return new MisReservasController($this->getmisReservasModel(),$this->getMustachePrinter());
    }

    private function getMisReservasModel(){
        return new MisReservasModel($this->getDatabase());
    }

    public function getLogInController(){
        return new LogInController($this->getLogInModel(),$this->getMustachePrinter());
    }

    private function getLogInModel(){
        return new LogInModel($this->getDatabase());
    }

    public function getInicioController(){
        return new InicioController($this->getInicioModel(),$this->getMustachePrinter());
    }

    private function getInicioModel(){
        return new InicioModel($this->getDatabase());
    }

    private function getReservaVueloModel(){
        return new reservaVueloModel($this->getDatabase());
    }

    public function getReservaVueloController(){
        return new reservaVueloController($this->getReservaVueloModel(),$this->getMustachePrinter());
    }

    private function getReporteModel(){
        return new ReporteModel($this->getDatabase());
    }

    public function getReporteController(){
        return new reporteController($this->getReporteModel(),$this->getMustachePrinter());
    }

    private function getDatabase(){
        return new Database("config/config.ini");
    }

    private function getPrinter(){
        return new Printer();
    }

    private function getMustachePrinter(){
        return new MustachePrinter("view");
    }
}

?>