<?php
// ver modificar los sql bien con los datos que tenemos nosotros! 
class VuelosModel
{
    private $database;
    
    public function __construct($database)
    {
        $this->database = $database;
    }
    
    public function getVuelos()
    {
        return $this->database->query("SELECT * FROM vuelos WHERE id_tipoVuelo= 1");
        
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
    
  
    public function crearVuelo ($id_vuelo){
        //primero buscar el vuelo en la tabla vuelo y dsp insertarlo en la tabla de vuelos 
        //confirmados con los datos faltantes .
        // $sql = "INSERT INTO vuelos()
        // VALUES('" . $datos["fecha"] . "','" . $datos["origen"] . "','" . $datos["partida"] ");";
        //var_dump($sql);
        return $this->database->insert($sql);
        $vuelo = mysqli_fetch_assoc($query);
        return $vuelo;
    }

    public function getVueloSeleccionado($id){
        $query = "SELECT * FROM vuelos_confirmados WHERE id_vuelo=.$id";
        $this->database->query($query);
        $vuelo = mysqli_fetch_assoc($query);
        return $vuelo;
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