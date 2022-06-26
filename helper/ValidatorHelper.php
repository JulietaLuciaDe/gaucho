<?php

class ValidatorHelper
{
    public static function validarSesionActiva(){
        return (isset($_SESSION["logueado"]) && $_SESSION["logueado"]==1);
    }

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

    public static function devolverFechaFormatoLatino($fechaSql){
        $aux = explode("-",$fechaSql);
        return $aux[2]."-".$aux[1]."-".$aux[0];
    }

    public static function validacionDeFecha($fecha){ 
        //Se cambia el formato de la fecha para que lo tome bien el checkdate         
        $originalDate = $fecha;
        $DateTime = DateTime::createFromFormat('Y-m-d', $originalDate);
        $newDate = $DateTime->format('d-m-Y');

        $valores = explode('-', $newDate); //Este explora el dato y separa los valores entre los / EJ de como quedaria el array: dd,mm,aaaa
        if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){ //cuenta si son 3 los valores y verifica con el checkdate si es una fecha valida
            return true;
        }
        return false;
    }

}