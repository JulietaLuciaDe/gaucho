<?php
    class LogInModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }


        public function iniciarSesion($usuario,$md5){
            $sqlUser = "SELECT autentificado FROM usuarios WHERE user = '" . $usuario. "' AND pass = '" . $md5 . "'";
            $qry = $this->database->query($sqlUser);
            $obj = mysqli_fetch_assoc($qry);

            if(isset($obj['autentificado'])){
                if($obj['autentificado']==1){
                    session_start();
                    $_SESSION["logueado"]=1;
                    //REGISTRADO Y AUTENTIFICADO --> LOGUEA OK
                    header("Location: index.php?module=inicio");
                }else{
                    //REGISTRADO PERO NO AUTENTIFICADO --> NO LOGUEA
                    header("Location: index.php?module=login&method=unauthenticated");
                    
                }
            }else{
                header("Location: index.php?module=login&method=notRegistered");
            }
        }  
        
        public function autentificar($correo){
            $sql = "UPDATE usuarios Set autentificado = 1 Where user = '" . $correo. "'";
            $this->database->query($sql);
            header("Location: index.php?module=login");

        }
    }

?>