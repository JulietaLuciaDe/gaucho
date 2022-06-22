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
    
    function show()
    {
        echo $this->printer->render("view/reservaVueloView.html");
    }
    
    function reserva($id)
    {
        // $data["hoy"] = date('Y-m-d');
        // if (isset($_POST["date"]) && $_POST["date"]) {
        //     $data['diaSeleccionado'] = $_POST["date"];
        //     $data['vuelos'] = $this->reservaVueloModel->getVuelosDia($data["diaSeleccionado"], $_POST["desde"]);
        // } else if (isset($_POST["desde"])) {
        //     $data['vuelos'] = $this->reservaVueloModel->getVuelosDesde($_POST["desde"]);
        // } else {
        //     $data['vuelos'] = $this->reservaVueloModel->getVuelos();
        // }
        
        // //Agrego debug porque seria raro tenes vacios
        // if (sizeof($data['vuelos']) == 0 && isset($_SESSION["debug"])) {
        //     var_dump($_POST);
        // }
        
        // //Agrego los dias, si me llego un dia paso ese, sino, la fecha de hoy
        // $this->agregarDia($data['vuelos'], isset($data["diaSeleccionado"]) ? $data["diaSeleccionado"] : $data["hoy"]);
        
        // echo $this->printer->render("view/suborbitalView.html", $data);

        $vuelo = $this->reservaVueloModel->getVueloSeleccionado($id); //crear en el model.
            if(empty($vuelo) || $vuelo == null){
                $title="PROBLEMAS TÉCNICOS";
                $message="El vuelo no se encuentra disponible. 
                            Por favor, seleccione otra opción de vuelo.
                            Disculpe las molestias ocacionadas.";
                $data = ["popUp" => true,"title"=> $title,"message"=>$message];
                $this->execute($data,'reservaVuelo.html');
            } else {
                $data["id_usuario"] = ;
                $data["id_vuelo"] = ;
                $data["id_tipoVuelo"] = ;
                $data["tipoAsiento"] = ;
                $data["numeroAsiento"] = ;
                $data["idServicio"] = ;
                $data["pago"] = ;
                $data["numeroPago"] = ;
            }
    }

    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Funciones de reservas
    
    function suborbital_reserva()
    {
        (new SeguridadController)->estaLogueado();;
        
        $tipo = $this->reservaVueloModel->tipoUsuario($_SESSION["id"]);
        if (!$tipo["tipo"]) {
            $_SESSION["mensaje"]["class"] = "warning";
            $_SESSION["mensaje"]["mensaje"] = "Debe chequear su capacidad para volar reservando turno medico";
            header('Location: /home');
            die();
        }
        
        //datos que vienen del post
        $nroDia = $_POST["nroDia"];
        //convierto fecha a unix
        $data["fecha"] = str_replace("/", "-", $nroDia);
        $data["fecha"] = date('Y-m-d', strtotime($data["fecha"]));
        $data["partida"] = $_POST["partida"];
        $data["duracion"] = $_POST["duracion"];
        $data["horario"] = $_POST["hora"];
        
        //si usuario ya tiene vuelo para este viaje, le da error
        if ($this->reservaVueloModel->usuarioTienePasajeVuelo($data["fecha"], $data["horario"], $data["partida"], $_SESSION["id"])) {
            $_SESSION["mensaje"]["class"] = "error";
            $_SESSION["mensaje"]["mensaje"] = "Solo puede reservar un pasaje por vuelo";
            header('Location: /vuelos/suborbital');
            die();
        }
        
        //chequear si ya alguien reservo en ese mismo vuelo, y traer la matricula
        $data["matricula"] = $this->reservaVueloModel->matriculaVuelo($data["fecha"], $data["horario"], $data["partida"]);
        if ($data["matricula"] && $data["matricula"]["matricula"] != '') {
            
            //acomodo el array para que sea un string
            //sino mustache explota
            $data["matricula"] = $data["matricula"]["matricula"];
        } else {
            //asigno una matricula
            $data["matricula"] = $this->reservaVueloModel->asignarMatriculaOrbital($data["fecha"], $data["horario"], $data["partida"]);
        }
        
        //Segun el tipo de avion, los asientos que tenga
        $cantidadDeAsientosPorTipo = $this->reservaVueloModel->cantidadAsientosPorTipo($data["matricula"]);
        $asientosOcupadosDelVuelo = $this->reservaVueloModel->asientosReservados($data["fecha"], $data["horario"], $data["partida"], $data["matricula"]);
        $data["asientos"] = $this->imprimirAsientos($cantidadDeAsientosPorTipo, $asientosOcupadosDelVuelo);
        
        
        //TODO armar el combo box segun la cantidad
        //$cantidadDeAsientosPorTipo - $cantidadReservada;
        //$cantidadDeAsientosDisponiblesPorTipo;
        
        echo $this->printer->render("view/suborbital_reservaView.html", $data);
    }
    
    function tour_reserva()
    {
        
        (new SeguridadController)->estaLogueado();
        
        $tipo = $this->reservaVueloModel->tipoUsuario($_SESSION["id"]);
        if (!$tipo["tipo"]) {
            $_SESSION["mensaje"]["class"] = "warning";
            $_SESSION["mensaje"]["mensaje"] = "Debe chequear su capacidad para volar reservando turno medico";
            header('Location: /home');
            die();
        }
        
        
        //datos que vienen del post
        $nroDia = $_POST["nroDia"];
        //convierto fecha a unix
        $data["fecha"] = str_replace("/", "-", $nroDia);
        $data["fecha"] = date('Y-m-d', strtotime($data["fecha"]));
        $data["partida"] = $_POST["partida"];
        $data["duracion"] = $_POST["duracion"];
        $data["horario"] = $_POST["hora"];
        
        //si usuario ya tiene vuelo para este viaje, le da error
        if ($this->reservaVueloModel->usuarioTienePasajeVueloTour($data["fecha"], $data["horario"], $data["partida"], $_SESSION["id"])) {
            $_SESSION["mensaje"]["class"] = "error";
            $_SESSION["mensaje"]["mensaje"] = "Solo puede reservar un pasaje por vuelo";
            header('Location: /vuelos/tour');
            die();
        }
        
        //chequear si ya alguien reservo en ese mismo vuelo, y traer la matricula
        $data["matricula"] = $this->reservaVueloModel->matriculaVueloTour($data["fecha"], $data["horario"], $data["partida"]);
        if ($data["matricula"] && $data["matricula"]["matricula"] != '') {
            
            //acomodo el array para que sea un string
            //sino mustache explota
            $data["matricula"] = $data["matricula"]["matricula"];
        } else {
            //asigno una matricula
            $data["matricula"] = $this->reservaVueloModel->asignarMatriculaTour($data["fecha"], $data["horario"], $data["partida"]);
        }
        
        //Segun el tipo de avion, los asientos que tenga
        $cantidadDeAsientosPorTipo = $this->reservaVueloModel->cantidadAsientosPorTipo($data["matricula"]);
        $asientosOcupadosDelVuelo = $this->reservaVueloModel->asientosReservadosTour($data["fecha"], $data["horario"], $data["partida"], $data["matricula"]);
        $data["asientos"] = $this->imprimirAsientos($cantidadDeAsientosPorTipo, $asientosOcupadosDelVuelo);
        
        //armar el combo box segun la cantidad
        //$cantidadDeAsientosPorTipo - $cantidadReservada;
        //$cantidadDeAsientosDisponiblesPorTipo;
        
        echo $this->printer->render("view/tour_reservaView.html", $data);
    }
    
    function entreDestinos_reserva()
    {
        (new SeguridadController)->estaLogueado();;
        
        $tipo = $this->reservaVueloModel->tipoUsuario($_SESSION["id"]);
        if (!$tipo["tipo"]) {
            $_SESSION["mensaje"]["class"] = "warning";
            $_SESSION["mensaje"]["mensaje"] = "Debe chequear su capacidad para volar reservando turno medico";
            header('Location: /home');
            die();
        }
        
        
        //datos que vienen del post
        $fechayhora = $_POST["fechayhora"];
        //convierto fecha a unix
        $data["fechayhora"] = str_replace("/", "-", $fechayhora);
        // $data["fechayhora"] = date('Y-m-d', strtotime($data["fechayhora"]));
        $data["desde"] = $_POST["desde"];
        $data["duracion"] = $_POST["duracion"];
        $data["idvuelo"] = $_POST["idvuelo"];
        
        //si usuario ya tiene vuelo para este viaje, le da error
        if ($this->reservaVueloModel->usuarioTienePasajeVueloEntreDestinos($data["idvuelo"], $_SESSION["id"])) {
            $_SESSION["mensaje"]["class"] = "error";
            $_SESSION["mensaje"]["mensaje"] = "Solo puede reservar un pasaje por vuelo";
            header('Location: /vuelos/entreDestinos');
            die();
        }
        
        //chequear si ya alguien reservo en ese mismo vuelo, y traer la matricula
        $data["matricula"] = $this->reservaVueloModel->matriculaVueloEntreDestinos($data["idvuelo"]);
        if ($data["matricula"] && $data["matricula"] != '') {
            
            //acomodo el array para que sea un string
            //sino mustache explota
            $data["matricula"] = $data["matricula"]["matricula"];
        }
        
        //Segun el tipo de avion, los asientos que tenga
        $cantidadDeAsientosPorTipo = $this->reservaVueloModel->cantidadAsientosPorTipo($data["matricula"]);
        $asientosOcupadosDelVuelo = $this->reservaVueloModel->asientosReservadosEntreDestinos($data["idvuelo"]);
        $data["asientos"] = $this->imprimirAsientos($cantidadDeAsientosPorTipo, $asientosOcupadosDelVuelo);
        
        echo $this->printer->render("view/entreDestinos_reservaView.html", $data);
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Funciones de pagos y confirmacion de reservas
    
    public function generarPago()
    {
        (new SeguridadController)->estaLogueado();;
        
        $data = array();
        $data["fecha"] = $_POST["fecha"];
        $data["hora"] = $_POST["hora"];
        $data["partida"] = $_POST["partida"];
        $data["duracion"] = $_POST["duracion"];
        $data["matricula"] = $_POST["matricula"];
        $data["tipo_asiento"] = $_POST["tipo_asiento"];
        $data["servicio"] = $_POST["servicio"];
        
        
        if (isset($_POST["general"])) {
            $data["num_asiento"] = $_POST["general"];
        } else
            if (isset($_POST["familiar"])) {
                $data["num_asiento"] = $_POST["familiar"];
            } else
                if (isset($_POST["suite"])) {
                    $data["num_asiento"] = $_POST["suite"];
                }
        
        $data["id_usuario"] = $_SESSION["id"];
        
        if ($this->reservaVueloModel->asientoOcupado($data["fecha"], $data["hora"], $data["partida"], $data["tipo_asiento"], $data["num_asiento"])) {
            $_SESSION["mensaje"]["class"] = "error";
            $_SESSION["mensaje"]["mensaje"] = "El asiento ya esta ocupado, por favor, seleccione otro asiento";
            header('Location: /vuelos/suborbital');
            die();
        }
        
        
        //Generar preferencia
        $preferencia = $this->MP->pagoReserva("Reserva suborbital",
            "Vuelo desde " . $data["partida"] . " el dia " . $data["fecha"] . " a las " . $data["hora"] . " horas", 1100, "suborbital");
        
        $data["preferencia"] = $preferencia->id;
        //Guardar el pago en la base de datos
        if ($this->reservaVueloModel->guardarPagoSuborbitales($data)) {
            //llamar al render del pago
            //Aca va a estar el link de pago
            
            echo $this->printer->render("view/pagarView.html", $data);
        }
        
    }
    
    public function registrarVuelo()
    {
        (new SeguridadController)->estaLogueado();;
        
        $data["fecha"] = $_POST["fecha"];
        $data["hora"] = $_POST["hora"];
        $data["partida"] = $_POST["partida"];
        $data["destino"] = $_POST["destino"];
        $data["duracion"] = $_POST["duracion"];
        $data["matricula"] = $_POST["matricula"];
        if ($this->reservaVueloModel->registrarVueloEntreDestinos($data)) {
            $_SESSION["mensaje"]["class"] = "exito";
            $_SESSION["mensaje"]["mensaje"] = "Vuelo creado con exito";
            header('Location:/');
        } else {
            $_SESSION["mensaje"]["class"] = "error";
            $_SESSION["mensaje"]["mensaje"] = "Error al crear vuelo";
            
            echo $this->printer->render("view/altaVuelosView.html");
        }
    }
    
    function generarComprobante()
    {
        (new SeguridadController)->estaLogueado();;
        
        //Chequear el ok del pago
        if ($_GET["collection_status"] != 'approved') {
            $_SESSION["mensaje"]["class"] = "error";
            $_SESSION["mensaje"]["mensaje"] = "Error al enviar los datos";
            header('Location: /vuelos');
            die();
        }
        //llamar a los datos en la BD
        if ($_GET["external_reference"] == "entredestinos") {
            $data = $this->reservaVueloModel->recuperarPagoEntreDestinos($_GET["preference_id"]);
        } else {
            $data = $this->reservaVueloModel->recuperarPago($_GET["preference_id"]);
        }
        $data["referencia"] = $_GET["external_reference"];
        if (!$data) {
            $_SESSION["mensaje"]["class"] = "error";
            $_SESSION["mensaje"]["mensaje"] = "Error al recuperar el pago";
            header('Location: /vuelos');
            die();
        }
        
        //eliminar el registro
        if (!isset($_SESSION["debug"])) {
            if ($data["referencia"] == "suborbital") {
                $this->reservaVueloModel->eliminarPagoRealizado($_GET["preference_id"]);
            }
            if ($data["referencia"] == "tour") {
                $this->reservaVueloModel->eliminarPagoRealizadoTour($_GET["preference_id"]);
            }
            if ($data["referencia"] == "entredestinos") {
                $this->reservaVueloModel->eliminarPagoRealizadoEntreDestinos($_GET["preference_id"]);
            }
        }
        //Genero la reserva segun el tipo de vuelo
        if ($_GET["external_reference"] == "suborbital") {
            $idReserva = $this->reservaVueloModel->generarReservaSuborbital($data);
        }
        if ($_GET["external_reference"] == "tour") {
            $idReserva = $this->reservaVueloModel->generarReservaTour($data);
        }
        if ($_GET["external_reference"] == "entredestinos") {
            $idReserva = $this->reservaVueloModel->generarReservaEntreDestinos($data);
        }
        
        //generar el comprobante
        if (isset($idReserva)) {
            //Separo fecha y hora
            $explode = explode(' ', $data["fechayhora"]);
            $data["fecha"] = $explode[0];
            $data["hora"] = $explode[1];
            
            //Genero el QR con el ID de la reserva
            $data["qr"] = $this->qr->generarQr($idReserva);
            
            //Genero el PDF y guardo el path en una variable
            $html = $this->printer->render("view/datosPdf.html", $data);
            $path = $this->pdf->generarPdf("formulario" . time(), $html);
            
            //Mando el PDF por mail
            $this->mailer->enviarMail($_SESSION["email"], "Reserva de vuelo " . $data["referencia"], "Adjuntamos archivo de comprobante", $_SESSION["nombre"], $path);
            
            //Exito
            $_SESSION["mensaje"]["class"] = "exito";
            $_SESSION["mensaje"]["mensaje"] = "Su reserva se genero con exito! Va a estar recibiendo un correo en la casilla " . $_SESSION["email"] . " en breve";
            header('Location: /home');
        } else {
            $_SESSION["mensaje"]["class"] = "error";
            $_SESSION["mensaje"]["mensaje"] = "Error al generar la reserva";
            header('Location: /vuelos');
            die();
        }
    }
    
    function generarPagoTour()
    {
        (new SeguridadController)->estaLogueado();;
        
        $data = array();
        $data["fecha"] = $_POST["fecha"];
        $data["hora"] = $_POST["hora"];
        $data["partida"] = $_POST["partida"];
        $data["duracion"] = $_POST["duracion"];
        $data["matricula"] = $_POST["matricula"];
        $data["tipo_asiento"] = $_POST["tipo_asiento"];
        $data["servicio"] = $_POST["servicio"];
        
        
        if (isset($_POST["general"])) {
            $data["num_asiento"] = $_POST["general"];
        } else
            if (isset($_POST["familiar"])) {
                $data["num_asiento"] = $_POST["familiar"];
            } else
                if (isset($_POST["suite"])) {
                    $data["num_asiento"] = $_POST["suite"];
                }
        
        $data["id_usuario"] = $_SESSION["id"];
        if ($this->reservaVueloModel->asientoOcupadoTour($data["fecha"], $data["hora"], $data["partida"], $data["tipo_asiento"], $data["num_asiento"])) {
            $_SESSION["mensaje"]["class"] = "error";
            $_SESSION["mensaje"]["mensaje"] = "El asiento ya esta ocupado, por favor, seleccione otro asiento";
            header('Location: /vuelos/tour');
            die();
        }
        
        //Generar preferencia
        $preferencia = $this->MP->pagoReserva("Reserva tour",
            "Vuelo desde " . $data["partida"] . " el dia " . $data["fecha"] . " a las " . $data["hora"] . " horas", 1100, "tour");
        
        $data["preferencia"] = $preferencia->id;
        //Guardar el pago en la base de datos
        if ($this->reservaVueloModel->guardarPagoTour($data)) {
            //llamar al render del pago
            //Aca va a estar el link de pago
            echo $this->printer->render("view/pagarView.html", $data);
        }
    }
    
    public function generarPagoEntreDestinos()
    {
        (new SeguridadController)->estaLogueado();
        $data = array();
        $data["idvuelo"] = $_POST["idvuelo"];
        $data["tipo_asiento"] = $_POST["tipo_asiento"];
        $data["servicio"] = $_POST["servicio"];
        
        
        if (isset($_POST["general"])) {
            $data["num_asiento"] = $_POST["general"];
        } else
            if (isset($_POST["familiar"])) {
                $data["num_asiento"] = $_POST["familiar"];
            } else
                if (isset($_POST["suite"])) {
                    $data["num_asiento"] = $_POST["suite"];
                }
        
        $data["id_usuario"] = $_SESSION["id"];
        
        if ($this->reservaVueloModel->asientoOcupadoEntreDestinos($data["idvuelo"], $data["tipo_asiento"], $data["num_asiento"])) {
            $_SESSION["mensaje"]["class"] = "error";
            $_SESSION["mensaje"]["mensaje"] = "El asiento ya esta ocupado, por favor, seleccione otro asiento";
            header('Location: /vuelos/entredestinos');
            die();
        }
        
        //Generar preferencia
        $preferencia = $this->MP->pagoReserva("Reserva Entre destinos",
            "Vuelo desde " . $_POST["partida"] . " el dia y hora es " . $_POST["fechayhora"], 1100, "entredestinos");
        
        $data["preferencia"] = $preferencia->id;
        //Guardar el pago en la base de datos
        if ($this->reservaVueloModel->guardarPagoEntreDestinos($data)) {
            //llamar al render del pago
            //Aca va a estar el link de pago
            
            echo $this->printer->render("view/pagarView.html", $data);
        }
        
    }
    
    public function errorDePago()
    {
        $_SESSION["mensaje"]["class"] = "error";
        $_SESSION["mensaje"]["mensaje"] = "Hubo un error en el pago";
        header('Location: /home');
        die();
    }
    
    public function cobroPendiente()
    {
        $_SESSION["mensaje"]["class"] = "error";
        $_SESSION["mensaje"]["mensaje"] = "El pago esta pendiente de cobro";
        header('Location: /home');
        die();
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Funciones helper
    
    private function agregarFechasDeVuelo($vuelos, $fecha)
    {
        $esDomingo = strtotime($fecha);
        global $DIAS;
        $resultado = array();
        
        for ($i = 0; sizeof($resultado) < 15; $i++) {
            if (!isset($vuelos[$i])) {
                $i = 0;
            }
            $vuelos[$i]["nroDia"] = strtotime("+" . sizeof($resultado) . " week", $esDomingo);
            $vuelos[$i]["nroDia"] = date("d/m/Y", $vuelos[$i]["nroDia"]);
            array_push($resultado, $vuelos[$i]);
        }
        return $resultado;
    }
    
    private function primerDomingo($fecha)
    {
        $num = strtotime($fecha);
        $fechaConvertir = date('w', $num);
        switch ($fechaConvertir) {
            case 1:
                $numLunes = strtotime("+6 day", $num);
                return $fecha = date("Y-m-d", $numLunes);
                break;
            case 2:
                $numMartes = strtotime("+5 day", $num);
                return $fecha = date("Y-m-d", $numMartes);
                break;
            case 3:
                $numMiercoles = strtotime("+4 day", $num);
                return $fecha = date("Y-m-d", $numMiercoles);
                break;
            case 4:
                $numJueves = strtotime("+3 day", $num);
                return $fecha = date("Y-m-d", $numJueves);
                break;
            case 5:
                $numViernes = strtotime("+2 day", $num);
                return $fecha = date("Y-m-d", $numViernes);
                break;
            case 6:
                $numSabado = strtotime("+1 day", $num);
                return $fecha = date("Y-m-d", $numSabado);
                break;
            default:
                return $fecha;
                break;
        }
    }
    
    /**
     * Agrega el numero del dia a todos los vuelos
     *
     * @param $array Array de vuelos
     * @param $fechaEstatica fecha del vuelo o fecha de donde empezar a contar
     */
    private function agregarDia(&$array, $fechaEstatica)
    {
        //convierto a formato unix
        $fechaEstatica = strtotime($fechaEstatica);
        
        global $DIAS;
        $numeros = array();
        
        //Segun el nombre del dia, seteo el numero
        //Si hoy es sabado 15, el $numeros en sabado va a tener 15
        //Luego, va a recorrer, por lo que el viernes va a ser hoy +6 = 21
        //el array quedaria
        // Sabado => 15
        // Domingo => 16
        // Lunes => 17
        // etc
        for ($i = -1; $i <= 6; $i++) {
            //El dia de entrada, mas los dias que ya recorri
            $n = strtotime("+$i day", $fechaEstatica);
            
            //$DIAS tiene del 0 al 6, empezando por domingo
            $numeros[$DIAS[date('w', $n)]] = $n;
        }
        
        //Segun el dia del vuelo, asigno "nroDia"
        foreach ($array as &$vuelo) {
            $vuelo["nroDia"] = date("d/m/Y", $numeros[$vuelo["dia"]]);
        }
    }
    
    private function imprimirAsientos($cantidadDeAsientosPorTipo, $asientosOcupadosDelVuelo)
    {
        
        $res = array();
        //Si tengo una sola reserva, el for hace cualquier cosa asi que chequeo
        if (isset($asientosOcupadosDelVuelo["tipoAsiento"]) || $asientosOcupadosDelVuelo["tipoAsiento"] == NULL) {
            if ($asientosOcupadosDelVuelo["tipoAsiento"] != NULL) {
                $asientosOcupadosDelVueloDos[$asientosOcupadosDelVuelo ['tipoAsiento']][$asientosOcupadosDelVuelo ['numeroAsiento']] = $asientosOcupadosDelVuelo['numeroAsiento'];
            }
        } else {
            for ($i = 0; $i < sizeof($asientosOcupadosDelVuelo); $i++) {
                $indice = $asientosOcupadosDelVuelo[$i];
                $asientosOcupadosDelVueloDos[$indice ['tipoAsiento']][$indice ['numeroAsiento']] = $indice['numeroAsiento'];
            }
        }
        
        $res["general"] = $res["familiar"] = $res["suite"] = "";
        for ($i = 0; $i < $cantidadDeAsientosPorTipo["cap_gen"]; $i++) {
            if (isset($asientosOcupadosDelVueloDos['general'][$i])) {
                $res["general"] .= '<div class="asientos"><input type="radio" id="general' . $i . '" name="general" value="' . $i . '" disabled>
                                     <label for="general' . $i . '">' . $i . '</label></div>';
            } else {
                $res["general"] .= '<div class="asientos"><input type="radio" id="general' . $i . '" name="general" value="' . $i . '">
                                       <label for="general' . $i . '">' . $i . '</label> </div>';
            }
        }
        
        for ($i = 0; $i < $cantidadDeAsientosPorTipo["cap_fam"]; $i++) {
            if (isset($asientosOcupadosDelVueloDos['familiar'][$i])) {
                $res["familiar"] .= '<div class="asientos"><input type="radio" id="familiar' . $i . '" name="familiar" value="' . $i . '" disabled>
                                   <label for="familiar' . $i . '">' . $i . '</label></div>';
            } else {
                $res["familiar"] .= '<div class="asientos"><input type="radio" id="familiar' . $i . '" name="familiar" value="' . $i . '">
                                   <label for="familiar' . $i . '">' . $i . '</label></div>';
            }
        }
        
        for ($i = 0; $i < $cantidadDeAsientosPorTipo["cap_sui"]; $i++) {
            if (isset($asientosOcupadosDelVueloDos['suite'][$i])) {
                $res["suite"] .= '<div class="asientos"><input type="radio" id="suite' . $i . '" name="suite" value="' . $i . '" disabled>
                                   <label for="suite' . $i . '">' . $i . '</label></div>';
            } else {
                $res["suite"] .= '<div class="asientos"><input type="radio" id="suite' . $i . '" name="suite" value="' . $i . '">
                                   <label for="suite' . $i . '">' . $i . '</label></div>';
            }
        }
        
        return $res;
        
    }
    
}
