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
    
    public function enviarMailCheckIn($QR,$PDF,$id_reserva){
        $to  = $_SESSION["usuario"]; 
        $user = $_SESSION["user"];
        $subject = 'Ya podés viajar!!'; 
        $message = "<h1>Hola, ".$user."!!!!</h1> 
        
                    <p>Realizaste el checkIn con éxito. Te hacemos llegar por este medio la informacion 
                    de tu abordaje</p>
                    ".$PDF;
                    //ACA IRIA EL PDF Y EL QR PERO NO PUEDO PROBAR SI ANDA PORQUE NO MANDA EL MAIL
                    
                    $mail = new PHPMailer(true);
                    
                    try {
                        $mail->SMTPDebug = 0;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'gauchorocket.oficial@gmail.com';                     //SMTP username
                        $mail->Password   = 'cbotlwdakzqkwwwb';                               //SMTP password
                        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                        $mail->setFrom('gauchorocket.oficial@gmail.com', 'Gaucho Rocket');
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