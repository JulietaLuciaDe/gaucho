<?php

class ValidatorHelper
{
    public static function validarNoEsVacio($string){
        return !empty($string);
    }

    public static function validarCaracteresMax($string, $cantCaractMax){
        return strlen($string)<$cantCaractMax;
    }

    public static function validarSiEstaSet($string){
        return isset($string);
    }

    public static function validarSiEsNumerico($number){
        return is_numeric($number);
    }

    public static function validarSiEsFecha($date){
        $fecha = $date;
        $valores = explode('/', $fecha);
        $is_date = checkdate($valores[1], $valores[0], $valores[2]);
        return $is_date;
    }

    public static function validacionDeNumeros($number,$cantCaract){
        return
            (   self::validarSiEsNumerico($number)  &&  self::validarNoEsVacio($number))
            &&( self::validarSiEstaSet($number)     &&  self::validarCaracteresMax($number,$cantCaract));
    }

    public static function validacionDeTexto($string, $cantCaract){
        return
            (   self::validarNoEsVacio($string)
            &&  self::validarSiEstaSet($string))
            &&  self::validarCaracteresMax($string,$cantCaract);
    }

    public static function validacionDeFecha($date){
        return
            (   self::validarNoEsVacio($date)
            &&  self::validarSiEstaSet($date))
            &&  self::validarSiEsFecha($date);
    }
}