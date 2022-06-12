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
}