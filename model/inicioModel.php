<?php
    class InicioModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }
   

    public function buscar($busqueda){
        if(!empty($busqueda)){
            $where ="WHERE destino = '".$busqueda."' OR origen = '".$busqueda."'";
        }else{
            $where = " ";
        }
        $sqlTravel="SELECT * FROM vuelos ".$where;
        $datos = $this->database->queryResult($sqlTravel);
        
        return $datos;
    }
}

?>