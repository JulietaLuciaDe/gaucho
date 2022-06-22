<?php
    class RegistroController{
        private $registroModel;
        private $printer;

        public function __construct($registroModel,$printer){
            $this->registroModel = $registroModel;
            $this->printer = $printer;
        }

        public function execute($data = [],$view ='registroView.html' ){
            if(isset($_SESSION["logueado"]) && $_SESSION["logueado"]==1){
                $menu ="<p>Hola, ".$_SESSION['user']."</p>
                    <a href='/logIn/exit'>Cerrar Sesion</a>";
              }else{
                $menu ="<a href='/registro'>Registrarse</a>
                <a href='/logIn'>Ingresar</a>";
              }
              
              if(isset($data["turno"])){
                $data["display"] = "d-none";
              }else{
                $data["display"] = "d-block";
              }
              $data += ["menu"=>$menu];
            $this->printer->generateView($view,$data);
        }

        public function registrar(){
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

                $status = $this->registroModel->registrar($name,$lastName,$dni,$email,$user,$pass);
                if( $status == "registrado"){
                    header("Location:/logIn/".$status);
                    exit();}
                else if($status == "noregistrado"){
                    header("Location:/logIn/".$status);
                    exit();
                }if($status== "email=".$email."&dni=".$dni){
                    header("Location:/registro/duplicate/".$status);
                    exit();
                }
            }else{
                header("Location: /registro");
                exit();
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
            $display = "d-block";
            $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
            $this->execute($data);
        }

        public function solicitarTurno(){
            $email = $_SESSION['usuario'];
            $result = $this->registroModel->buscarCentrosDisponibles();
            
                if(isset($_SESSION["logueado"]) && $_SESSION["logueado"]==1){
                    if($this->registroModel->TurnoSolicitado($email)){
                        $title="Turno ya solicitado";
                        $message="Usted ya ha solicitado un turno. Si desea cancelarlo o modificarlo esperamos su correo en <a href='mailto:gauchorocketargentina@gmail.com' style='color:black;'>gauchorocketargentina@gmail.com</a>";
                        $display = "d-block";
                        $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
                    }else{
                        $display = "d-block";
                        $data = ["turno" => true,"email"=> $email,"display"=>$display];
                    }

                
            }else{
                header("Location: /inicio");
                exit();
            }
            $data=$data+["centros"=>$result] ;
            $this->execute($data);
        }

        public function validarTurnoMedico(){
            
            $fecha = $_POST['dia'];
            $hora = $_POST['hora'];
            $email = $_POST['email'];
            $centro = $_POST['centroMedico'];
            
           
            if($this->registroModel->turnoDisponible($fecha,$hora,$centro)){

              if($this->registroModel->guardarTurno($email,$fecha,$hora,$centro)){
                $title="Turno tomado con éxito";
                $message="Hemos enviado los resultados a su correo electrónico.
                          Debe volver a loguearse para buscar viajes.";
                $data = ["popUp" => true,"title"=> $title,"message"=>$message];
                $this->execute($data,'loginView.html');
              }else{
                echo "ha ocurrido un problema";
              }
              
            }else{
                $email = $_SESSION['usuario'];
                $title="Turno no disponible";
                $message="El turno solicitado ya se encuentra tomado, por favor seleccione otro";
                $result = $this->registroModel->buscarCentrosDisponibles();
                
                $data = ["popUp" => true,"title"=> $title,"message"=>$message,"turno"=>true,"email"=> $email];
                $data=$data+["centros"=>$result] ;
                $this->execute($data);
            }
            
            
        }

    }




?>