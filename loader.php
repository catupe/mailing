<?php

// $name llega de la misma manera que en __autoload y aquí veremos qué hacer con ese nombre
// y como recuperar el archivo donde está la clase!

spl_autoload_register( function ($nombreDeClase){
    $nombreDeClase = ltrim($nombreDeClase, '\\');
    error_log("1 -> " . $nombreDeClase);
    $nombreDeFichero  = '';
    $nombreDeEspacio = '';
    if ($ultimaPos = strrpos($nombreDeClase, '\\')) {
        error_log("2 -> " . $ultimaPos);
        $nombreDeEspacio = substr($nombreDeClase, 0, $ultimaPos);
        error_log("3 -> " . $nombreDeEspacio);
        $nombreDeClase = substr($nombreDeClase, $ultimaPos + 1);
        error_log("4 -> " . $nombreDeClase);
        $nombreDeFichero  = str_replace('\\', DIRECTORY_SEPARATOR, $nombreDeEspacio) . DIRECTORY_SEPARATOR;
        error_log("5 -> " . $nombreDeFichero);
    }
    $nombreDeFichero .= str_replace('_', DIRECTORY_SEPARATOR, $nombreDeClase) . '.php';

    error_log("6 -> " .  $nombreDeFichero);

    require $nombreDeFichero;
});
