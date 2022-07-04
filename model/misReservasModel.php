<?php
    class MisReservasModel{
        private $database;

        public function __construct($database){
            $this->database=$database;
        }
   
    public function misReservasImpagas(){
        $id_usuario=$_SESSION['id'];
        $sqlmisReservas="SELECT R.id_vuelofk,R.id_usuariofk,R.fechaReserva,R.horaReserva,R.TotalReserva, D.descripcion as 'origen' , DE.descripcion as 'destino' FROM RESERVA R JOIN DESTINOS D ON R.origenReserva = D.id_destino JOIN DESTINOS DE ON R.destinoReserva = DE.id_destino WHERE id_usuariofk=".$id_usuario." and pago=0";
        $datos = $this->database->queryResult($sqlmisReservas);
        return $datos;
    }
    public function misReservasCheckIn(){
        $id_usuario=$_SESSION['id'];
        $sqlmisReservas="SELECT R.id,R.fechaReserva,R.horaReserva,R.TotReservaMoneda,R.monedaReserva, D.descripcion as 'origen' , DE.descripcion as 'destino' FROM RESERVA R JOIN DESTINOS D ON R.origenReserva = D.id_destino JOIN DESTINOS DE ON R.destinoReserva = DE.id_destino WHERE id_usuariofk=".$id_usuario." and pago=1 AND checkin =0";
        $datos = $this->database->queryResult($sqlmisReservas);
        return $datos;
    }
  
    public function misReservasCheckeadas(){
        $id_usuario=$_SESSION['id'];
        $sqlmisReservas="SELECT R.fechaReserva,R.horaReserva,R.TotReservaMoneda,R.monedaReserva, D.descripcion as 'origen' , DE.descripcion as 'destino' FROM RESERVA R JOIN DESTINOS D ON R.origenReserva = D.id_destino JOIN DESTINOS DE ON R.destinoReserva = DE.id_destino WHERE id_usuariofk=".$id_usuario." and pago=1 AND checkin =1";
        $datos = $this->database->queryResult($sqlmisReservas);
        return $datos;
    } 

      
    public function eliminarReserva($idReserva){
        $id_usuario=$_SESSION['id'];
        $sql = "delete from reserva where id = ". $idReserva;
        return $this->database->delete($sql);
    } 

    public function getDatosAbordaje($id_reserva){
        $query="SELECT R.id,R.fechaReserva,R.horaReserva,R.TotReservaMoneda,R.monedaReserva,R.id_Vuelofk,R.cantidadAsientos, D.descripcion as 'origen' , DE.descripcion as 'destino', concat(U.Nombre,' ', U.Apellido) as 'cliente' FROM RESERVA R JOIN DESTINOS D ON R.origenReserva = D.id_destino JOIN DESTINOS DE ON R.destinoReserva = DE.id_destino JOIN USUARIOS U ON R.id_usuariofk = U.id WHERE R.id=".$id_reserva;
        $datos = $this->database->queryResult($query);
        return $datos;
    } 
}

?>