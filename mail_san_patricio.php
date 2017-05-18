<?php

  $destinatario = "rodriguezcfranco@gmail.com";
//  $sBCC=""; //envio como copias ocultas
  $asunto = "Distribuidora San Patricio - Oferta!!";
  $header = "From: San Patricio <contacto@sanpatricio.com.uy>\n";
  $header .= "Reply-To: no-responder@sanpatricio.com.uy\n";
  $header .= "Content-Type: text/html\n";
 // $header .= "BCC: <$sBCC>\n"; //aqui fijo el BCC
  $mensaje = file_get_contents ( "san_patricio.html" );

  mail($destinatario , $asunto , $mensaje , $header );