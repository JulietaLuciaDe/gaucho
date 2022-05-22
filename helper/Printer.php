<?php

class Printer {

    public function __construct(){

    }

    public function generateView($content){
            include_once("view/header.php");
            include_once("view/navBar.php");
            include_once("view/".$content);
            include_once("view/footer.php");
    }

    public function generatePopUp($message,$content){
        include_once("view/header.php");
        include_once("view/navBar.php");
        include_once("view/popUpView.php");
        include_once("view/".$content);
        include_once("view/footer.php");
    }
}

?>