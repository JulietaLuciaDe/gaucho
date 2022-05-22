<?php
    class RegistroController{
        private $registroModel;
        private $printer;

        public function __construct($registroModel,$printer){
            $this->registroModel = $registroModel;
            $this->printer = $printer;
        }

        public function execute(){
            $this->printer->generateView('registroView.php');
        }

        public function registrar(){
            $name = $_POST["name"];
            $lastName = $_POST["lastName"];
            $dni = $_POST["dni"];
            $user = $_POST["user"];
            $pass = $_POST["pass"];

            $this->registroModel->registrar($name,$lastName,$dni,$user,$pass);

            
        }

        public function validarRegistro(){
            $email = $_GET['email'];
            $hash = $_GET['hash'];
            $this->registroModel->validarRegistro($email,$hash);
        }
    }



?>