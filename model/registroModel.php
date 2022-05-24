<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
    class RegistroModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }


        public function registrar($name,$lastName,$dni,$user,$pass){
            $existe = $this->userExistente($user);
            if(!$existe){
                $sql = "INSERT INTO usuarios (nombre,apellido,dni,user,pass) VALUES ('".$name."','".$lastName."','".$dni."','".$user."','".$pass."')";
                $this->database->query($sql);
                $confirmation = $this->sendConfirmationMail($user);
                if($confirmation){
                    header("Location: index.php?module=logIn&method=registrado");
                    exit();
                }else{
                    header("Location: index.php?module=logIn&method=noregistrado");
                    exit();
                }
                
            }else{
                header("Location: index.php?module=registro&method=noregistrado");
                exit();
            }
           
        
        }

        private function userExistente($user){
            $sql= "SELECT 1 FROM usuarios where user ='".$user."'";
            $result = $this->database->query($sql);
            $result = mysqli_fetch_assoc($result);
            return (isset($result["1"]))? true : false;
            
        }

        private function sendConfirmationMail($user){
            $to  = $user; 
            $hash = md5($user);
            $subject = 'Registro en GauchoRocket'; 
            $message = "Aqui comienza su mejor experiencia!
                        Su cuenta ha sido creada. Por favor, ingrese al siguiente link para activarla:
                        localhost/index.php?module=registro&method=validarRegistro&email=$user&hash=$hash";
                        
                        $mail = new PHPMailer(true);
                        
                        try {
                            $mail->SMTPDebug = 0;                      //Enable verbose debug output
                            $mail->isSMTP();                                            //Send using SMTP
                            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                            $mail->Username   = 'gauchorocketargentina@gmail.com';                     //SMTP username
                            $mail->Password   = 'gaucho2022';                               //SMTP password
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
               header("Location: index.php?module=logIn&method=autentificado&correo=$email");
            }else{
               header("Location: index.php?module=inicio");
            }
    
        }
    }

?>