<?php

class ValidatorFechaYHora
{
    public static function getFechaYHoraActual(){
        date_default_timezone_set("America/Buenos_Aires");
        return date("m-d-Y h:i:s a", time());
    }
}