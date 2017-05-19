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

            echo("----------------------------------------<br>");
            echo("voy a mandar mail con la siguiente info<br>");
            echo("to:      " . $to . "<br>");
            echo("subject: " . $subject . "<br>");
            //echo("body:    " . $body . "<br>");
            //error_log("body: " . $header);
            echo("----------------------------------------" . "<br>");
            /*
            if(mail($to , $subject , $body , $header )){
                throw new Exception(__CLASS__ . "::" . __METHOD__ . " - line " . __LINE__ . " - :: " . Mensajes::getMensaje( '000', array() ), 1);
            }
            */
            return array("error" => 0);
        }
    }
