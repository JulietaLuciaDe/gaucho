<?php

class reporteController
{
    private $reporteModel;
    private $printer;

    public function __construct($reporteModel,$printer){
        $this->reporteModel=$reporteModel;
        $this->printer=$printer;
    }

    public function execute($data){
        if(validatorHelper::validarSesionActiva()){
            $menu ="<p>Hola, ".$_SESSION['user']."</p>
                <a href='/logIn/exit'>Cerrar Sesion</a>";
        }else{
            $menu ="<a href='/registro'>Registrarse</a>
            <a href='/logIn'>Ingresar</a>";
        }
        $data += ["menu"=>$menu];
        $this->printer->generateView('reporteView.html',$data);
    }
}