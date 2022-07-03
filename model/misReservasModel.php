<?php
    class MisReservasModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }
   
    public function misReservasImpagas(){
        $id_usuario=$_SESSION['id'];
        $sqlmisReservas="select fechaReserva, horaReserva, d.descripcion as 'origen' , d.descripcion as destino , monedaReserva , TotReservaMoneda from reserva r INNER JOIN destinos d on r.origenReserva=d.id_destino  WHERE id_usuariofk= ".$id_usuario." and pago=0" ;
        $datos = $this->database->queryResult($sqlmisReservas);
        return $datos;
    }
    public function misReservasCheckIn(){
        $id_usuario=$_SESSION['id'];
        $sqlmisReservas="select fechaReserva, horaReserva, d.descripcion as 'origen' , d.descripcion as destino , monedaReserva , TotReservaMoneda from reserva r INNER JOIN destinos d on r.origenReserva=d.id_destino WHERE id_usuariofk= ".$id_usuario." and checkIn=0 and pago=1";
        $datos = $this->database->queryResult($sqlmisReservas);
        return $datos;
    }
  
    public function misReservasCheckeadas(){
        $id_usuario=$_SESSION['id'];
        $sqlmisReservas="select fechaReserva, horaReserva, d.descripcion as 'origen', d.descripcion as destino , monedaReserva , TotReservaMoneda from reserva r INNER JOIN destinos d on r.origenReserva=d.id_destino WHERE id_usuariofk= ".$id_usuario." and checkIn=1 and pago=1";
        $datos = $this->database->queryResult($sqlmisReservas);
        return $datos;
    } 

      
    public function eliminarReserva($idReserva){
        $id_usuario=$_SESSION['id'];
        $sql = "delete from reserva where id = ". $idReserva;
        return $this->database->delete($sql);
    } 
}

?>