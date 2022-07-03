<?php
include_once("helper/ValidatorHelper.php");
date_default_timezone_set("America/Buenos_Aires");
class FechaYHoraHelper
{
    public static function getFechaYHoraActual(){
        return date("d-m-Y H:i", time());
    }

    public static function getFechaActual(){
        return date("d-m-Y");
    }

    public static function getHoraActual(){
        return date("H:i",time());
    }

    public static function getFecha($ddmmyyyy){
        return date($ddmmyyyy);
    }

    public static function validarSiYaPasoLaFecha($fechaAComparar){
        return strtotime(FechaYHoraHelper::getFechaActual())>strtotime(self::getFecha($fechaAComparar));
    }

    public static function sumarHoras($fechaYHora,$cantHoras){
        $fecha = new DateTime($fechaYHora);
        if($cantHoras>0){
            if($cantHoras==1){
                $fecha->modify("+".$cantHoras." hour");
            }else{
                $fecha->modify("+".$cantHoras." hours");
            }
        }
        //POR AHORA LE CAMBIE EL FORMATO PARA QUE NO ROMPA
        // return $fecha->format("d-m-Y H:i");
        return $fecha->format("Y-m-d H:i");
    }
}