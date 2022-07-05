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
        $fechayHoraReserva = $datosAbordaje[0]['fechaReserva'].' '.$datosAbordaje[0]['horaReserva'].':00';
        $fechaYHoraActual = FechaYHoraHelper::getFechaYHoraActual();
        $checkin2hs = FechaYHoraHelper::restarHoras($fechayHoraReserva,2);
        $checkin48hs = FechaYHoraHelper::restarHoras($fechayHoraReserva,48);

        if($fechaYHoraActual<$checkin2hs){
          if($fechaYHoraActual>$checkin48hs){
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $codigoAutorizacion = substr(str_shuffle($permitted_chars), 0, 11);
            if($this->misReservasModel->marcarCheckInOK($id_reserva,$codigoAutorizacion)){
                $message = "Codigo reserva: ".$datosAbordaje[0]['id']."\n Codigo Vuelo: ".$datosAbordaje[0]['id_Vuelofk']."\n Cliente: 
                ".$datosAbordaje[0]['cliente']."\n Codigo de autorizacion de abordaje: ".$datosAbordaje[0]['codigoAlfanumerico']."\nFecha Salida: ".$datosAbordaje[0]['fechaReserva']."\n Hora salida:
                ".$datosAbordaje[0]['horaReserva']."\n Origen: ".$datosAbordaje[0]['origen']."\n Destino: 
                ".$datosAbordaje[0]['destino']."\n \n Cantidad de pasajeros: ".$datosAbordaje[0]['cantidadAsientos']."\n Total pagado:
                ".$datosAbordaje[0]['monedaReserva']." ".$datosAbordaje[0]['TotReservaMoneda'];

               
                $this->generarPDF($id_reserva);
                $this->generarQRAbordaje($message);
                
                
                if($enviado = $this->misReservasModel->enviarMailCheckIn($id_reserva)){
                  
                      header('Location: /misreservas/execute');
                      exit();
                }else{
                  $title = "Ha ocurrido un problema!";
                  $message = "Contáctese con nosotros para mas informacion a gauchorocketargoficial@gmail.com";
                      $display = "d-block";
                  $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
                  $this->execute('misReservasView.html',$data);
                }
            }else{
                $title = "Ha ocurrido un problema!";
                $message = "Contáctese con nosotros para mas informacion a gauchorocketargoficial@gmail.com";
                    $display = "d-block";
                $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
                $this->execute('misReservasView.html',$data);
            } 
        }else{
          $title = "Demasiado pronto";
            $message = "Todavia no está disponible el check in en tu reserva. Se habilitará 48hs antes de la salida";
                $display = "d-block";
            $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
            $this->execute('misReservasView.html',$data);
        }
      }else{
            $title = "Demasiado tarde";
            $message = "Los pasajeros del vuelo ya están confirmados. Ya no podés hacer el checkin";
                $display = "d-block";
            $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
            $this->execute('misReservasView.html',$data);
      }

    }


    public function eliminarReserva(){
      $id_reserva = $_GET['id'];
      echo var_dump($id_reserva);
      $this->misReservasModel->eliminarReserva($id_reserva);
      header("Location:localhost/misReservas");
      exit();
    }


    public function generarQRAbordaje($contenido){
      $path = 'http://localhost/';
                  if(!file_exists('public/img/')) //si no existe la carpeta
                      mkdir('public/img/'); //creame la carpeta
                  $fileName =  'public/img/QRAbordaje.png';
                  QRcode::png($contenido,$path.$fileName,'M',10,3); //textoQR,dondeSeGuarda,Nivel,Tamaño,Margen
                  $imgQr = $path.$fileName;
                   $img = '<img src="'.$imgQr.'"/>';
                  echo $img;
    }


    
    public function generarPDF($id_reserva = ''){
        if($id_reserva == ''){
            $idreserva = $_GET['id'];
        }else{
          $idreserva = $id_reserva;
        }
        $datosAbordaje = $this->misReservasModel->getDatosAbordaje($idreserva);
        $mensaje= "      <h2>Datos de Abordaje:</h2>
        <div>
        <div>
         
        <p>Debe presentar este código para abordar en la nave: ".$datosAbordaje[0]['codigoAlfanumerico']."</p>
        <p> \n <b>Codigo Reserva:</b> ".$datosAbordaje[0]['id']."</p>
        <p> \n <b>Codigo Vuelo:</b> ".$datosAbordaje[0]['id_Vuelofk']."</p>
        <p> \n <b>Cliente:</b> ".$datosAbordaje[0]['cliente']."</p>
        <p> \n <b>Fecha de salida:</b> ".$datosAbordaje[0]['fechaReserva']."</p>
        <p> \n <b>Hora de salida:</b> ".$datosAbordaje[0]['horaReserva'].":00 hs</p>
        <p> \n <b>Origen:</b> ".$datosAbordaje[0]['origen']."</p>
        <p> \n <b>Destino:</b> ".$datosAbordaje[0]['destino']."</p>
        <p> \n <b>Total abonado:</b> ".$datosAbordaje[0]['monedaReserva'].' '.$datosAbordaje[0]['TotReservaMoneda'].".00</p>    
        </div>
        <div style = 'text-align:center'>
        <img src='public/img/QRAbordaje.png'>  
        </div>
        
        </div>
        ";
                      
                        
                        
                        //img para insertar QR no la toma por la ruta  <img src='http://localhost/public/img/QRAbordaje'>
        $html=$mensaje;
        $nombrePDF= "CheckIn";
        $pdf = new Dompdf();//Inicializa
        $pdf->setPaper('A4','landscape');//Se ajusta el papel
        $pdf->loadHtml($html);//lo carga en la hoja el html
        $pdf->render();//renderiza de html a pdf
        
        $pdf->stream($nombrePDF, ['Attachment'=>1]);//genera el pdf en el navegador /false misma pag / true descarga
        //El ['Attachment'=>0] es para q lo genere en otra pestaña, cambiar a 1 para decargar
    }


   



}

