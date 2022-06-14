<?php
    class RegistroController{
        private $registroModel;
        private $printer;

        public function __construct($registroModel,$printer){
            $this->registroModel = $registroModel;
            $this->printer = $printer;
        }

        public function execute($data = []){
            if(isset($_SESSION["logueado"]) && $_SESSION["logueado"]==1){
                $menu ="<a href='/logIn/exit'>Cerrar Sesion</a>";
              }else{
                $menu ="<a href='/registro'>Registrarse</a>
                <a href='/logIn'>Ingresar</a>";
              }
              $data += ["menu"=>$menu];
            $this->printer->generateView('registroView.html',$data);
        }

        public function registrar(){
            //TODO: Preguntar al profe cómo podría usar un corte de control para las validaciones.
            if(((   ValidatorHelper::validacionDeTexto($_POST["nombre"],11)&&
                    ValidatorHelper::validacionDeTexto($_POST["apellido"],11))&&
            (       ValidatorHelper::validacionDeNumeros($_POST['dni'],11)&&
                    ValidatorHelper::validacionDeTexto($_POST['email'],50)))&&
            (       ValidatorHelper::validacionDeTexto($_POST['user'],21)&&
                    ValidatorHelper::validacionDeTexto($_POST['clave'],21))){
                $name = $_POST["nombre"];
                $lastName = $_POST["apellido"];
                $dni = $_POST["dni"];
                $email = $_POST["email"];
                $user = $_POST["user"];
                $pass = $_POST["clave"];

                $this->registroModel->registrar($name,$lastName,$dni,$email,$user,$pass);
            }else{
                header("Location: /registro");
            }
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
            $message="<a class='recovery' href='/login/recuperar/email=$email&dni=$dni'>Olvidé mi clave</a>";
            $data = ["popUp" => true,"title"=> $title,"message"=>$message];
            $this->execute($data);
        }
    }



?>