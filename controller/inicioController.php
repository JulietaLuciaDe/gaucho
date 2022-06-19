<?php
 
class InicioController {
    private $printer;
    private $inicioModel;

    public function __construct($inicioModel,$printer) {
        $this->printer = $printer;
        $this->inicioModel = $inicioModel;
    }

    public function execute() {
        if(isset($_SESSION["logueado"]) && $_SESSION["logueado"]==1){
          $menu ="<p>Hola, ".$_SESSION['user']."</p>
                  <a href='/logIn/exit'>Cerrar Sesion</a>";
            if($_SESSION["nivel"]==1 || $_SESSION["nivel"]==2){
              $filtroNivel = " (id_tipo IN('OR','BA'))";
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
          //FALTA VALIDAR FECHA(AGREGAR EN EL VALIDATOR), TIPOVUELO ( Y NO SÉ SI ES NECESARIO LOS RADIO TAMBIEN)  
          if(   ValidatorHelper::validacionDeTexto($_POST["origen"],20)&&
                ValidatorHelper::validacionDeTexto($_POST["destino"],20)){
                  $origen= $_POST["origen"];
                  $destino= $_POST["destino"];
                  $tipoVuelo = $_POST["tipoVuelo"];
                  $ida = $_POST["ida"];
                  $fechaIda = $_POST["fechaIda"];
                  if($ida==1){
                    if(isset($_POST["fechaVuelta"]) && !empty($_POST["fechaVuelta"])){
                      $fechaVuelta = $_POST["fechaVuelta"];
                      $whereVuelta = "OR (V.origen = '$destino' and V.destino = '$origen' and T.id= '$tipoVuelo' and V.fecha<='$fechaVuelta' and V.fecha>'$fechaIda')";
                    }                    
                  }else{
                    $whereVuelta = "";
                  }
                    $busqueda = "((V.origen = '$origen' and V.destino = '$destino' and T.id= '$tipoVuelo' and V.fecha>='$fechaIda') $whereVuelta)";
                    if(isset($_SESSION["logueado"]) && $_SESSION["logueado"]==1){
                      $busqueda = $busqueda." and ".$filtroNivel;
                    }
          }else{
                  $busqueda= $filtroNivel; 
          }
        }else{
            if(isset($_SESSION["logueado"]) && $_SESSION["logueado"]==1 && $_SESSION["nivel"]==""){
              $title = "Checkeo médico incompleto";
              $message = "Debe completar el checkeo médico para realizar reservas </br>
                          <a class='recovery' href='https://mail.google.com' target='blank'>Ir a mi correo</a></br>
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
        if(!empty($resultado = $this->inicioModel->buscar($busqueda))){
              $data = ["menu"=> $menu,"resultado" => $resultado];
          }else{
            $data = ["menu"=> $menu,"resultado" => $resultado,"noData"=>true];
            
//Si al buscar un vuelo no esta, se debe crear en la base de datos:
           //   $this->inicioModel->registrarVuelo($origen,$destino,$tipoVuelo/*,$ida, $idaVuelta*/,$fechaIda,$fechaVuelta);
          }
        $this->printer->generateView('inicioView.html',$data);
        
    }
}