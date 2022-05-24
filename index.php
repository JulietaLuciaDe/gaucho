
<?php
include_once("helper/configuration.php");
include_once("helper/router.php");
$configuration = new Configuration();
$router = new Router($configuration, "getInicioController", "execute");
session_start();

/*Acá dijo el profe que podemos validar el tema del login cuando está
logueado o no el usuario
*/
$module = isset($_GET["module"])?  $_GET["module"] : "";
$method = isset($_GET["method"])?  $_GET["method"] : "";



$router->executeActionFromModule($module,$method);


?>