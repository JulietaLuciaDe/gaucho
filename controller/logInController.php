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

        public function registrado(){
            $message = 'Usuario Registrado';
            $this->printer->generatePopUp($message,'logInView.php');
            
        }

        

        
    }



?>