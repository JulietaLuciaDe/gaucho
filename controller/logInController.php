<?php
    class LogInController{
        private $logInModel;
        private $printer;

        public function __construct($logInModel,$printer){
            $this->logInModel = $logInModel;
            $this->printer = $printer;
        }

        public function execute($data = []){
            
                $menu ="<a href='/registro'>Registrarse</a>
                <a href='/logIn'>Ingresar</a>";
          

              if(isset($data["recovery"])){
                $data["display"] = "d-none";
              }else{
                $data["display"] = "d-block";
              }
              $data["menu"] = $menu;            
              $this->printer->generateView("loginView.html",$data);
        }

        public function registrado(){
            $title = "Registro exitoso!";
            $message = "Verifique su correo electrónico para activar la cuenta  </br> <a class='recovery' href='https://mail.google.com' target='blank'>Ir a mi correo</a>";
                $display = "d-block";
            $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
            $this->execute($data);
            
        }

        public function validarSesion(){
            if(ValidatorHelper::validarSeteadoYNoVacio($_POST["usuario"]) && 
               ValidatorHelper::validarSeteadoYNoVacio($_POST["password"])){
                $usuario = $_POST["usuario"];
                $password = $_POST["password"];
                $obj = $this->logInModel->iniciarSesion($usuario,$password);
                if(isset($obj['autentificado'])){
                    if($obj['autentificado']==1){
                        //REGISTRADO Y AUTENTIFICADO --> LOGUEA OK
                        session_start();
                        $_SESSION["logueado"]=1;
                        //ESTE ES EL MAIL
                        $_SESSION["usuario"]=$_POST["usuario"];
                        $_SESSION["user"]=$obj['user'];
                        $_SESSION["id"]=$obj['id'];
                        $_SESSION["nivel"]=$obj['tipo'];
                        $_SESSION["tipoUser"]=$obj['tipoUsuario'];
                        //LOGUEA OK PERO NO TIENE TURNO MÉDICO
                        if($_SESSION["nivel"]==""){
                            header("Location: /registro/solicitarTurno/email=".$_SESSION['usuario']);
                            exit();
                        }else{
                            //LOGUEA OK Y TIENE TURNO MÉDICO
                            header("Location: /inicio");
                            exit();
                        }
                    }else{
                        //REGISTRADO PERO NO AUTENTIFICADO --> NO LOGUEA
                        header("Location: /login/unauthenticated");
                        exit();
                    }
                }else{
                    //NO REGISTRADO -->  NO LOGUEA
                   header("Location: /login/notRegistered/email=".$usuario);
                }
            }else{
                header("Location: /login");
            }
            
             
        }

        public function autentificado(){
            $correo = $_GET["correo"];
            $this->logInModel->autentificar($correo);
        }

        public function unauthenticated(){
            $title = "Registro incompleto";
            $message = "Verifique su correo electrónico para activar la cuenta  </br> <a class='recovery' href='https://mail.google.com' target='blank'>Ir a mi correo</a>";
                $display = "d-block";
            $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
            $this->execute($data);
        }

        public function notRegistered(){
            $email=$_GET["email"];
            $title = "Usuario o clave incorrecta";
            $message = "<p>intente nuevamente</p> </br> <a class='recovery' href='/login/recuperar/email=$email&dni=1'>Olvidé mi clave</a><a class='recovery' href='/registro'>Aún no estoy registrado</a>";
            $display = "d-block";
            $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
            $this->execute($data);
        }

        public function exit(){
            session_unset();
            session_destroy();
            header("Location: /incio");
        }

        public function recuperar(){
            $email = $_GET['email'];
            $dni = $_GET['dni'];
            if($this->logInModel->sendMailRecovery($dni,$email)){
                $title="Verifique su correo electrónico";
                $message="Hemos enviado un link de recupero de clave </br> <a class='recovery' href='https://mail.google.com' target='blank'>Ir a mi correo</a>";
            }else{
                $title="Ha ocurrido un problema! verifique si está registrado";
                $message="<a class='recovery' href='/registro' target='blank'>Registrarme</a>";
            }
            $display = "d-block";
            $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
            $this->execute($data);
        }

        public function recovery(){
            $email = $_GET['email'];
            $md5 = $_GET['recoveryCode'];
            $validLink = md5($email);
            if($validLink==$md5){
                $data=["email"=>$email,"recovery"=>true];
                $this->execute($data);
            }else{
                header("Location: /inicio");
            }
            
            
        }

        public function saveRecovery(){
            if(isset($_POST["usuario"]) && !empty($_POST["usuario"]) && isset($_POST["password"]) && !empty($_POST["password"])){
                $email = $_POST["usuario"];
                $pass = $_POST["password"];
                $this->logInModel->saveRecovery($pass,$email);
            }else{
                header("Location: /login");
            }

        }
    }

?>