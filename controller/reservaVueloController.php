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
    public function __construct($reservaVueloModel,$printer)
    {
        $this->reservaVueloModel = $reservaVueloModel;
        $this->printer = $printer;
        /*
        $this->log = $logger;
        $this->pdf = $pdf;
        $this->qr = $qr;
        */
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Funciones publicas

    public function execute($data){
        if(validatorHelper::validarSesionActiva()){
            $menu ="<p>Hola, ".$_SESSION['user']."</p>
                <a href='/logIn/exit'>Cerrar Sesion</a>";
          }else{
            $menu ="<a href='/registro'>Registrarse</a>
            <a href='/logIn'>Ingresar</a>";
          }
          $data += ["menu"=>$menu];
        $this->printer->generateView('reservaVueloView.html',$data);

    }

    private function validarSiExisteVuelo($id_vuelo,$tabla){
        $vuelo = $this->reservaVueloModel->getVueloSeleccionado($id_vuelo,$tabla);
        if(empty($vuelo)||$vuelo==""){
            return false;
        }else{
            return true;
        }
    }

    public function reserva()
    {
        $id_vuelo = $_GET["id"];
        if(ValidatorHelper::validarSesionActiva()){
            if($this->validarSiExisteVuelo($id_vuelo,"vuelos")){
                if(!($this->validarSiExisteVuelo($id_vuelo,"vuelos_confirmados"))){
                    $tramo = $this->reservaVueloModel->getDestinosyTipoVuelo($id_vuelo);
                    
                     $destinos = '';

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
        $destinos = $this->reservaVueloModel->getTramoVuelo($id_vuelo);
        $cantDestinos = count($destinos);
        
        $datos_destinos = $this->getDestinos($destinos);
        $data = ["usuario" => $usuario];
        $data += ["vuelo" => $vuelo];
        $data += ["servicios" => $servicios];
        $data += ["datos_destinos" => $datos_destinos];
        if($cantDestinos>2){
            $data += ["tramos" => true];
        }
        $this->execute($data);
    }

  
    public function getDestinos($destinos)
    {   
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
        
    }


    /*
    public function reserva($id)
    {
        $id_vuelo=$_GET["id"];
        $vuelo = $this->reservaVueloModel->getVueloSeleccionado($id_vuelo); 
        if(empty($vuelo) || $vuelo == null){
            //acá hay que crearlo para que puedan empezar a usarlo!.
            $vuelo=$this->reservaVueloModel->crearVuelo($id_vuelo);
        }
        $data["id_vuelo"] = $vuelo->id_vuelo;
        $data["fecha"] = $vuelo->fecha;
        $data["duracion"] = $vuelo->duracion;
        $data["origen"] = $vuelo->origen;
        $data["destino"] = $vuelo->destino;
        $data["id_equipo"] = $vuelo->id_equipo;
        $this->execute($data,'reservaVuelo.html');
    }*/

    public function reservado($id_usuario,$id_vuelo,$id_tipoVuelo,$tipoAsiento,$nroAsiento,$id_servicio,$pago,$nroPago){
                    $cantidadMaxima = $this->reservaVueloModel->getCantidadMaxima($id_vuelo); //hacerfuncion
                    $cantidadDePasajerosEnVuelo = $this->reservaVueloModel->getCantidadPasajerosEnVuelo($id_vuelo);// hacerfuncion y determinar que no se pase de esa cantidad con la maxima
        //verificar si ya reservo en este vuelo
                    if ($cantidadDePasajerosEnVuelo<$cantidadMaxima)
                    if(ValidatorHelper::validacionDeNumeros($id_usuario)&& ValidatorHelper::validacionDeNumeros($id_vuelo)&&
                        ValidatorHelper::validacionDeNumeros($id_tipoVuelo)&& ValidatorHelper::validacionDeTexto($tipoAsiento,30)&&
                        ValidatorHelper::validacionDeNumeros($nroAsiento)&& ValidatorHelper::validacionDeNumeros($id_servicio)
                    )
                    {
                        $this->reservaVueloModel->registrarReserva(); //pasar datos
                        $title="Vuelo Reservado";
                        $message="Ya te haz reservado!";
                        $data = ["popUp" => true,"title"=> $title,"message"=>$message];
                        $this->execute($data,'reservaVuelo.html');

                    }




    }
}
