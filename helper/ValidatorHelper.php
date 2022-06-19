<?php

class ValidatorHelper
{
    public static function validarNoEsVacio($variable){
        return !empty($variable);
    }

    public static function validarCaracteresMax($variable, $cantCaractMax){
        return strlen($variable)<$cantCaractMax;
    }

    public static function validarSiEstaSet($variable){
        return isset($variable);
    }

    public static function validarSiEsNumerico($number){
        return is_numeric($number);
    }

    public static function validarSeteadoYNoVacio($string){
        return self::validarNoEsVacio($string)&&
               self::validarSiEstaSet($string);
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