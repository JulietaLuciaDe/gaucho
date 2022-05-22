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
            $message = "<div><h3>Registro exitoso!</h3></br><p>Verifique su correo electrónico para activar la cuenta</p></div>";
            $this->printer->generatePopUp($message,'logInView.php');
        }

        public function validarSesion(){
            $usuario = $_POST["usuario"];
            $password = $_POST["password"];
            if($this->logInModel->iniciarSesion($usuario,$password)){
                session_start();
                header("Location: index.php?module=inicio");
                exit();
            }else if(!$this->autentificado()){
                $message = "<div><h3>Cuenta aún no autenticada.</h3></br><p>Verifique nuevamente su correo electrónico para activar la cuenta.</p></div>";
                $this->printer->generatePopUp($message,'logInView.php');
            }else{
                header("Location: index.php?module=logIn");
                exit();
            }
        }

        public function autentificado(){
            $correo = $_GET["correo"];
            return $this->logInModel->autentificar($correo);
        }
    }



?>