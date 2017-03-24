<?php
    namespace lib;

    class HandlerMailing{
        private $ruta_configuracion = null;
        private $ambiente           = null;
        private $basedatos          = null;
        private $mail               = null;

        public function __construct( $ruta_configuracion = "", $ambiente = "" ){
            try{
                $this->ruta_configuracion 	= $ruta_configuracion;
    			$this->ambiente 			= $ambiente;
    			$this->configuracion 		= new Configuracion ( $this->ruta_configuracion, $this->ambiente );
                $this->basedatos 			= new Database ( $this->ruta_configuracion, $this->ambiente );
                $this->mail                 = new Mail();
            }
            catch(Exception $e ) {
			    //Logs::error_log($e->getMessage(), __METHOD__ . "::" .__LINE__);
                $this->error = 1;
            }
        }
        /*
         *  Obtiene los clientes activos
         */
        public function obtenerClientesActivos(){
            try{
                $consulta = ' SELECT *         '.
                            ' FROM cliente     '.
                            ' WHERE activo = 1 ';

            }
            catch(Exception $e ) {
			   $this->error = 1;
               throw $e;
            }
        }
        /*
         *  Obtiene las campanias de mails activas y con fecha de fin de campania superior a la actual
         *  para un cliente determinado
         */
        public function obtenerCampaniasActivas( $cliente = "" ){
            try{

                //// chequeo de parametros
                if( strcmp($cliente, "") == 0 ){
                    throw new Exception(__CLASS__ . "::" . __METHOD__ . " - line " . __LINE__ . " - :: " . Mensajes::getMensaje( '001', array('cliente') ), 1);
                }
                // obtengo las campanias para un cliente determinado
                $consulta = ' SELECT *                              '.
                            ' FROM campania                         '.
                            ' WHERE activa = 1 AND                  '.
                            '       fecha_fin_campania >= NOW() AND '.
                            '       id_cliente = ?                  ';

                $rows	  = $this->basedatos->ExecuteQuery($consulta, array( $cliente ));

                $salida = array();
                foreach ( $rows as $k => $v){
                    $salida[$v->id] = $v;
                }
                return $salida;
            }
            catch(Exception $e ) {
			    $this->error = 1;
                throw $e;
            }
        }
        public function obtenerMailCampania( $idcampania = null ){
            try{

                //// chequeo de parametros
                if(!isset($idcampania) or empty($idcampania)){
                    throw new Exception(__CLASS__ . "::" . __METHOD__ . " - line " . __LINE__ . " - :: " . Mensajes::getMensaje( '001', array('idcampania') ), 1);
                }
                $consulta = '   SELECT *                                '.
                            '   FROM mailing m, campania c, cliente cl  '.
                            '   WHERE m.id_campania = c.id AND          '.
                            '    	  c.id_cliente = cl.id AND          '.
                            '         c.id = ?                          ';

                $row = $this->basedatos->ExecuteQuery($consulta, array( $idcampania ));
                return $row[0];
            }
            catch(Exception $e ) {
			    $this->error = 1;
                throw $e;
            }
        }
        public function obtenerDestinatariosCampania( $idcampania = null ){
            try{

                //// chequeo de parametros
                if(!isset($idcampania) or empty($idcampania)){
                    throw new Exception(__CLASS__ . "::" . __METHOD__ . " - line " . __LINE__ . " - :: " . Mensajes::getMensaje( '001', array('idcampania') ), 1);
                }
                $consulta = ' SELECT d.*                            '.
                            ' FROM destinatario d, item_campania ic '.
                            ' WHERE d.id = ic.id_destinatario AND   '.
                            '       ic.id_campania = ?              ';

                $rows	  = $this->basedatos->ExecuteQuery($consulta, array( $idcampania ));
                $salida = array();
                foreach ( $rows as $k => $v){
                    $salida[$v->id] = $v;
                }
                return $salida;
            }
            catch(Exception $e ) {
			    $this->error = 1;
                throw $e;
            }
        }
        //private function cargarMail( $mail = array(), $tags = array() ){
        public function cargarMail( $mail = array(), $tags = array() ){
            try{

                //// chequeo de parametros
                if( !isset($mail) or empty($mail) ){
                    throw new Exception(__CLASS__ . "::" . __METHOD__ . " - line " . __LINE__ . " - :: " . Mensajes::getMensaje( '001', array('mail') ), 1);
                }

                // TODO: reemlazar tags
                $path = $this->configuracion->getDato('mailing.path');
                $path2mail = $path . DIRECTORY_SEPARATOR . $mail->cuerpo;
                $body = file_get_contents ( $path2mail );

                return $body;
            }
            catch(Exception $e ) {
                $this->error = 1;
                throw $e;
            }
        }
        public function enviarMails( $destinatrio = null, $mail = null ){
            try{

                //// chequeo de parametros
                if( !isset($destinatario) or empty($destinatario) ){
                    throw new Exception(__CLASS__ . "::" . __METHOD__ . " - line " . __LINE__ . " - :: " . Mensajes::getMensaje( '001', array('destinatrio') ), 1);
                }
                if( !isset($mail) or empty($mail) ){
                    throw new Exception(__CLASS__ . "::" . __METHOD__ . " - line " . __LINE__ . " - :: " . Mensajes::getMensaje( '001', array('mail') ), 1);
                }

                $body = $this->cargarMail( $mail, array() );
                foreach ($destinatario as $key => $value) {
                    // mail->enviarMail($to, $body, $subject, $from, $replyto, $cc, $bcc)
                    $this->mail->enviarMail($value->email, $body, $mail->asunto, $mail->from, $mail->replyto, $mail->cc, $mail->bcc);
                }
            }
            catch(Exception $e ) {
                $this->error = 1;
                throw $e;
            }
        }
        /**
        *   Envia las los mails a los destinatarios de las campanias activas
        *
        *   Recorro las campanias que esten activas y que las fechas sean adecuadas (?)
        *   para cada campania anterior, obtengo el mail asociado a la campania (tabla mailing),
        *   luego obtengo cada item_campania asociado a la campania ACTIVO y el destinatario asociado al item_campania
        *   y envio el mail correspondiente. Hay que generar un link asociado a la campania y el destiantario para
        *   incluir en el mail y permitir al usuario darse de baja de una campania
        */
        public function envioCampanias(){
            try{

                $campanias = $this->obtenerCampaniasActivas();

                foreach( $campanias as $idcampania => $campania ){

                    $mail = $this->obtenerMailCampania($idcampania); // TODO: obtenerMailCamania()
                    $destinatarios = $this->obtenerDestinatariosCampania( $idcampania ); // TODO: obtenerDestinatariosCampania()
                    $resMail = $this->enviarMails( $destinatrio, $mail ); // TODO: clase Mail->enviarMail()

                }

                return array( 'error' => 0 );
            }
            catch(Exception $e ) {
			    $this->error = 1;
                throw $e;
            }
        }
    }
