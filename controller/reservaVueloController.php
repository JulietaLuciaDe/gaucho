<?php
class reservaVueloController
{
    private $reservaVueloModel;
    private $printer;
    /*
    private $log;
    private $pdf;
    private $qr;
    */
    public function __construct($reservaVueloModel,$printer){
        $this->reservaVueloModel = $reservaVueloModel;
        $this->printer = $printer;
        /*
        $this->log = $logger;
        $this->pdf = $pdf;
        $this->qr = $qr;
        */
    }

    public function execute($view = 'reservaVueloView.html',$data = []){
        if(validatorHelper::validarSesionActiva()){
            $menu ="<p>Hola, ".$_SESSION['user']."</p>
                <a href='/misReservas/execute'>Mis Reservas</a>
                  <a href='/logIn/exit'>Cerrar Sesion</a>";
          }else{
            $menu ="<a href='/registro'>Registrarse</a>
            <a href='/logIn'>Ingresar</a>";
          }
          $data += ["menu"=>$menu];
        $this->printer->generateView($view,$data);
    }

    

    public function mostrarSeccionPago($reserva){
          $reserva = $reserva[0]['id'];
          $datosReserva = $this->reservaVueloModel->getDatosPagoReserva($reserva);
          $data = ["reservaData"=>$datosReserva];
        $this->execute('pagoReservaView.html',$data);
        //HAY QUE CREAR NUEVOS METODOS PARA VALIDAR PAGO Y MARCARLO PAGADO
    }

    private function validarSiExisteVuelo($id_vuelo,$tabla){
        $vuelo = $this->reservaVueloModel->getVueloSeleccionado($id_vuelo,$tabla);
        if(empty($vuelo)||$vuelo==""){
            return false;
        }else{
            return true;
        }
    }

    public function reserva($vuelo = '',$data = []){
        $id_vuelo = $vuelo;
        if(isset($_GET["id"])){
            $id_vuelo = $_GET["id"];
        }
        if(ValidatorHelper::validarSesionActiva()){
            if($this->validarSiExisteVuelo($id_vuelo,"vuelos")){
                if(!($this->validarSiExisteVuelo($id_vuelo,"vuelos_confirmados"))){
                    $tramo = $this->reservaVueloModel->getDestinosyTipoVuelo($id_vuelo);
                    $destinos = '|';
                    for($i=$tramo[0]["origen"];$i<=$tramo[0]["destino"];$i++){
                        if($tramo[0]["tipovuelofk1"]=='ED2' && $i==3){
                            continue;
                        }else{
                            $destinos = $destinos.$i;
                            $destinos = $destinos.'|';
                        }
                    }
                    $this->reservaVueloModel->crearVuelo($id_vuelo,$destinos);
                }
            }else{
                header("Location:/inicio");
                exit();
            }
        }else{
            header("Location:/login");
            exit();
        }

        $vuelo=$this->reservaVueloModel->getDatosVuelo($id_vuelo);
        $email = $_SESSION["usuario"];
        $usuario=$this->reservaVueloModel->getUsuario($email);
        $servicios = $this->reservaVueloModel->getServicios();
        $cabinas = $this->reservaVueloModel->getCabinasDisponibles($id_vuelo);
        $destinos = $this->reservaVueloModel->getTramoVuelo($id_vuelo);
        $cantDestinos = count($destinos);
        $datos_destinos = $this->getDestinos($destinos);
        $data += ["usuario" => $usuario,"vuelo" => $vuelo,"servicios" => $servicios,"cabinas" => $cabinas,
        "cabinas" => $cabinas,"datos_destinos" => $datos_destinos];
        if($cantDestinos>2){
            $data += ["tramos" => true];
        }
        $this->execute('reservaVueloView.html',$data);
    }

  
    public function getDestinos($destinos){
        $cant=count($destinos);
        unset($destinos[$cant-1]);
        $i = 0;
        foreach($destinos as $valor){
            $destino = $this->reservaVueloModel->getDatosDestino($valor);
            $id_destinos[$i]= ['idDestino'=>$destino[0]['id_destino'],'nombreDestino'=>$destino[0]['descripcion']];
            $i+=1;
        }
        return $id_destinos;
    }

   
    public function VerificarReserva(){
        if(ValidatorHelper::validarSesionActiva()){
            if(ValidatorHelper::validacionDeTexto($_POST['tipoVuelo'],3)){
                $tipoVuelo = $_POST['tipoVuelo'];
                $EDValid =true;
                $origen = $_POST['OrigenVuelo'];
                $destino = $_POST['DestinoVuelo'];
                if($tipoVuelo=='ED1' || $tipoVuelo =='ED2'){
                    $EDValid = ValidatorHelper::validacionDeNumeros($_POST['origen'],2) &&
                    ValidatorHelper::validacionDeNumeros($_POST['destino'],2)&&
                    ($_POST['origen']!=$_POST['destino']);
                    if($EDValid){
                        $origen = $_POST['origen'];
                        $destino = $_POST['destino'];
                    }
                }
                if($EDValid && ValidatorHelper::validacionDeNumeros($_POST['vuelo'],11)&&
                ValidatorHelper::validacionDeNumeros($_POST['servicio'],1)&&
                ValidatorHelper::validacionDeNumeros($_POST['asientos'],3)&&
                ValidatorHelper::validacionDeTexto($_POST['cabina'],3)){       
                    $vuelo = $_POST['vuelo'];
                    $servicio = $_POST['servicio'];
                    $cantAsientos = $_POST['asientos'];
                    $tipoCabina = $_POST['cabina'];
                    if($this->reservaVueloModel->ValidarCabinaSeleccionada($vuelo,$tipoCabina)){
                        if($this->validarSiExisteVuelo($vuelo,"vuelos_confirmados")){
                            if($this->reservaVueloModel->validarVueloYaReservado($vuelo)){
                                $tramos = $this->reservaVueloModel->verificarDisponibilidadAsientos($vuelo,$tipoCabina,$cantAsientos,$origen,$destino);
                                if(!empty($tramos)){
                                    $costoReserva = $this->reservaVueloModel->calcularCostoReserva($tramos,$cantAsientos,$tipoCabina,$servicio);
                                    $tramo = $this->getStringTramo($tramos);
                                    $reserva = $this->reservaVueloModel->CrearReserva($vuelo,$tramo,$tipoCabina,$servicio,$cantAsientos,$costoReserva);
                                    if($reserva){
                                        $this->reservaVueloModel->sendMailReservado($reserva);
                                        $this->mostrarSeccionPago($reserva);
                                    }else{
                                        header("Location: /inicio");
                                        exit();
                                    }
                                }else{
                                    $title = "Ups!";
                                    $message = "La cantidad de asientos seleccionado supera el disponible en el vuelo";
                                    $display = "d-block";
                                    $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
                                    $this->reserva($vuelo,$data);
                                }
                            }else{
                                $title = "Ya tiene pasajes para este vuelo";
                                $message = "si desea modificar su reserva debe cancelarla y volver a solicitarla";
                                $display = "d-block";
                                $data = ["popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display];
                                $this->reserva($vuelo,$data);
                            }
                        }else{
                            header("Location: /inicio");
                            exit();
                        }
                    }else{
                        header("Location: /inicio");
                        exit();
                    }
                }else{
                    header("Location: /inicio");
                    exit();
                }
            }else{
                header("Location: /inicio");
                exit();
            }
        }else{
            header("Location: /login");
                    exit();
        }
    }

public function validarPago(){
    if(ValidatorHelper::validacionDeTexto($_POST['moneda'],3)&&
    ValidatorHelper::validacionDeTexto($_POST['inputNombre'],50)&&
    ValidatorHelper::validacionDeNumeros($_POST['id'],11)&&
   /* ValidatorHelper::validacionDeNumeros($_POST['inputNumero'],20)&&
   VERIFICAR ESTO, NO VALIDA PORQUE VIENEN SEPARADOS DE A 4*/
    ValidatorHelper::validacionDeNumeros($_POST['mes'],2)&&
    ValidatorHelper::validacionDeNumeros($_POST['year'],4)&&
    ValidatorHelper::validacionDeNumeros($_POST['inputCCV'],4)){
        $moneda = $_POST['moneda'];
        $nombre = $_POST['inputNombre'];
        $reserva = $_POST['id'];
        $numero = $_POST['inputNumero'];
        $mes = $_POST['mes'];
        $year = $_POST['year'];
        $CCV = $_POST['inputCCV'];

        if(!($moneda === 'ARP' || $moneda === 'USD')){
            header('Location: /inicio');
            exit();
        }
        else{
            /*********REVISAR ESTA LOGICA PARA EL CAMBIO DE MONEDA 
            $tc = 73.10;
            $monedaCambio = 'USD';
            /*DESARROLLAR ESTE METODO
            $totalReserva = $this->reservaVueloModel->getCostoReserva($reserva);
            $totalConvertido = $tc*$totalReserva;
            
            if($moneda=='ARP'){
                tc= 150;
                $totalConvertido = $tc*$totalConvertido;
            }
            /***************************************************** */
        }
        
        if(!($mes>0 && $mes<13) || !($year>date("Y"))){
            header('Location: /inicio');
            exit();
        }
    //**ACA TIENE QUE HACER UN INSERT DE LOS DATOS DE LA TARJETA EN UNA TABLA TARJETAS O EN LA TABLA RESERVAS */
   
    if($this->reservaVueloModel->marcarReservaPagada($reserva)){
         /*DESARROLLAR ESTE METODO
        $this->reservaVueloModel->sendMailPagado($reserva);**/
        /*        header('Location: /misReservas');
        exit();*/

        echo "PAGADO CON ÉXITO";
    }

    }else{
        echo "datos invalidos";
    }
}

public function getStringTramo($tramos){
    $cantTramos = count($tramos);
    $tramo = '';
    for($i=0;$i<$cantTramos;$i++){
        $tramo = $tramo.'|'.$tramos[$i]['id_tramo'];
    }
    return $tramo;
}
}
   