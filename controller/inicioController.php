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
            $menu ="<a href='/logIn/exit'>Cerrar Sesion</a>";
          }else{
            $menu ="<a href='/registro'>Registrarse</a>
            <a href='/logIn'>Ingresar</a>";
          }
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
                      $whereVuelta = "OR (origen = '$destino' and destino = '$origen' and id_tipo= '$tipoVuelo' and fecha<='$fechaVuelta' and fecha>'$fechaIda')";
                    }                    
                  }else{
                    $whereVuelta = "";
                  }
                  $busqueda = "(origen = '$origen' and destino = '$destino' and id_tipo= '$tipoVuelo' and fecha>='$fechaIda') $whereVuelta";
            }else{
                  $busqueda= "";
            }
          }else{
            $busqueda= "";
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