<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        $string = substr($string[0]['tramo'], 1);
        return explode ( '|' , $string);
    }

    public function getDatosDestino($id_destino){
        $query = "SELECT id_destino,descripcion FROM destinos WHERE id_destino='".$id_destino."'";
       return $this->database->queryResult($query);
    }

    public function asignarTramoVuelo($id_vuelo,$tramo){
        $query = "UPDATE vuelos_confirmados set tramo = '".$tramo."' where id_vuelo = ".$id_vuelo;
        $this->database->query($query);
    }

    public function validarVueloYaReservado($vuelo){

        $query = "SELECT 1 from reserva where id_vuelofk = ".$vuelo. " and id_usuariofk = ".$_SESSION["id"]; 
        $result = $this->database->queryResult($query);
        if(!empty($result)){
            return false;
        }else{
            return true;
        }
    }

    public function getVueloSeleccionado($id,$tabla){
        $query = "SELECT * FROM ".$tabla." WHERE id_vuelo=".$id;
        return $this->database->queryResult($query);
    }

    public function getDatosVuelo($id){
        $query = "SELECT V.*,T.nombre as 'tipoEquipo',TV.descripcion as 'tVuelo',TV.id as 'idtVuelo', D.descripcion as 'destinoOrigen' , DE.descripcion as 'destinoDestino',D.id_destino as 'IDdestinoOrigen' , DE.id_destino as 'IDdestinoDestino' FROM VUELOS_CONFIRMADOS V JOIN EQUIPOS E ON V.id_equipofk=E.matricula JOIN CARACT_EQUIPOS C ON E.modelo=C.caract_modelo JOIN TIPO_EQUIPO T ON C.id_tipo=T.id JOIN TIPOS_VUELO TV ON V.ID_tipoVuelofk = TV.id JOIN DESTINOS D ON V.origen = D.id_destino JOIN DESTINOS DE ON V.destino = DE.id_destino WHERE id_vuelo=".$id;
        return $this->database->queryResult($query);
    }

    public function getServicios(){
        $query = "SELECT id as 'id_servicio',descripcion as 'descripcion_servicio',precio as 'precio_servicio' FROM servicios";
        return $this->database->queryResult($query);
    }

    public function getCabinasDisponibles($vuelo){
        $query = "SELECT tiposCabina from caract_equipos CE JOIN EQUIPOS E ON CE.caract_modelo=E.modelo JOIN VUELOS_CONFIRMADOS V ON V.id_equipofk=E.matricula where V.id_vuelo = ".$vuelo;
        $result = $this->database->queryResult($query);
        $result = $result[0]['tiposCabina'];
        $query1 = "SELECT id_cabina ,descripcion as 'nombre_cabina',precio as 'precio_cabina' FROM tipo_cabina where id_cabina IN (".$result.")"; 
        $result = $this->database->queryResult($query1);
        return $result;
    }

   public function sendMailReservadoOPagado($reserva,$tipoMail){

        if($tipoMail!='pagado'){
            $reserva = $reserva[0]['id'];
        }
        
        $reservaData = $this->getDatosReserva($reserva);
        $email = $_SESSION["usuario"];
        if($tipoMail=='pagado'){
            $message = "El pago de su reserva ha sido aprobado!!!!
                        Recuerde que para poder ingresar a Gaucho Rocket el día del vuelo, debe realizar previamente el checkin
                        48hs antes de la partida, de lo contrario, su pasaje será cancelado.";
        }else{
           
            $message = "Has adquirido ".$reservaData[0]['cantidadAsientos']." pasajes en el vuelo 
                    ".$reservaData[0]['id_vuelofk'].".
                    
                    Su reserva se encuentra pendiente de pago.";
                    
        }
        $to  = $email; 
            $hash = md5($email);
            $subject = "Gaucho Rocket RESERVA - ".$reservaData[0]['id']; 
            
            
                        
                        $mail = new PHPMailer(true);
                        
                        try {
                            $mail->SMTPDebug = 0;                      //Enable verbose debug output
                            $mail->isSMTP();                                            //Send using SMTP
                            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                            $mail->Username   = 'gauchorocket.oficial@gmail.com';                     //SMTP username
                            $mail->Password   = 'cbotlwdakzqkwwwb';                               //SMTP password
                            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                            $mail->setFrom('gauchorocket.oficial@gmail.com', 'Gaucho Rocket');
                            $mail->addAddress($to);     //Add a recipient
                            //$mail->isHTML(true);                                  //Set email format to HTML
                            $mail->Subject = $subject;
                            $mail->Body    = $message;
                            $mail->send();
                            return true;
                        } catch (Exception $e) {
                            return false;
                        }
        
    }

    public function getDatosReserva($reserva){
        $query = "SELECT * from reserva where id= ".$reserva;
        $result = $this->database->queryResult($query);
        return $result;
    }

    public function ValidarCabinaSeleccionada($vuelo,$tipoCabina){
        $query = "SELECT ".$tipoCabina." FROM caract_equipos where caract_modelo = (Select modelo from equipos where matricula = (select id_equipofk from vuelos_confirmados where id_vuelo = ".$vuelo."))";
        $result = $this->database->queryResult($query);
        $result = $result[0][$tipoCabina];
        $return = false;
        if ($result>0){
            $return =true;
        }
        return $return;
    }

    public function verificarDisponibilidadAsientos($vuelo,$tipoCabina,$cantAsientos,$origen,$destino){
        $totalCabinaVuelo = $this->getAsientosTotales($vuelo,$tipoCabina);
        $totalCabinaVuelo = $totalCabinaVuelo[0][$tipoCabina];
        $tramosReserva = $this->getIdTramos($vuelo,$origen,$destino);
        $return = $tramosReserva;
        foreach($tramosReserva as $tramo){
            $reservadas = $this->getReservadas($vuelo,$tipoCabina,$tramo['id_tramo']);
            $reservadas = $reservadas[0]['CantReservados'];
            $result = $reservadas+$cantAsientos;
            $totalCabinaVuelo = $totalCabinaVuelo+0;
            if($result<=$totalCabinaVuelo){
                continue;
            }else{
                $return = '';
                break;  
            }        
        }
        return $return;
    }

    public function calcularCostoReserva($tramos,$cantAsientos,$tipoCabina,$servicio){
        $totalTramos = 0;
        foreach($tramos as $tramo){
            $precioTramo = $this->getPrecioTramo($tramos[0]['id_tramo']);
            $precioTramo = intval($precioTramo[0]['precio']);
            $totalTramos = $totalTramos +$precioTramo;   
        }  
        $precioCabina = $this->getPrecioCabina($tipoCabina);
        $precioCabina = $precioCabina[0]['precio'];
        $precioServicio = $this->getPrecioServicio($servicio);
        $precioServicio = $precioServicio[0]['precio'];
        $totalReserva = ($totalTramos*$cantAsientos)+($cantAsientos*$precioCabina)+$precioServicio;
        return $totalReserva;
    }


    public function CrearReserva($vuelo,$tramo,$tipoCabina,$servicio,$cantAsientos,$costoReserva,$origen,$destino,$fechaReserva,$horaReserva){
        $vuelo = intval($vuelo);
        $servicio = intval($servicio);
        $cantAsientos = intval($cantAsientos);
        $origen = intval($origen);
        $destino = intval($destino);
        $query = "INSERT INTO reserva (id_usuariofk,id_vuelofk,tipoAsiento,id_serviciofk,pago,cantidadAsientos,
                checkIn,tramos,TotalReserva,origenReserva,destinoReserva,fechaReserva,horaReserva)
        VALUES (".$_SESSION["id"].",".$vuelo.",'".$tipoCabina."',".$servicio.",0,".$cantAsientos.",0,
                '".$tramo."',".$costoReserva.",".$origen.",".$destino.",'".$fechaReserva."',".$horaReserva.")";
        $this->database->query($query);
        $query = "SELECT id from reserva where id_usuariofk = ".$_SESSION["id"]." and id_vuelofk = ".$vuelo;
        return $this->database->queryResult($query);
    }

  

    public function getFechaYHoraReserva($origenReserva,$destinoReserva,$vuelo){
        $query = "SELECT origen,destino,fecha,h_partida,id_tipoVuelofk,id_equipofk from vuelos_confirmados where id_vuelo = ".$vuelo;
        $datosVuelo = $this->database->queryResult($query);
        $fechaVuelo = $datosVuelo[0]['fecha'] . ' ' .$datosVuelo[0]['h_partida'].':00' ;
        $origenVuelo = $datosVuelo[0]['origen'];
        $destinoVuelo = $datosVuelo[0]['destino'];
        if($origenVuelo==$origenReserva){
            $salidaVuelo = $fechaVuelo;
        }else{

            $where = "and (origen >=".$origenVuelo." and origen<".$origenReserva.")";

            $idEquipo = $datosVuelo[0]['id_equipofk'];
            $tipoVuelo = $datosVuelo[0]['id_tipoVuelofk'];
            $query= "SELECT SUM(duracion) as 'cantHoras' from tramos where circuito = '".$tipoVuelo."' and tipoEquipo = 
            (Select id_tipo from caract_equipos where caract_modelo = (select modelo from equipos where matricula = '".$idEquipo."'))".$where;
            $cantHoras = $this->database->queryResult($query);
            $cantHoras = $cantHoras[0]['cantHoras'];
            $salidaVuelo = FechayHoraHelper::SumarHoras($fechaVuelo,$cantHoras);
        }

        return $salidaVuelo;
    }




    public function getPrecioTramo($tramo){
        $query = "SELECT precio from tramos where id_tramo = ".$tramo;
        return $this->database->queryResult($query);
    }

    public function getPrecioCabina($cabina){
        $query = "SELECT precio from tipo_cabina where id_cabina = '".$cabina."'";
        return $this->database->queryResult($query);
    }

    public function getPrecioServicio($servicio){
        $query = "SELECT precio from servicios where id = ".$servicio;
        return $this->database->queryResult($query);
    }


    public function getIdTramos($vuelo,$origen,$destino){
       $query ="Select id_tramo from tramos where circuito = (select id_tipoVuelofk from vuelos_confirmados where id_vuelo = ".$vuelo.") and (tipoEquipo = (select id_tipo from caract_equipos where caract_modelo = (Select modelo from equipos where matricula = (select id_equipofk from vuelos_confirmados where id_vuelo =  ".$vuelo.")))) and (origen >=".$origen." and destino<=".$destino.")";
       return $this->database->queryResult($query);
    }


    public function getReservadas($vuelo,$tipoCabina,$tramo){
        $queryReservadas = "SELECT Count(cantidadAsientos) as 'CantReservados' from reserva where id_vuelofk = ".$vuelo." and tipoAsiento='".$tipoCabina."' and tramos like '".$tramo."'";
        return $this->database->queryResult($queryReservadas);
    }

    public function getAsientosTotales($vuelo,$tipoCabina){
        $queryReservadas = "SELECT ".$tipoCabina." from caract_equipos where caract_modelo = (Select modelo from equipos where matricula = (Select id_equipofk from vuelos_confirmados where id_vuelo = ".$vuelo."))";
        return $this->database->queryResult($queryReservadas);
    }

    public function getDatosPagoReserva($reserva){
        $query = "SELECT R.id, R.cantidadAsientos, R.TotalReserva,TC.descripcion as 'tCabina', S.descripcion as 'tServicio'
                            from reserva R JOIN tipo_cabina TC ON TC.id_cabina = R.tipoAsiento JOIN servicios S ON S.id = R.id_serviciofk where R.id = ".$reserva;
        return $this->database->queryResult($query);
    }

    public function marcarReservaPagada($reserva,$moneda,$totalAPagar){
        $query = "UPDATE reserva set pago = 1,monedaReserva='".$moneda."', TotReservaMoneda=".$totalAPagar." where id = ".$reserva;
        return $this->database->query($query);
    }

   public function verificarReservaCliente($user,$vuelo){
        $query = "SELECT id FROM reserva where id_vuelofk = ".$vuelo." and id_usuariofk=".$user;
        return $this->database->queryResult($query);
   }

   public function getValorReservaEnCreditos($reserva){
        $query = "SELECT TotalReserva FROM reserva where id = ".$reserva;
        return $this->database->queryResult($query);
   }

}


        