<?php
 require_once  'dompdf/autoload.inc.php';
 use Dompdf\Dompdf;
 

class MisReservasController {
    private $printer;
    private $misReservasModel;
    private $pdf;

    public function __construct($misReservasModel,$printer) {
        $this->printer = $printer;
        $this->misReservasModel = $misReservasModel;
    }

    public function execute() {
        if(ValidatorHelper::validarSesionActiva()){
          $menu ="<p>Hola, ".$_SESSION['user']."</p>
                  <a href='/misReservas/excute'>Mis Reservas</a>
                  <a href='/logIn/exit'>Cerrar Sesion</a>";
          }else{
            $menu ="<a href='/registro'>Registrarse</a>
            <a href='/logIn'>Ingresar</a>";
          }

        $reservas = $this->misReservasModel->misReservas();
        $data = ["misReservas"=>$reservas];
        $this->printer->generateView('misReservasView.html',$data);
        
    }

    public function generarPDF(){
      include_once("view/pdfView.html");
      $html = ob_get_clean();
      $nombrePDF= "CheckIn";
      $pdf = new Dompdf();//Inicializa
      $pdf->setPaper('A4','landscape');//Se ajusta el papel
      $pdf->loadHtml($html);//lo carga en la hoja el html
      $pdf->render();//renderiza de html a pdf
      $pdf->stream($nombrePDF, ['Attachment'=>1]);//genera el pdf en el navegador /false misma pag / true descarga
      //El ['Attachment'=>0] es para q lo genere en otra pestaña
    }
}