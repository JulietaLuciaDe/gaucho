<?php
    class LogInController{
        private $logInModel;
        private $printer;

        public function __construct($logInModel,$printer){
            $this->logInModel = $logInModel;
            $this->printer = $printer;
        }

        public function execute($data = []){
            if(isset($_SESSION["logueado"]) && $_SESSION["logueado"]==1){
                $menu ="<a href='/logIn/exit'>Cerrar Sesion</a>";
              }else{
                $menu ="<a href='/registro'>Registrarse</a>
                <a href='/logIn'>Ingresar</a>";
              }
              $display = "style='display:block;'";
              $data = $data + ["menu"=>$menu,"display"=>$display];
            $this->printer->generateView("loginView.html",$data);
        }

        public function registrado(){
            $title = "Registro exitoso!";
            $message = "Verifique su correo electrónico para activar la cuenta  </br> <a class='recovery' href='https://mail.google.com' target='blank'>Ir a mi correo</a>";
            $display = "style='display:block;'";
            $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
            $this->execute($data);
            
        }

        public function validarSesion(){
            //AGREGAR VALIDACION DE CORREO ELECTRONICO 
            if(isset($_POST["usuario"]) && !empty($_POST["usuario"]) && isset($_POST["password"]) && !empty($_POST["password"])){
                $usuario = $_POST["usuario"];
                $password = $_POST["password"];
                $this->logInModel->iniciarSesion($usuario,$password);
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
            $display = "style='display:block;'";
            $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
            $this->execute($data);
        }

        public function notRegistered(){
            $email=$_GET["email"];
            $title = "Usuario o clave incorrecta";
            $message = "intente nuevamente </br> <a class='recovery' href='/login/recuperar/email=$email&dni=1'>Olvidé mi clave</a> </br> <a class='recovery' href='/registro'>Aún no estoy registrado</a>";
            $display = "style='display:block;'";
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
                $title="El mail ingresado no se encuentra registrado";
                $message="<a class='recovery' href='/registro' target='blank'>Registrarme</a>";
            }
            $display = "style='display:block;'";
            $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
            $this->execute($data);
        }

        public function recovery(){
            $email = $_GET['email'];
            $md5 = $_GET['recoveryCode'];
            $validLink = md5($email);
            if($validLink==$md5){
                $display = "style='display:none;'";
                $data=["email"=>$email,"recovery"=>true,"display"=>$display];
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