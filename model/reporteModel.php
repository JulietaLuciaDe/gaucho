<?php

class reporteModel
{
    private $database;

    public function __construct($database){
        $this->database=$database;
    }

    public function getCantidadDeVuelosSegunFecha($fecha){
        $query = "SELECT COUNT(fecha) FROM vuelos_confirmados WHERE fecha LIKE '".$fecha."'";
        return $this->database->queryResult($query);
    }

    public function getVuelosConfirmadosSegunFecha($fecha){
        $query = "SELECT * FROM vuelos_confirmados WHERE fecha LIKE '".$fecha."'";
        return $this->database->queryResult($query);
    }

    public function getDatosReservas(){
        $query =
                "SELECT vc.id_vuelo,                 vc.fecha,
                        d.descripcion as 'origen',   d2.descripcion as 'destino',
                        r.pago,                      r.cantidadAsientos,
                        r.TotalReserva,              u.nombre,
                        u.apellido,                  u.email
                FROM vuelos_confirmados vc 
                    JOIN reserva r      ON r.id_vuelofk=vc.id_vuelo
                    JOIN destinos d     ON vc.origen=d.id_destino
                    JOIN destinos d2    ON vc.destino=d2.id_destino
                    JOIN usuarios u     ON r.id_usuariofk=u.id
                WHERE r.pago = 1
                ORDER BY vc.id_vuelo    ASC";
        $datos = $this->database->queryResult($query);
        return $datos;
    }

    public function getCantidadDeAsientosPorVuelo(){
        $query =
            "SELECT SUM(cantidadAsientos) as 'cantidad',id_vuelofk as 'id'
            FROM reserva 
            WHERE pago=1   GROUP BY id_vuelofk";
        $datos = $this->database->queryResult($query);
        return $datos;
    }

    public function getFacturacionPorCliente(){
        $query =
            "SELECT SUM(r.TotalReserva)     as 'suma',
                                            u.nombre,
                                            u.apellido,
                                            u.email
            FROM reserva r 
            JOIN usuarios u     ON r.id_usuariofk=u.id
            GROUP BY u.id";
        $datos = $this->database->queryResult($query);
        return $datos;
    }
}