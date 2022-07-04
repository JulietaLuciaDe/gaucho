<?php
 require_once  'dompdf/autoload.inc.php';
 use Dompdf\Dompdf;
 
class InicioController {
    private $printer;
    private $inicioModel;
    private $pdf;

    public function __construct($inicioModel,$printer) {
        $this->printer = $printer;
        $this->inicioModel = $inicioModel;
    }

    public function execute() {
        //VERIFICA SI ESTA LOGUEADO PARA MENU DINAMICO Y (EN CASO QUE SI) QUE NIVEL TIENE PARA FILTRAR X TIPO EQUIPO
        if(ValidatorHelper::validarSesionActiva()){
          $menu ="<p>Hola, ".$_SESSION['user']."</p>
                  <a href='/misReservas/execute'>Mis Reservas</a>
                  <a href='/logIn/exit'>Cerrar Sesion</a>";
            if($_SESSION["nivel"]==1 || $_SESSION["nivel"]==2){
              $filtroNivel = "(T.id IN('OR','BA'))";
            }else{
              $filtroNivel = "1";
            }
          }else{
            $menu ="<a href='/registro'>Registrarse</a>
            <a href='/logIn'>Ingresar</a>";
            $filtroNivel = "1";
          }
///////////////////////////////////////////////////////7
          //SI ACTIVA LA BUSQUEDA
        if(isset($_POST['botonBuscar'])){
            $busqueda = '';
            $flag = 0;
            if(ValidatorHelper::validacionDeTexto($_POST['origen'],50)){
                $origen = $_POST['origen'];
                $flag =1;
                $busqueda=$busqueda."(V.origen = (Select id_destino from destinos where descripcion = '".$origen."')) ";
            }
            if(ValidatorHelper::validacionDeTexto($_POST['destino'],50)){
                $destino = $_POST['destino'];
                if($flag==1){
                    $busqueda = $busqueda . ' and ';
                }
                $flag =1;
                $busqueda=$busqueda."(V.destino = (Select id_destino from destinos where descripcion= '".$destino."')) ";
            }
            if(isset($_POST['fechaBusqueda']) && $_POST['fechaBusqueda']!=''){
                $fecha = $_POST['fechaBusqueda'];
                if($flag==1){
                    $busqueda = $busqueda . ' and ';
                }
                $flag =1;
                $busqueda=$busqueda."(V.fecha>='$fecha') ";
            }
            if(ValidatorHelper::validacionDeTexto($_POST['tipoVuelo'],3)){
                $tipoVuelo = $_POST['tipoVuelo'];
                if($flag==1){
                    $busqueda = $busqueda . ' and ';
                }
                $flag =1;
                $busqueda=$busqueda."(V.tipoVuelofk1= '$tipoVuelo') ";
            }
            if(ValidatorHelper::validacionDeTexto($_POST['tipoEquipo'],11)){
                $tipoEquipo = $_POST['tipoEquipo'];
                if($flag==1){
                    $busqueda = $busqueda . ' and ';
                }
                $flag =1;
                $busqueda=$busqueda."(T.id= '$tipoEquipo') ";
            }
            if($flag==1){
            $busqueda = $busqueda . ' and ';
            }
            $busqueda = $busqueda.$filtroNivel;

            //INICIO SIN ACTIVAR BUSQUEDA
        }else{
                //SI ESTA LOGUEADO Y LE FALTA EL CHECKEO MEDICO, LO INFORMA
            if((ValidatorHelper::validarSesionActiva()) && $_SESSION["nivel"]==""){
              $md5Email = md5($_SESSION["usuario"]);
              $title = "Checkeo médico incompleto";
              $message = "Debe solicitar un turno médico para poder navegar como usuario logueado </br>
                          <a class='recovery' href='/registro/solicitarTurno/email=".$_SESSION['usuario']."&hash=".$md5Email."'>Solicitar Turno</a>
                          <a class='recovery' href='/logIn/exit'>Cerrar Sesion</a>
                          ";
              $display = "d-block";
              $displaySearch = "d-none";
              $data = ["menu"=>$menu,"popUp" => true,"title"=> $title,"message"=>$message,"display"=>$display,"displaySearch"=>$displaySearch];
              $this->printer->generateView('inicioView.html',$data);
              exit();
              
            }else{
                $busqueda= $filtroNivel;
            }
            
        }
        //HACE LA BUSQUEDA PASANDOLE TODO EL TEXTO DEL WHERE POR PARAMETRO Y ARMA EL DATA
        if(!empty($resultado = $this->inicioModel->buscarVuelos($busqueda))){
            $data = ["menu"=> $menu,"resultado" => $resultado];
        }else{ 
            $data = ["menu"=> $menu,"resultado" => $resultado,"noData"=>true];
        }

        //AGREGA AL DATA MATCHEO DE TIPOSVUELO Y TIPOEQUIPO PARA QUE SE MUESTREN LAS DESCRIPCIONES EN LOS SELECT DEL BUSCADOR
        $tiposVuelo = $this->inicioModel->tiposVuelo();
        $tiposEquipo = $this->inicioModel->tiposEquipo();
        $data += ["tiposVuelo"=>$tiposVuelo];
        $data += ["tiposEquipo"=>$tiposEquipo];


        $this->printer->generateView('inicioView.html',$data);
        
    }


}