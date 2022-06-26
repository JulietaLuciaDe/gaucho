<?php
 
class InicioController {
    private $printer;
    private $inicioModel;

    public function __construct($inicioModel,$printer) {
        $this->printer = $printer;
        $this->inicioModel = $inicioModel;
    }

    public function execute() {
        if(ValidatorHelper::validarSesionActiva()){
          $menu ="<p>Hola, ".$_SESSION['user']."</p>
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
          //TODO: BOTON SUBMIT NO LO RECONOCE POR EL JS, POR EL MOMENTO VALIDAMOS CON ORIGEN
        if(isset($_POST['origen'])){   
          //FALTA VALIDAR TIPOVUELO ( Y NO SÉ SI ES NECESARIO LOS RADIO TAMBIEN)  
          if(   ValidatorHelper::validacionDeTexto($_POST["origen"],20)&&
                ValidatorHelper::validacionDeTexto($_POST["destino"],20)&&
                ValidatorHelper::validacionDeFecha($_POST["fechaIda"])){
                  $origen= $_POST["origen"];
                  $destino= $_POST["destino"];
                  $tipoVuelo = $_POST["tipoVuelo"];
                  $ida = $_POST["ida"];
                  $fechaIda = $_POST["fechaIda"];
                  if($ida==1){
                    if(ValidatorHelper::validacionDeFecha($_POST["fechaVuelta"])){
                      $fechaVuelta = $_POST["fechaVuelta"];
                      $whereVuelta = "OR (V.origen = '$destino' and V.destino = '$origen' and TV.id= '$tipoVuelo' and V.fecha<='$fechaVuelta' and V.fecha>'$fechaIda')";
                    }                    
                  }else{
                    $whereVuelta = "";
                  }
                    $busqueda = "((V.origen = '$origen' and V.destino = '$destino' and V.tipoVuelofk1= '$tipoVuelo' and V.fecha>='$fechaIda') $whereVuelta)";
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
        if(!empty($resultado = $this->inicioModel->buscarVuelos($busqueda))){
            $data = ["menu"=> $menu,"resultado" => $resultado];
        }else{
         //Si al reservar un vuelo no esta reservado previamente, se debe crear en la base de datos:
         //$this->inicioModel->registrarVuelo($origen,$destino,$tipoVuelo/*,$ida, $idaVuelta*/,$fechaIda,$fechaVuelta);
            $data = ["menu"=> $menu,"resultado" => $resultado,"noData"=>true];
        }

        $tiposVuelo = $this->inicioModel->tiposVuelo();

        
        $data += ["tiposVuelo"=>$tiposVuelo];

        $this->printer->generateView('inicioView.html',$data);
        
    }
}