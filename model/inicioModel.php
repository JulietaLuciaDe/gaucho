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
        
        $sqlTravel="SELECT V.*,T.nombre as 'tipoEquipo',TV.descripcion as 'tVuelo' FROM VUELOS V JOIN EQUIPOS E ON V.id_equipofk2=E.matricula JOIN CARACT_EQUIPOS C ON E.modelo=C.caract_modelo JOIN TIPO_EQUIPO T ON C.id_tipo=T.id JOIN TIPOS_VUELO TV ON V.tipoVuelofk1 = TV.id ".$where;
        $datos = $this->database->queryResult($sqlTravel);
        return $datos;
    }

    public function tiposVuelo(){
        $sqlTiposVuelo="SELECT Descripcion,Id as 'id_tipoVuelo' FROM tipos_Vuelo ";
        $datos = $this->database->queryResult($sqlTiposVuelo);
        return $datos;
    }
}

?>