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
        return $this->database->queryResult($query);
    }

    public function getVuelos()
    {
        return $this->database->query("SELECT * FROM vuelos");
    }
    //ver los id vuelos.

    public function getTour()
    {
        return $this->database->query("SELECT * FROM tour");
    }

    public function getVuelosED()
    {
        return $this->database->query("SELECT * FROM entredestinos");
    }


    public function crearVuelo($id_vuelo){
        $query = "INSERT INTO vuelos_confirmados
        SELECT id_vuelo,origen,destino,fecha FROM vuelos WHERE id_vuelo=".$id_vuelo;
        $this->database->query($query);
    }

    public function getVueloSeleccionado($id,$tabla){
        $query = "SELECT * FROM ".$tabla." WHERE id_vuelo=".$id;
        /* TODO: Una vez que esté la tabla "vuelos_confirmados", comprobar query correspondiente.
         * $query = "SELECT 1 FROM vuelos_confirmados WHERE id_vuelo=".$id;
         */
        return $this->database->queryResult($query);
    }

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
    }

}