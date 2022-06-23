<?php
    class InicioModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }
   

    public function buscarVuelos($busqueda){
        if(!empty($busqueda)){
            $where ="WHERE ".$busqueda;
        }else{
            $where = " ";
        }
        
        $sqlTravel="SELECT V.*,T.nombre as 'tipoEquipo',TV.descripcion as 'tVuelo' FROM VUELOS V JOIN EQUIPOS E ON V.id_equipo=E.matricula JOIN CARACT_EQUIPOS C ON E.modelo=C.caract_modelo JOIN TIPO_EQUIPO T ON C.id_tipo=T.id JOIN TIPOS_VUELO TV ON V.tipoVuelo = TV.id ".$where;
        $datos = $this->database->queryResult($sqlTravel);
        return $datos;
    }

    public function tiposVuelo(){
        $sqlTiposVuelo="SELECT Descripcion,Id as 'id_tipoVuelo' FROM tipos_Vuelo ";
        $datos = $this->database->queryResult($sqlTiposVuelo);
        return $datos;
    }
            //**Esto para mi deberia estar en el modulo de reservas */
//Modificar funcion o base de datos
  /*  public function registrarVuelo($origen,$destino,$tipoVuelo,$ida, $idaVuelta,$fechaIda,$fechaVuelta){
        $sql="INSERT INTO vuelos(origen,destino,fecha/*,tipoVuelo,ida,idaVuelta,fechaIda,fechaVuelta)
                VALUE ('".$origen."','".$destino."','".$tipoVuelo."','".$ida."','".$idaVuelta."','".$fechaIda."','".$fechaVuelta."')";
        return $this->database->query($sql);
    }*/
}

?>