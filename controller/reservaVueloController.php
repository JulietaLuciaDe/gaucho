<?php

class reservaVueloController
{


    private $reservaVueloModel;
    private $log;
    private $printer;
    private $pdf;
    private $qr;

    public function __construct($logger, $printer, $reservaVueloModel, $pdf, $qr)
    {
        $this->reservaVueloModel = $reservaVueloModel;
        $this->log = $logger;
        $this->printer = $printer;
        $this->pdf = $pdf;
        $this->qr = $qr;
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Funciones publicas

    public function execute($data,$vista){
        $this->printer->generateView($data,$vista);
    }
    
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
    }

    public function reservado($id_usuario,$id_vuelo,$id_tipoVuelo,$tipoAsiento,$nroAsiento,$id_servicio,$pago,$nroPago){
                    $cantidadMaxima = $this->reservaVueloModel->getCantidadMaxima($id_vuelo); //hacerfuncion
                    $cantidadDePasajerosEnVuelo = $this->reservaVueloModel->getCantidadPasajerosEnVuelo($id_vuelo);// hacerfuncion y determinar que no se pase de esa cantidad con la maxima
        //verificar si ya reservo en este uelo
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
