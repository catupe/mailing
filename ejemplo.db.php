<?php
    include "loader.php";

    use \lib\Database;
    use \lib\Configuracion;

    $ruta_cfg = "cfg/configuracion.ini";
    $ambiente = "desarrollo";

    $db = new Database($ruta_cfg, $ambiente);

    $consulta = ' SELECT *                              '.
                ' FROM campania                         '.
                ' WHERE activa = 1 AND                  '.
                '       fecha_fin_campania >= NOW() AND '.
                '       id_cliente = ?                  ';

    $rows	  = $db->ExecuteQuery($consulta, array( 1 ));

    echo "<pre>";
    var_dump($rows);
