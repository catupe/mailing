<?php
    include "loader.php";

    use \lib\HandlerMailing;

    $ruta_cfg = "cfg/configuracion.ini";
    $ambiente = "desarrollo";

    $handler = new HandlerMailing($ruta_cfg, $ambiente);

    $res = $handler->obtenerCampaniasActivas( 1 );

    echo "<pre>";
    var_dump($res);
