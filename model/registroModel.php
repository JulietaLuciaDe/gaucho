<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    class RegistroModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }


        public function registrar($name,$lastName,$dni,$email,$user,$pass){
            if( !$this->emailExistente($email)
                && !$this->dniExistente($dni)){
                $passMd5= md5($pass); 
                $sql = "INSERT INTO usuarios (nombre,apellido,dni,email,user,pass) VALUES ('".$name."','".$lastName."','".$dni."','".$email."','".$user."','".$passMd5."')";
                $this->database->query($sql);
                $confirmation = $this->sendConfirmationMail($email,$user);
                if($confirmation){
                    $status = "registrado";
                    return $status;
                }else{
                    $status = "noregistrado";
                    return $status;
                }
                
            }else{
                $status = "email=".$email."&dni=".$dni;
                return $status;
            }
           
        
        }

        private function emailExistente($email){
            $sql= "SELECT 1 FROM usuarios where email ='".$email."'";
            $result = $this->database->query($sql);
            $result = mysqli_fetch_assoc($result);
            return (isset($result["1"]))? true : false;
            
        }
        private function dniExistente($dni){
            $sql= "SELECT 1 FROM usuarios where dni ='".$dni."'";
            $result = $this->database->query($sql);
            $result = mysqli_fetch_assoc($result);
            return (isset($result["1"]))? true : false;
            
        }

        private function sendConfirmationMail($email,$user){
            $to  = $email; 
            $hash = md5($email);
            $subject = 'Confirmacion de registro en GauchoRocket'; 
            $message = "Bienvenido/a, ".$user."!!!! Aqui es donde comienza su mejor viaje...
                        Su cuenta ha sido creada. Por favor, ingrese al siguiente link para activarla:
                        
                        http://localhost/registro/validarRegistro/email=$email&hash=$hash
                        
                        Una vez activa su cuenta, recuerde que debe realizarse el checkeo médico para
                        poder contratar nuestros servicios. Podrá confirmar día y horario del mismo unicamente a través 
                        de este enlace 
                        http://localhost/registro/solicitarTurno/email=$email&hash=$hash";
                        
                        $mail = new PHPMailer(true);
                        
                        try {
                            $mail->SMTPDebug = 0;                      //Enable verbose debug output
                            $mail->isSMTP();                                            //Send using SMTP
                            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                            $mail->Username   = 'gauchorocketargentina@gmail.com';                     //SMTP username
                            $mail->Password   = 'hgdmsmjtkucnctgg';                               //SMTP password
                            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                            $mail->setFrom('gauchorocketargentina@gmail.com', 'Gaucho Rocket');
                            $mail->addAddress($to);     //Add a recipient
                            //$mail->isHTML(true);                                  //Set email format to HTML
                            $mail->Subject = $subject;
                            $mail->Body    = $message;
                            $mail->send();
                            return true;
                        } catch (Exception $e) {
                            return false;
                        }
                        
        }

        public function validarRegistro($email,$hash){
            $correctHash = md5($email);
            if($correctHash==$hash){
               header("Location: /logIn/autentificado/correo=$email");
            }else{
               header("Location: /inicio");
            }
    
        }

        public function turnoSolicitado($email){
            $sql= "SELECT 1 FROM usuarios where email ='".$email."' and turnoSolicitado = true";
            $result = $this->database->query($sql);
            $result = mysqli_fetch_assoc($result);
            return (isset($result["1"]))? true : false;
    
        }
        public function guardarTurno($email,$fecha,$hora,$centro){
            $nivel=rand(1, 3);
            $to  = $email; 
            $subject = 'Gaucho Rocket - Turno medico'; 
            $message = "Estimado/a, ".$_SESSION['user']." ha completado el checkeo médico.
                        su nivel de pasajero es $nivel";
                        
                        $mail = new PHPMailer(true);
                        
                        try {
                            $mail->SMTPDebug = 0;                      //Enable verbose debug output
                            $mail->isSMTP();                                            //Send using SMTP
                            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                            $mail->Username   = 'gauchorocketargentina@gmail.com';                     //SMTP username
                            $mail->Password   = 'hgdmsmjtkucnctgg';                               //SMTP password
                            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                            $mail->setFrom('gauchorocketargentina@gmail.com', 'Gaucho Rocket');
                            $mail->addAddress($to);     //Add a recipient
                            //$mail->isHTML(true);                                  //Set email format to HTML
                            $mail->Subject = $subject;
                            $mail->Body    = $message;
                            $mail->send();
                            $sql = "UPDATE usuarios set turnoSolicitado=true,tipo = ".$nivel." where email = '".$email."'";
                            $this->database->query($sql);
                            $sql = "Select id from usuarios where email = '".$email."'";
                            $result = $this->database->query($sql);
                            $result = mysqli_fetch_assoc($result);
                            $id_user = $result["id"];
                            $sql = "INSERT INTO reservas_medicas (id_centroMedico,id_usuario,fecha,hora) VALUES ('".$centro."','".$id_user."','".$fecha."',".$hora.")";
                            $this->database->query($sql);
                            return true;
                        } catch (Exception $e) {
                            return false;
                        }
            
            
    
        }

        public function buscarCentrosDisponibles(){
            $sql = "Select 1 from reservas_medicas";
            $datos = $this->database->queryResult($sql);
            if(!$datos){
                $sql= "Select nombre,id from centrosmedicos";
            }else{
                $sql= "Select nombre,id from centrosmedicos C where C.cantidadTurnos>(Select Count(id_reservaM) from reservas_medicas Group by id_centroMedico) ";
            }
            
            $datos = $this->database->queryResult($sql);
            return $datos;
        }

        public function turnoDisponible($fecha,$hora,$centro){
            
            $sql= "Select 1 as 'result' from reservas_medicas where (fecha ='".$fecha."' and hora=".$hora." and id_centroMedico = '".$centro."') or ((select Count(id_reservaM) from reservas_medicas where id_centroMedico = '".$centro."')>=(select cantidadTurnos from centrosmedicos where id='".$centro."'))";
            $datos = $this->database->query($sql);
            $datos = mysqli_fetch_assoc($datos);
            
            if (isset($datos['result'])){
                return false;
            }else{
                return true;
            }
            
        }

    }

?>