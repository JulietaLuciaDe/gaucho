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
            $ida = $_POST["ida"];
            $fechaIda = $_POST["fechaIda"];
            $fechaVuelta = $_POST["fechaVuelta"];
            $busqueda = $origen;
          }else{
            $busqueda= "";
          }
        $resultado = $this->inicioModel->buscar($busqueda);
        $data = ["menu"=> $menu,"resultado" => $resultado];
        
        $this->printer->generateView('inicioView.html',$data);
    }
}