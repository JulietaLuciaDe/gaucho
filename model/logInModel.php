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

            if(isset($obj['autentificado']) && $obj['autentificado']==1){
               // echo"SI";
               // session_start();
                //$_SESSION["logueado"]=true;
                return true;
            }else{
               // echo"NO";
                return false;
            }
        }  
        
        public function autentificar($correo){
            $sql = "UPDATE usuarios Set autentificado = 1 Where user = '" . $correo. "'";
            return $this->database->query($sql);
        }
    }

?>