
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'helper/PHPMailer/Exception.php';
require 'helper/PHPMailer/PHPMailer.php';
require 'helper/PHPMailer/SMTP.php';

include_once("helper/configuration.php");
include_once("helper/router.php");
include_once ("helper/ValidatorHelper.php");
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