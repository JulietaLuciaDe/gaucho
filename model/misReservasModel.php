<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    class MisReservasModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }
   
    public function misReservasImpagas(){
        $id_usuario=$_SESSION['id'];
        $sqlmisReservas="SELECT R.id, R.id_vuelofk,R.id_usuariofk,R.fechaReserva,R.horaReserva,R.TotalReserva, D.descripcion as 'origen' , DE.descripcion as 'destino' FROM RESERVA R JOIN DESTINOS D ON R.origenReserva = D.id_destino JOIN DESTINOS DE ON R.destinoReserva = DE.id_destino WHERE id_usuariofk=".$id_usuario." and pago=0";
        $datos = $this->database->queryResult($sqlmisReservas);
        return $datos;
    }
    public function misReservasCheckIn(){
        $id_usuario=$_SESSION['id'];
        $sqlmisReservas="SELECT R.id, R.id,R.fechaReserva,R.horaReserva,R.TotReservaMoneda,R.monedaReserva, D.descripcion as 'origen' , DE.descripcion as 'destino' FROM RESERVA R JOIN DESTINOS D ON R.origenReserva = D.id_destino JOIN DESTINOS DE ON R.destinoReserva = DE.id_destino WHERE id_usuariofk=".$id_usuario." and pago=1 AND checkin =0";
        $datos = $this->database->queryResult($sqlmisReservas);
        return $datos;
    }
  
    public function misReservasCheckeadas(){
        $id_usuario=$_SESSION['id'];
        $sqlmisReservas="SELECT R.id, R.fechaReserva,R.horaReserva,R.TotReservaMoneda,R.monedaReserva, D.descripcion as 'origen' , DE.descripcion as 'destino' FROM RESERVA R JOIN DESTINOS D ON R.origenReserva = D.id_destino JOIN DESTINOS DE ON R.destinoReserva = DE.id_destino WHERE id_usuariofk=".$id_usuario." and pago=1 AND checkin =1";
        $datos = $this->database->queryResult($sqlmisReservas);
        return $datos;
    } 

      
    public function eliminarReserva($idReserva){
        $id_usuario=$_SESSION['id'];
        $sql = "delete from reserva where id = ". $idReserva;
        return $this->database->query($sql);
    } 

    public function getDatosAbordaje($id_reserva){
        $query="SELECT R.codigoAlfanumerico,R.id,R.fechaReserva,R.horaReserva,R.TotReservaMoneda,R.monedaReserva,R.id_Vuelofk,R.cantidadAsientos, D.descripcion as 'origen' , DE.descripcion as 'destino', concat(U.Nombre,' ', U.Apellido) as 'cliente' FROM RESERVA R JOIN DESTINOS D ON R.origenReserva = D.id_destino JOIN DESTINOS DE ON R.destinoReserva = DE.id_destino JOIN USUARIOS U ON R.id_usuariofk = U.id WHERE R.id=".$id_reserva;
        $datos = $this->database->queryResult($query);
        return $datos;
    } 

    public function marcarCheckInOK($id_reserva,$codigoAutorizacion){
        $sql = "UPDATE reserva set checkIn = 1, codigoAlfanumerico = '".$codigoAutorizacion."' where id = ".$id_reserva;
        return $this->database->query($sql);
    }
    
    public function enviarMailCheckIn($id_reserva){
        $to  = $_SESSION["usuario"]; 
        $user = $_SESSION["user"];
        $subject = 'Ya podes viajar!!'; 

        $datosAbordaje = $this->getDatosAbordaje($id_reserva);
        $datosReserva= "      <h2>Datos de Abordaje:</h2>
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
     
        </div>
        ";


        $message = "<h1>Hola, ".$user."!!!!</h1> 
        
                    <p>Realizaste el checkIn con éxito. Te hacemos llegar por este medio la informacion 
                    de tu abordaje</p><br>
                    ".$datosReserva;
                    //ACA IRIA EL PDF Y EL QR 
                    
                    $mail = new PHPMailer(true);
                    
                    try {
                        $mail->SMTPDebug = 0;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'gauchorocketargoficial@gmail.com';                     //SMTP username
                        $mail->Password   = 'rhgnkeztistnuflx';                               //SMTP password
                        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                        $mail->setFrom('gauchorocketargoficial@gmail.com', 'Gaucho Rocket');
                        $mail->addAddress($to);     //Add a recipient
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = $subject;
                        $mail->Body    = $message;
                        $mail->send();
                        return true;
                    } catch (Exception $e) {
                        return false;
                    }
                    
    }


}

?>