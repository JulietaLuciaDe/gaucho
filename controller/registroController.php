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
            $name = $_POST["nombre"];
            $lastName = $_POST["apellido"];
            $dni = $_POST["dni"];
            $email = $_POST["email"];
            $user = $_POST["user"];
            $pass = $_POST["clave"];

            $this->registroModel->registrar($name,$lastName,$dni,$email,$user,$pass);
        }

        public function validarRegistro(){
            $email = $_GET['email'];
            $hash = $_GET['hash'];
            $this->registroModel->validarRegistro($email,$hash);
        }

        public function duplicate(){
            $email = $_GET['email'];
            $dni = $_GET['dni'];
            $title="DNI o email ya registrados";
            //ESTE LINK AGREGARLO EN EL LOGIN
            $message="<a class='recovery' href='index.php?module=login&method=recuperar&email=$email&dni=$dni'>Olvidé mi clave</a>";
            $this->printer->generatePopUp($title,$message,'registroView.php');
        }
    }



?>