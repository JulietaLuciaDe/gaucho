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
        $resultado = $this->buscadorModel->buscar($_POST["viajeABuscar"]);
        $this->printer->generateDataView($resultado,"buscadorView.php");
    }
}