
<?php
include_once("helper/configuration.php");
include_once("helper/router.php");
$configuration = new Configuration();
$router = new Router($configuration, "getInicioController", "execute");


$module = isset($_GET["module"])?  $_GET["module"] : "";
$method = isset($_GET["method"])?  $_GET["method"] : "";



$router->executeActionFromModule($module,$method);


?>