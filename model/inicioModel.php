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

        if (empty($datos)){
            $sqlTravel="SELECT * FROM vuelos ";
            $datos = $this->database->queryResult($sqlTravel);
        }
        return $datos;
    }
//Modificar funcion o base de datos
    public function registrarVuelo($origen,$destino,$tipoVuelo,$ida, $idaVuelta,$fechaIda,$fechaVuelta){
        $sql="INSERT INTO vuelos(origen,destino,fecha/*,tipoVuelo,ida,idaVuelta,fechaIda,fechaVuelta*/)
                VALUE ('".$origen."','".$destino."','".$tipoVuelo."','".$ida."','".$idaVuelta."','".$fechaIda."','".$fechaVuelta."')";
        return $this->database->query($sql);
    }
}

?>