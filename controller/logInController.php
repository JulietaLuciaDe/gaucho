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
            $message = "Verifique su correo electrónico para activar la cuenta";
            $this->printer->generatePopUp($title,$message,'logInView.php');
            
        }

        public function validarSesion(){
            $usuario = $_POST["usuario"];
            $password = $_POST["password"];
            $this->logInModel->iniciarSesion($usuario,$password);
             
        }

        public function autentificado(){
            $correo = $_GET["correo"];
            $this->logInModel->autentificar($correo);
        }

        public function unauthenticated(){
            $title = "Registro incompleto";
            $message = "Verifique su correo electrónico para activar la cuenta";
            $this->printer->generatePopUp($title,$message,'logInView.php');
        }

        public function notRegistered(){
            $title = "Usuario inexistente";
            $message = "El correo ingresado no está registrado";
            $this->printer->generatePopUp($title,$message,'logInView.php');
        }

        public function exit(){
            session_unset();
            session_destroy();
            $this->printer->generateView('inicioView.php');
        }

    }

?>