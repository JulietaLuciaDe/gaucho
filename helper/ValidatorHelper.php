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

    public static function validacion($string,$cantCaract){
        return (self::validarNoEsVacio($string)
                && self::validarSiEstaSet($string))
                && self::validarCaracteresMax($string,$cantCaract);
    }
}