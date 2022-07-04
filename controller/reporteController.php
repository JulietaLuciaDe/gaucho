<?php
include_once("helper/fpdf-example/fpdf/fpdf.php");


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

    public function vuelosGenerar(){
        if($_SESSION['tipoUser']==1){
            $w = 10;
            $setY = 10;

            $id=$_GET['id'];
            $reservas = $this->reporteModel->getReservasSegunVuelo($id);


            $pdf = new FPDF();
            $pdf->AddPage();

            //Titulo
            $pdf->SetY($setY);
            $pdf->SetFont("Arial","B",12);
            $pdf->SetY(5);
            $pdf->Cell($w,10,"Reporte de vuelos");

            //Logo
            $pdf->SetY($setY);
            $pdf->Image("http://localhost/public/img/logo.png",175);

            //Subtitulos
            $setY+=5;
            $pdf->SetFont("Arial","",11);
            $pdf->SetY($setY);
            $pdf->Cell($w,10,"Pasajes del vuelo con id:".$id);

            $setY+=5;
            $pdf->SetFont("Arial","",9);
            $pdf->SetY($setY);
            $pdf->Cell($w,10,"Fecha de vuelo:".ValidatorHelper::devolverFechaFormatoLatino($reservas[0]['fecha']));

            //Cabeceras
            $setY+=15;
            $pdf->SetFont("Arial","B",9);
            $pdf->SetY($setY);
            $pdf->Cell($w+10,10,"Nombre");
            $pdf->Cell($w+10,10,"Apellido");
            $pdf->Cell($w+30,10,"Cantidad de pasajes");
            $pdf->Cell($w+30,10,"ARP abonados");
            $pdf->Cell($w+10,10,"ARS$ abonados");

            $pdf->SetFont("Arial","",9);
            foreach($reservas as $reserva){
                $setY+=5;

                //Datos
                $pdf->SetY($setY);
                $pdf->Cell($w+10,10,$reserva['nombre']);
                $pdf->Cell($w+10,10,$reserva['apellido']);
                $pdf->Cell($w+30,10,$reserva['cantidadAsientos']);
                $pdf->Cell($w+30,10,"ARP ".$reserva['TotalReserva']);
                $pdf->Cell($w+10,10,"ARS$ ".$reserva['TotReservaMoneda']);
            }

            $pdf->output();
        }else{
            header("Location:inicio");
            exit();
        }
    }
}