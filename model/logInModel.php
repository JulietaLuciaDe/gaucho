<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    class LogInModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }


        public function iniciarSesion($email,$md5){
            $sqlUser = "SELECT autentificado FROM usuarios WHERE email = '" . $email. "' AND pass = '" . $md5 . "'";
            $qry = $this->database->query($sqlUser);
            $obj = mysqli_fetch_assoc($qry);

            if(isset($obj['autentificado'])){
                if($obj['autentificado']==1){
                    session_start();
                    $_SESSION["logueado"]=1;
                    //REGISTRADO Y AUTENTIFICADO --> LOGUEA OK
                    header("Location: index.php?module=inicio");
                }else{
                    //REGISTRADO PERO NO AUTENTIFICADO --> NO LOGUEA
                    header("Location: /login/unauthenticated");
                    
                }
            }else{
                header("Location: /login/notRegistered/email=$email");
            }
        }  
        
        public function autentificar($correo){
            $sql = "UPDATE usuarios Set autentificado = 1 Where email = '" . $correo. "'";
            $this->database->query($sql);
            header("Location: /login");

        }

        public function sendMailRecovery($dni, $email){
            $sqlUser = "SELECT email,user FROM usuarios WHERE email = '" . $email. "' OR dni = " . $dni . "";
            $qry = $this->database->query($sqlUser);
            $obj = mysqli_fetch_assoc($qry);
            if(isset($obj['email'])){
                $email  = $obj['email']; 
                $user =  $obj['user'];
                $subject = 'Recupero de Clave'; 
                $recoveryCode = md5($email);
                $message = "Hola, ".$user."!!!! 

                            Ingrese en el siguiente link para recuperar su clave:
                            http://localhost/login/recovery/email=$email&recoveryCode=$recoveryCode
                            
                            Si usted no ha solicitado la recuperación de su clave, ignore este mensaje";
                            
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
                                $mail->addAddress($email);     //Add a recipient
                                //$mail->isHTML(true);                                  //Set email format to HTML
                                $mail->Subject = $subject;
                                $mail->Body    = $message;
                                $mail->send();
                                return true;
                            } catch (Exception $e) {
                                return false;
                            }
            }else{
                return false;
            }
            

        }

        public function saveRecovery($pass,$email){
            $sql = "UPDATE usuarios Set pass = '" . $pass. "' Where email = '" . $email. "'";
            $this->database->query($sql);
            header("Location:/login");
        }
    }

?>