<?php
session_start();
include_once("config/Configuration.php");

$module = isset($_GET["module"]) ? $_GET["module"] : "reservas" ;
$action = isset($_GET["action"]) ? $_GET["action"] : "show" ;

$configuration = new Configuration();
$router = $configuration->createRouter( "createReservasController", "show");

$router->executeActionFromModule($module,$action);