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
    
    /**
     * @param $fecha
     */
    public function getVuelosDia($fecha, $desde = NULL)
    {
        global $DIAS;
        
        $diaDeLaSemana = date('w', strtotime($fecha));
        
        $diaDeLaSemana = $DIAS["$diaDeLaSemana"];
        
        if ($desde) {
            return $this->database->query("SELECT * FROM suborbitales where dia = '$diaDeLaSemana' AND partida = '$desde' ORDER BY horario;");
        } else {
            return $this->database->query("SELECT * FROM suborbitales where dia = '$diaDeLaSemana' ORDER BY horario");
        }
    }
     

    public function generarReserva($datos)
    {
        $sql = "INSERT INTO suborbitales_reservas(fechayhora,desde,matricula,usuario,tipoAsiento,numeroAsiento,servicio)
                VALUES('" . $datos["fechayhora"] . "','" . $datos["desde"] . "','" . $datos["matricula"] .
            "'," . $datos["usuario"] . ",'" . $datos["tipoAsiento"] . "'," . $datos["numeroAsiento"] .
            ",'" . $datos["servicio"] . "' );";
        //var_dump($sql);
        return $this->database->insert($sql);
    }
   
    public function usuarioTienePasajeVuelo($fecha, $hora, $partida, $id_usuario)
    {
        $sql = "SELECT * FROM suborbitales_reservas where fechayhora = '$fecha $hora' and desde = '$partida' and usuario = $id_usuario;";
        return $this->database->query($sql);
    }

       public function tipoUsuario($idUsuario)
    {
        $sql = "SELECT tipo FROM usuario where id = $idUsuario;";
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
    
    public function asientosReservados($fecha, $hora, $partida, $matricula)
    {
        $sql = "SELECT tipoAsiento,numeroAsiento 
        FROM suborbitales_reservas 
        WHERE fechayhora = '$fecha $hora' 
        and desde = '$partida'
        and matricula = '$matricula'";
        return $this->database->query($sql);
    }
}