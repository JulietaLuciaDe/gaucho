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

    public function execute($view = 'misReservasView.html',$data = []){
      if(validatorHelper::validarSesionActiva()){
          $menu ="<p>Hola, ".$_SESSION['user']."</p>
              <a href='/misReservas/execute'>Mis Reservas</a>
                <a href='/logIn/exit'>Cerrar Sesion</a>";
        }else{
          $menu ="<a href='/registro'>Registrarse</a>
          <a href='/logIn'>Ingresar</a>";
        }
        $data += ["menu"=>$menu];
        $pendientesDePago = $this->misReservasModel->misReservasImpagas();
        $misReservasCheckeadas = $this->misReservasModel->misReservasCheckeadas();
        $pendientesDeCheckIn = $this->misReservasModel->misReservasCheckIn();
        $data += ["pendientesDePago"=>$pendientesDePago, "misReservasCheckeadas"=>$misReservasCheckeadas , "pendientesDeCheckIn"=>$pendientesDeCheckIn ];
        
        $this->printer->generateView('misReservasView.html',$data);
        echo $reservas;

    
    }


    public function eliminarReserva(){

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