<?php
// ver modificar los sql bien con los datos que tenemos nosotros!
class reservaVueloModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUsuario($email){
        $query = "SELECT id, nombre, apellido, dni, email FROM usuarios WHERE email='".$email."'";
        $usuario = $this->database->queryResult($query);
        return $usuario;
    }

   

    public function crearVuelo($id_vuelo,$tramo){
        $query = "INSERT INTO vuelos_confirmados (id_vuelo,fecha,h_partida,id_equipofk,origen,destino,id_tipoVuelofk)
        SELECT id_vuelo,fecha,h_partida,id_equipofk2,origen,destino,tipoVuelofk1 FROM vuelos WHERE id_vuelo=".$id_vuelo;
        $this->database->query($query);
        $this->asignarTramoVuelo($id_vuelo,$tramo);
    }

    public function verificarDestinosVuelo($id_vuelo){
        $querySelect = "SELECT id_destino FROM destino WHERE id_destino>= (Select id_tipoVuelofk from vuelos_confirmados where id_vuelo = ".$id_vuelo.")";
       return $this->database->queryResult($querySelect);


    }

    public function getDestinosyTipoVuelo($id_vuelo){
        $querySelect = "SELECT origen,destino,tipovuelofk1 FROM vuelos WHERE id_vuelo = ".$id_vuelo;
       return $this->database->queryResult($querySelect);
    }

    public function getTramoVuelo($id_vuelo){
        $querySelect = "SELECT tramo FROM vuelos_confirmados WHERE id_vuelo = ".$id_vuelo;
        $string = $this->database->queryResult($querySelect);
        return explode ( '|' , $string[0]['tramo']);

    }

    


    public function getDatosDestino($id_destino){
        $query = "SELECT id_destino,descripcion FROM destinos WHERE id_destino='".$id_destino."'";
       return $this->database->queryResult($query);
    }

    

    public function asignarTramoVuelo($id_vuelo,$tramo){
        $query = "UPDATE vuelos_confirmados set tramo = '".$tramo."' where id_vuelo = ".$id_vuelo;
        $this->database->query($query);
    }



    public function getVueloSeleccionado($id,$tabla){
        $query = "SELECT * FROM ".$tabla." WHERE id_vuelo=".$id;
        /* TODO: Una vez que esté la tabla "vuelos_confirmados", comprobar query correspondiente.
         * $query = "SELECT 1 FROM vuelos_confirmados WHERE id_vuelo=".$id;
         */
        return $this->database->queryResult($query);
    }

    public function getDatosVuelo($id){
        $query = "SELECT V.*,T.nombre as 'tipoEquipo',TV.descripcion as 'tVuelo', D.descripcion as 'destinoOrigen' , DE.descripcion as 'destinoDestino' FROM VUELOS_CONFIRMADOS V JOIN EQUIPOS E ON V.id_equipofk=E.matricula JOIN CARACT_EQUIPOS C ON E.modelo=C.caract_modelo JOIN TIPO_EQUIPO T ON C.id_tipo=T.id JOIN TIPOS_VUELO TV ON V.ID_tipoVuelofk = TV.id JOIN DESTINOS D ON V.origen = D.id_destino JOIN DESTINOS DE ON V.destino = DE.id_destino WHERE id_vuelo=".$id;
        return $this->database->queryResult($query);
    }

    public function getServicios(){
        $query = "SELECT id as 'id_servicio',descripcion as 'descripcion_servicio',precio as 'precio_servicio' FROM servicios";
        return $this->database->queryResult($query);
    }


    /*
    public function generarReserva($datos)
    {

        //insert en las reservas.
        $sql = "INSERT INTO suborbitales_reservas(fechayhora,desde,matricula,usuario,tipoAsiento,numeroAsiento,servicio)
                VALUES('" . $datos["fechayhora"] . "','" . $datos["desde"] . "','" . $datos["matricula"] .
            "'," . $datos["usuario"] . ",'" . $datos["tipoAsiento"] . "'," . $datos["numeroAsiento"] .
            ",'" . $datos["servicio"] . "' );";
        //var_dump($sql);
        return $this->database->insert($sql);
    }

    public function cargarCantidadPasajeros($fecha, $hora, $partida, $id_usuario)
    {
        $sql = "SELECT * FROM suborbitales_reservas where fechayhora = '$fecha $hora' and desde = '$partida' and usuario = $id_usuario;";
        return $this->database->query($sql);
    }

    public function guardarPago($datos)
    {
        $sql = "INSERT INTO suborbitales_pagos(fechayhora,desde,matricula,usuario,tipoAsiento,numeroAsiento,servicio,id_preferencia)
                VALUES('" . $datos["fecha"] . " " . $datos["hora"] . "','" . $datos["partida"] . "','" . $datos["matricula"] .
            "'," . $datos["id_usuario"] . ",'" . $datos["tipo_asiento"] . "'," . $datos["num_asiento"] .
            ",'" . $datos["servicio"] . "','" . $datos["preferencia"] . "' );";

        return $this->database->insert($sql);
    }

    public function eliminarPagoRealizado($preferencia)
    {
        $sql = "DELETE FROM suborbitales_pagos where id_preferencia = '$preferencia';";
        return $this->database->delete($sql);
    }*/

}