<?php
include_once("view/popUpView.php");
include_once("view/recoveryView.php");
class Printer {

    public function __construct(){

    }

    public function generateView($content){
            include_once("view/header.php");
            include_once("view/navBar.php");
            include_once("view/".$content);
            include_once("view/footer.php");
    }

    public function generatePopUp($title,$message,$content){
        include_once("view/header.php");
        include_once("view/navBar.php");
        $popUp = new PopUp($title,$message);
        include_once("view/".$content);
        include_once("view/footer.php");
    }

    public function generateRecovery($email){
        include_once("view/header.php");
        include_once("view/navBar.php");
        $recovery= new Recovery($email);
        include_once("view/footer.php");
    }
}

?>