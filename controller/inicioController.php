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
          
         if(isset($_POST["busqueda"])){
          //ACÁ HAY QUE AGREGAR LAS VALIDACIONES CON EL VALIDATOR
          
            if(true){
                  $origen= $_POST["origen"];
                  $destino= $_POST["destino"];
                  $tipoVuelo = $_POST["tipoVuelo"];
                  $ida = $_POST["ida"];
                  $fechaIda = $_POST["fechaIda"];
                  if($ida=="1"){
                    if(isset($_POST["fechaVuelta"]) && !empty($_POST["fechaVuelta"])){
                      $fechaVuelta = $_POST["fechaVuelta"];
                      $whereVuelta = "OR (origen = '$destino' and destino = '$origen' and id_tipo= '$tipoVuelo' and fecha<='$fechaVuelta' and fecha>'$fechaIda')";
                    }else{
                      //VER COMO METER UN ALERT ACA (UN POPUP ME PARECE QUE NO QUEDA)
                      echo "DEBE INGRESAR UNA FECHA DE VUELTA";
                    }
                    
                  }else{
                    $whereVuelta = "";
                  }
                  $busqueda = "(origen = '$origen' and destino = '$destino' and id_tipo= '$tipoVuelo' and fecha>='$fechaIda') $whereVuelta";
            }else{
                  $busqueda = "";
            }
                
          }else{
            $busqueda= "";
          }
          if($resultado = $this->inicioModel->buscar($busqueda)){
              $data = ["menu"=> $menu,"resultado" => $resultado];
          }else{
//Si al buscar un vuelo no esta, se debe crear en la base de datos:
              $this->inicioModel->registrarVuelo($origen,$destino,$tipoVuelo/*,$ida, $idaVuelta*/,$fechaIda,$fechaVuelta);
          }
        $this->printer->generateView('inicioView.html',$data);
    }
}