<?php

class ReservasController
{
    private $reservasModel;
    private $printer;

    public function __construct($reservasModel,$printer){
        $this->reservasModel = $reservasModel;
        $this->printer = $printer;
    }
    public function show(){
        $reservas = $this->reservasModel->getPresentaciones();
        $data["reservas"] = $reservas;
        echo $this->printer->render("view/reservasView.html",$data);
    }
}