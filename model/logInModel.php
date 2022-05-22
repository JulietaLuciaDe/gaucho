<?php

    class LogInModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }

        public function iniciarSesion($usuario,$md5){
            $sqlUser = "SELECT 1 FROM usuarios WHERE username = '" . $usuario. "' AND password = '" . $md5 . "'";
            $this->database->query("");
        }
    }

?>