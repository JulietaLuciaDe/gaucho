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
        if(ValidatorHelper::validarSesionActiva()){
          $menu ="<p>Hola, ".$_SESSION['user']."</p>
                  <a href='/misReservas/execute'>Mis Reservas</a>
                  <a href='/logIn/exit'>Cerrar Sesion</a>";
            if($_SESSION["nivel"]==1 || $_SESSION["nivel"]==2){
              $filtroNivel = " (T.id IN('OR','BA'))";
            }else{
              $filtroNivel = "1";
            }
           
          }else{
            $menu ="<a href='/registro'>Registrarse</a>
            <a href='/logIn'>Ingresar</a>";
            $filtroNivel = "1";
          }
          //BOTON SUBMIT NO LO RECONOCE POR EL JS, POR EL MOMENTO VALIDAMOS CON ORIGEN
        if(isset($_POST['origen'])){   
          //FALTA VALIDAR TIPOVUELO ( Y NO SÉ SI ES NECESARIO LOS RADIO TAMBIEN)  
          if(isset($_POST['origen'] )){
                  $origen= $_POST["origen"];
                  $destino= $_POST["destino"];
                  $tipoVuelo = $_POST["tipoVuelo"];
                  $tipoEquipo = $_POST["tipoEquipo"];
                  //$ida = $_POST["ida"];
                  $fechaIda = $_POST["fechaIda"];
                  /*if($ida==1){
                    if(ValidatorHelper::validacionDeFecha($_POST["fechaVuelta"])){
                      $fechaVuelta = $_POST["fechaVuelta"];
                      $whereVuelta = "OR (V.origen = '$destino' and V.destino = '$origen' and TV.id= '$tipoVuelo' and V.fecha<='$fechaVuelta' and V.fecha>'$fechaIda')";
                    }                    
                  }else{
                    $whereVuelta = "";
                  }*/
                    $busqueda = "(V.origen = (Select id_destino from destinos where descripcion = '$origen') and V.destino = (Select id_destino from destinos where descripcion = '$destino') and V.tipoVuelofk1= '$tipoVuelo' and T.id= '$tipoEquipo' and V.fecha>='$fechaIda')";
                    if(ValidatorHelper::validarSesionActiva()){
                      $busqueda = $busqueda." and ".$filtroNivel;
                    }
          }else{
                  $busqueda= $filtroNivel; 
          }
        }else{
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
        if(!empty($resultado = $this->validarCamposDeBusqueda())){
            $data = ["menu"=> $menu,"resultado" => $resultado];
        }else{ 
            $data = ["menu"=> $menu,"resultado" => $resultado,"noData"=>true];
        }

        $tiposVuelo = $this->inicioModel->tiposVuelo();
        $tiposEquipo = $this->inicioModel->tiposEquipo();
        
        $data += ["tiposVuelo"=>$tiposVuelo];
        $data += ["tiposEquipo"=>$tiposEquipo];
        $this->printer->generateView('inicioView.html',$data);
        
    }

    public function validarCamposDeBusqueda(){
        $camposABuscar = array( 'origen'=>'',
                                'destino'=>'',
                                'tipoVuelo'=>'',
                                'tipoEquipo'=>'',
                                'fechaIda'=>'');
        if(isset($_POST['origen']))
            $camposABuscar['origen'].=$_POST['origen'];
        if(isset($_POST['destino']))
            $camposABuscar['destino'].=$_POST['destino'];
        if(isset($_POST['tipoVuelo']))
            $camposABuscar['tipoVuelo'].=$_POST['tipoVuelo'];
        if(isset($_POST['tipoEquipo']))
            $camposABuscar['tipoEquipo'].=$_POST['tipoEquipo'];
        if(isset($_POST['fechaIda']))
            $camposABuscar['fechaIda'].=$_POST['fechaIda'];

        return $this->busquedaValidadaPorCampos($camposABuscar);
    }

    public function validarCamposDeBusquedaAsString(){
        $campos = $this->validarCamposDeBusqueda();
        $string =   "origen=".$campos['origen'].
                    "|destino=".$campos['destino'].
                    "|tipoVuelo=".$campos['tipoVuelo'].
                    "|tipoEquipo=".$campos['tipoEquipo'].
                    "|fechaIda=".$campos['fechaIda'];
        return $string;
    }

    private function busquedaValidadaPorCampos($camposABuscar){
        $arrayAux = array();
        $destinos = $this->inicioModel->getDestinos();
        $vuelos = $this->inicioModel->buscarVuelos("");

        foreach ($vuelos as $vuelo){
            foreach ($destinos as $destino){
                if($vuelo['origen']==$destino['id_destino']){
                    $vuelo['origen']=$destino['descripcion'];
                }
                if($vuelo['destino']==$destino['id_destino']){
                    $vuelo['destino']=$destino['descripcion'];
                }
            }
            if($vuelo['origen']==$camposABuscar['origen'])
                array_push($arrayAux,$vuelo);
            if($vuelo['destino']==$camposABuscar['destino'])
                array_push($arrayAux,$vuelo);
            if($vuelo['tipoVuelofk1']==$camposABuscar['tipoVuelo'])
                array_push($arrayAux,$vuelo);
            if($vuelo['fecha']==$camposABuscar['fechaIda'])
                array_push($arrayAux,$vuelo);
        }

        return $arrayAux;
    }

    public function generarPDF(){
      include_once("view/pdfView.html");
      $html = ob_get_clean();
      $nombrePDF= "CheckIn";
      $pdf = new Dompdf();//Inicializa
      $pdf->setPaper('A4','landscape');//Se ajusta el papel
      $pdf->loadHtml($html);//lo carga en la hoja el html
      $pdf->render();//renderiza de html a pdf
      $pdf->stream($nombrePDF, ['Attachment'=>1]);//genera el pdf en el navegador /false misma pag / true descarga
      //El ['Attachment'=>0] es para q lo genere en otra pestaña
    }
}