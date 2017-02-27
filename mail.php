<?php

    class Mail{

        public function enviar( $to         = "",
                                $body       = "",
                                $subject    = "",
                                $from       = "",
                                $replyto     = "",
                                $cc         = "",
                                $bcc        = "" ){

            $header  = "From: $from\n";
            $header .= "Reply-To: $replyto\n";
            $header .= "Content-Type: text/html\n";

            // Para enviar un correo HTML, debe establecerse la cabecera Content-type
            //$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
            //$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            $mensaje = file_get_contents ( $body );

            if(mail($to , $subject , $mensaje , $header )){
                return array("error" => 0);
            }

            return array("error" => 1);
        }
    }
