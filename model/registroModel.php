<?php
    class RegistroModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }


        public function registrar($name,$lastName,$dni,$user,$pass){
            $existe = $this->userExistente($user);
            if(!$existe){
                $sql = "INSERT INTO usuarios (nombre,apellido,dni,user,pass) VALUES ('".$name."','".$lastName."','".$dni."','".$user."','".$pass."')";
                $this->database->query($sql);
                header("Location: index.php?module=logIn&method=registrado");
                exit();
            }else{
                header("Location: index.php?module=registro&method=noregistrado");
                exit();
            }
           
        
        }

        public function userExistente($user){
            $sql= "SELECT 1 FROM usuarios where user ='".$user."'";
            $result = $this->database->query($sql);
            $result = mysqli_fetch_assoc($result);
            return (isset($result["1"]))? true : false;
            
        }
    }

?>