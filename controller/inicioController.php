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
            $origen= $_POST["origen"];
            $destino= $_POST["destino"];
            $tipoVuelo = $_POST["tipoVuelo"];
//Agregar en la base de datos de Vuelos dos tipos de Fechas y uno de Ida?
            $ida = $_POST["ida"];
            $idaVuelta = $_POST["idaVuelta"];
            $fechaIda = $_POST["fechaIda"];
            $fechaVuelta = $_POST["fechaVuelta"];

            $busqueda = $origen;
          }else{
            $busqueda= "";
          }
          if($resultado = $this->inicioModel->buscar($busqueda)){//Aca hay un tema, siempre retorna
              $data = ["menu"=> $menu,"resultado" => $resultado];
          }else{
//Si al buscar un vuelo no esta, se debe crear en la base de datos:
              $this->inicioModel->registrarVuelo($origen,$destino,$tipoVuelo/*,$ida, $idaVuelta*/,$fechaIda,$fechaVuelta);
          }
        $this->printer->generateView('inicioView.html',$data);
    }
}