<?php
    namespace lib;
    
    use \lib\Mensajes;
    use \Exception;

    class Mail{
        public function enviarMail( $to         = "",
                                    $body       = "",
                                    $subject    = "",
                                    $from       = "",
                                    $replyto    = "",
                                    $cc         = "",
                                    $bcc        = ""
                                     ){

            $header  = "From: $from\n";
            $header .= "Reply-To: $replyto\n";
            $header .= "Content-Type: text/html\n";

            // Para enviar un correo HTML, debe establecerse la cabecera Content-type
            //$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
            //$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            //$mensaje = file_get_contents ( $body );

            error_log("----------------------------------------");
            error_log("voy a mandar mail con la siguiente info");
            error_log("to:      " . $to);
            error_log("subject: " . $subject);
            error_log("body:    " . $body);
            //error_log("body: " . $header);
            error_log("----------------------------------------");
            /*
            if(mail($to , $subject , $body , $header )){
                throw new Exception(__CLASS__ . "::" . __METHOD__ . " - line " . __LINE__ . " - :: " . Mensajes::getMensaje( '000', array() ), 1);
            }
            */
            return array("error" => 0);
        }
    }
