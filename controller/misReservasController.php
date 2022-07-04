<?php
require_once  'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;
include("phpqrcode/qrlib.php");

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



      if($_SESSION['tipoUser']!=1){
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
      }else{
          header("Location: /inicio");
          exit();
      }
    

    }

    public function checkInOn(){
        if(isset($_GET['id'])){
            $id_reserva = $_GET['id'];
        }else{
            header('Location: /inicio');
            exit();
        }
        $datosAbordaje = $this->misReservasModel->getDatosAbordaje($id_reserva);

        //AGREGAR CODIGO ALFANUMERICO EN EL MESSAGE
        $message = "Codigo reserva: ".$datosAbordaje[0]['id']."\n Codigo Vuelo: ".$datosAbordaje[0]['id_Vuelofk']."\n Cliente: 
      ".$datosAbordaje[0]['cliente']."\n Fecha Salida: ".$datosAbordaje[0]['fechaReserva']."\n Hora salida:
      ".$datosAbordaje[0]['horaReserva']."\n Origen: ".$datosAbordaje[0]['origen']."\n Destino: 
      ".$datosAbordaje[0]['destino']."\n \n Cantidad de pasajeros: ".$datosAbordaje[0]['cantidadAsientos']."\n Total pagado:
      ".$datosAbordaje[0]['monedaReserva']." ".$datosAbordaje[0]['TotReservaMoneda'];


        $path = 'http://localhost/';
        if(!file_exists('public/img/')) //si no existe la carpeta
            mkdir('public/img/'); //creame la carpeta

        $fileName =  'public/img/QRAbordaje.png';
        $contenido = $message;

        QRcode::png($contenido,$fileName,'M',10,3); //textoQR,dondeSeGuarda,Nivel,Tamaño,Margen
        $imgQr = $path.$fileName;
        /* $img = '<img src="'.$imgQr.'"/>';
         echo $img;  //Muestr QR
           */
        $this->generarPDF($id_reserva);

    }


    public function eliminarReserva(){
      $id_reserva = $_GET['id'];
      echo var_dump($id_reserva);
      $this->misReservasModel->eliminarReserva($id_reserva);
      header("Location:localhost/misReservas");
      exit();
    }

    public function generarPDF($id_reserva){

        $datosAbordaje = $this->misReservasModel->getDatosAbordaje($id_reserva);
        $mensaje= "        <center><h2>Datos de Abordaje:</h2></center>
                        <div style='text-align: center'>
                        <p>Cliente: ".$datosAbordaje[0]['cliente']."</p><br>
                        <p>Fecha de salida: ".$datosAbordaje[0]['fechaReserva']."</p><br>
                        <p>Hora de salida: ".$datosAbordaje[0]['horaReserva']."</p><br>
                        <p>Origen: ".$datosAbordaje[0]['origen']."</p><br>
                        <p>Destino: ".$datosAbordaje[0]['destino']."</p><br>
                        <p>Total: ".$datosAbordaje[0]['TotReservaMoneda']."</p>  <br>  
                        </div>
                        <img src='./public/img/QRAbordaje.png'>";
        $html=$mensaje;
        $nombrePDF= "CheckIn";
        $pdf = new Dompdf();//Inicializa
        $pdf->setPaper('A4','landscape');//Se ajusta el papel
        $pdf->loadHtml($html);//lo carga en la hoja el html
        $pdf->render();//renderiza de html a pdf
        $pdf->stream($nombrePDF, ['Attachment'=>0]);//genera el pdf en el navegador /false misma pag / true descarga
        //El ['Attachment'=>0] es para q lo genere en otra pestaña, cambiar a 1 para decargar
    }
}