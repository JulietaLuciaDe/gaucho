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

        public function validarSesion(){
            $usuario = $_POST["usuario"];
            $password = $_POST["password"];
            if($this->logInModel->iniciarSesion($usuario,$password)){
                header("Location: index.php?module=inicio");
            }else{
                header("Location: index.php?module=logIn");
                exit();
            }
        }
        
    }



?>