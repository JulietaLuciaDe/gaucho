<?php
    class LogInController{
        private $logInModel;
        private $printer;

        public function __construct($logInModel,$printer){
            $this->logInModel = $logInModel;
            $this->printer = $printer;
        }

        public function execute(){
            $this->printer->generateView('logInView.php');
        }

        public function registrado(){
            $title = "Registro exitoso!";
            $message = "Verifique su correo electrónico para activar la cuenta  </br> <a class='recovery' href='https://mail.google.com' target='blank'>Ir a mi correo</a>";
            $this->printer->generatePopUp($title,$message,'logInView.php');
            
        }

        public function validarSesion(){
            //AGREGAR VALIDACION DE CORREO ELECTRONICO SI VA A SEGUIR INGRESANDO CON ESO, SI VA A INGRESAR CON USUARIO VALIDAR LONGITUD
            //SI VA A INGRESAR CON USUARIO HAY QUE CAMBIARLO EN DONDE CORRESPONDA
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
            $this->printer->generatePopUp($title,$message,'logInView.php');
        }

        public function notRegistered(){
            $email=$_GET["email"];
            $title = "Usuario o clave incorrecta";
            $message = "intente nuevamente </br> <a class='recovery' href='/login/recuperar/email=$email&dni=1'>Olvidé mi clave</a>";
            $this->printer->generatePopUp($title,$message,'logInView.php');
        }

        public function exit(){
            session_unset();
            session_destroy();
            $this->printer->generateView('inicioView.php');
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
            $this->printer->generatePopUp($title,$message,'loginView.php');
        }

        public function recovery(){
            $email = $_GET['email'];
            $md5 = $_GET['recoveryCode'];
            $validLink = md5($email);
            if($validLink==$md5){
                $this->printer->generateRecovery($email);
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