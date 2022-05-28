<?php

class buscadorController
{
    private $printer;
    private $buscadorModel;

    public function __construct($printer,$buscadorModel){
        $this->printer = $printer;
        $this->buscadorModel = $buscadorModel;
    }

    public function execute(){
        $this->printer->generateView("buscadorView.php");
    }

    public function buscar(){
        $get = $_GET["viajeABuscar"];
        $this->buscadorModel->buscar($get);
    }

}