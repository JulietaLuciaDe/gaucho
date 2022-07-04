<?php

class reporteController
{
    private $reporteModel;
    private $printer;

    public function __construct($reporteModel,$printer){
        $this->reporteModel=$reporteModel;
        $this->printer=$printer;
    }

    public function execute($data = []){
        if($_SESSION['tipoUser']==1){
            $menu ="<p>Hola, ".$_SESSION['user']."</p>
                  <a href='/inicio'>Inicio</a>
                  <a href='/logIn/exit'>Cerrar Sesion</a>";

                  $reservas = $this->reporteModel->getDatosReservas();
        $cantidad = $this->reporteModel->getCantidadDeAsientosPorVuelo();
        $clientes = $this->reporteModel->getFacturacionPorCliente();

        $data+=['Cantidad'  => $cantidad];
        $data+=['Reservas'  => $reservas];
        $data+=['Clientes'  => $clientes];
        $data+=['menu'      => $menu];

        $this->printer->generateView('reporteView.html',$data);
        }else{
            header("Location: /inicio");
            exit();
        }

        
    }
}