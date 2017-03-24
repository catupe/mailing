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
			    //Logs::error_log($e->getMessage(), __METHOD__ . "::" .__LINE__);
                $this->error = 1;
            }
        }
        /*
         *  Obtiene las campanias de mails activas y con fecha de fin de campania superior a la actual
         *  para un cliente determinado
         */
        public function obtenerCampaniasActivas( $cliente = "" ){
            try{
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
			    //Logs::error_log($e->getMessage(), __METHOD__ . "::" .__LINE__);
                $this->error = 1;
            }
        }
        public function obtenerMailCampania( $idcampania = null ){
            try{
                if(!isset($idcampania)){
                    throw new Exception("HandlerMailing::Error Processing Request", 1);
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
			    //Logs::error_log($e->getMessage(), __METHOD__ . "::" .__LINE__);
                $this->error = 1;
            }
        }
        public function obtenerDestinatariosCampania( $idcampania = null ){
            try{
                if(!isset($idcampania)){
                    throw new Exception("HandlerMailing::Error Processing Request", 1);
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
			    //Logs::error_log($e->getMessage(), __METHOD__ . "::" .__LINE__);
                $this->error = 1;
            }
        }
        private function cargarMail( $mail = array(), $tags = array() ){
            try{
                // TODO: reemlazar tags
                $body = file_get_contents ( $mail->cuerpo; );
            }
            catch(Exception $e ) {
                //Logs::error_log($e->getMessage(), __METHOD__ . "::" .__LINE__);
                $this->error = 1;
            }
        }
        public function enviarMails( $destinatrio, $mail ){
            try{
                $body = $this->cargarMail( $mail, array() );
                foreach ($destinatario as $key => $value) {
                    // mail->enviarMail($to, $body, $subject, $from, $replyto, $cc, $bcc)
                    $this->mail->enviarMail($value->email, $body, $mail->asunto, $mail->from, $mail->replyto, $mail->cc, $mail->bcc);
                }
            }
            catch(Exception $e ) {
                //Logs::error_log($e->getMessage(), __METHOD__ . "::" .__LINE__);
                $this->error = 1;
            }
        }
        /**
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
            }
            catch(Exception $e ) {
			    //Logs::error_log($e->getMessage(), __METHOD__ . "::" .__LINE__);
                $this->error = 1;
            }
        }
    }
