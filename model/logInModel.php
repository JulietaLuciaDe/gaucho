<?php

    class LogInModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }

        public function iniciarSesion($usuario,$md5){
            $sqlUser = "SELECT 1 FROM usuarios WHERE user = '" . $usuario. "' AND pass = '" . $md5 . "'";
            $qry = $this->database->query($sqlUser);
            $obj = mysqli_fetch_assoc($qry);

            if(isset($obj['1'])){
                session_start();
                $_SESSION["logueado"]=true;
                return true;
            }else{
                return false;
            }
        }
    }

?>