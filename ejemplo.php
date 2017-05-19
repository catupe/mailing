<?php
    include "loader.php";

    use \lib\HandlerMailing;

    $ruta_cfg = "cfg/configuracion.ini";
    $ambiente = "desarrollo";

    echo "<pre>";
    $handler = new HandlerMailing($ruta_cfg, $ambiente);

    //$mail = $handler->obtenerMailCampania(1); // TODO: obtenerMailCamania()
    //$res  = $handler->cargarMail( $mail );
    //$res = $handler->envioCampanias(1);
    $res = $handler->envioCampania(1,1);

    echo "<pre>";
    var_dump($res);
