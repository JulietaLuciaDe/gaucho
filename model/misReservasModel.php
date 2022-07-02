<?php
    class MisReservasModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }
   
    public function misReservas(){
        $id_usuario=$_SESSION['id'];
        $sqlmisReservas="select id, tipoAsiento, tramos from reserva r WHERE id_usuariofk= '".$id_usuario."'";
        $datos = $this->database->queryResult($sqlmisReservas);
        return $datos;
    }

    public function tramoOrigen($tramos){
        $querySelect = "SELECT tramos FROM reserva WHERE ....";
        $string = $this->database->queryResult($querySelect);
        $string = substr($string[0]['tramo'], 1);
        $arrayDeTramos = explode ( '|' , $string);
    }

    public function tramoDestino($tramos){

    }
}

?>