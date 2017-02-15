<?php

  $destinatario = "mesa.alvaro@gmail.com, rodriguezcfranco@gmail.com";
  $asunto = "Prueba de envio de mail";
  $header = "From: Promociones Franco-Alvaro <mail-empresa@dominio-empresa.com>\n";
  $header .= "Reply-To: no-responder@dominio-empresa.com\n";
  $header .= "Content-Type: text/html\n";
  $mensaje = file_get_contents ( "body.html" );

  mail($destinatario , $asunto , $mensaje , $header );
