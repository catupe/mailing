<?php

// $name llega de la misma manera que en __autoload y aquí veremos qué hacer con ese nombre
// y como recuperar el archivo donde está la clase!

spl_autoload_register( function ($nombreDeClase){

    $nombreDeClase = ltrim($nombreDeClase, '\\');
    $nombreDeFichero  = '';
    $nombreDeEspacio = '';

    if ($ultimaPos = strrpos($nombreDeClase, '\\')) {

        $nombreDeEspacio = substr($nombreDeClase, 0, $ultimaPos);

        $nombreDeClase = substr($nombreDeClase, $ultimaPos + 1);

        $nombreDeFichero  = str_replace('\\', DIRECTORY_SEPARATOR, $nombreDeEspacio) . DIRECTORY_SEPARATOR;

    }
    $nombreDeFichero .= str_replace('_', DIRECTORY_SEPARATOR, $nombreDeClase) . '.php';

    require $nombreDeFichero;
});
