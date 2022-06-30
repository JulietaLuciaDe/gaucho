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

    public function execute($data){
        if(validatorHelper::validarSesionActiva()){
            $menu ="<p>Hola, ".$_SESSION['user']."</p>
                  <a href='/misReservas'>Mis Reservas</a>
                  <a href='/logIn/exit'>Cerrar Sesion</a>";
          }else{
            $menu ="<a href='/registro'>Registrarse</a>
            <a href='/logIn'>Ingresar</a>";
          }
          $data += ["menu"=>$menu];
        $this->printer->generateView('reservaVueloView.html',$data);
    }

    public function mostrarSeccionPago($reserva){
        if(validatorHelper::validarSesionActiva()){
            $menu ="<p>Hola, ".$_SESSION['user']."</p>
                  <a href='/misReservas'>Mis Reservas</a>
                  <a href='/logIn/exit'>Cerrar Sesion</a>";
          }else{
            $menu ="<a href='/registro'>Registrarse</a>
            <a href='/logIn'>Ingresar</a>";
          }
          $data = ["menu"=>$menu];
          $reserva = $reserva[0]['id'];
          $datosReserva = $this->reservaVueloModel->getDatosPagoReserva($reserva);
          $data += ["reservaData"=>$datosReserva];
        $this->printer->generateView('pagoReservaView.html',$data);
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

    public function reserva(){
        $id_vuelo = $_GET["id"];
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
        $data = ["usuario" => $usuario];
        $data += ["vuelo" => $vuelo];
        $data += ["servicios" => $servicios];
        $data += ["cabinas" => $cabinas];
        $data += ["datos_destinos" => $datos_destinos];
        if($cantDestinos>2){
            $data += ["tramos" => true];
        }
        $this->execute($data);
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
            //revisar este if, no me acuerdo por qué lo puse
            if(isset($_POST['tipoVuelo'])){
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
                                        $this->mostrarSeccionPago($reserva);
                                    }else{
                                        echo "Hubo un problema al crear la reserva";
                                    }
                                }else{
                                    echo "ups! la cantidad de asientos seleccionado supera el disponible en el vuelo ";
                                }
                            }else{
                                echo "ya ha contratado pasajes en este vuelo.Si desea realizar algun cambio debe cancelar la reserva y volver a gestionarlo";
                            }
                        }else{
                            header("Location: /inicio");
                            exit();
                        }
                    }else{
                        echo "cabina invalida para el vuelo seleccionado (habria que validarlo en el front)";
                    }
                }else{
                    echo "no valido dato";
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

public function getStringTramo($tramos){
    $cantTramos = count($tramos);
    $tramo = '';
    for($i=0;$i<$cantTramos;$i++){
        $tramo = $tramo.'|'.$tramos[$i]['id_tramo'];
    }
    return $tramo;
}
}
   