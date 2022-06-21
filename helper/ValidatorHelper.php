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
    //Gente, agregue esta funcion ára validar fechas
    public static function validarFecha($fecha){
        $valores = explode('/', $fecha); //Este explora el dato y separa los valores entre los / EJ de como quedaria el array: dd,mm,aaaa
        if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){ //cuenta los valores si son 3,
            // y hace una funcion check q encontre en internet pero no se que hace dentro jeje
            return true;
        }
        return false;
    }


}