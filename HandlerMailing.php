<?php

    class HandlerMailing{
        private $ruta_configuracion = null;
        private $ambiente           = null;
        private $basedatos          = null;

        public function __construct( $ruta_configuracion = "", $ambiente = "" ){
            try{
                $this->ruta_configuracion 	= $ruta_configuracion;
    			$this->ambiente 			= $ambiente;
    			$this->configuracion 		= new Configuracion ( $this->ruta_configuracion, $this->ambiente );
                $this->basedatos 			= new DatabaseExt ( $this->ruta_configuracion, $this->ambiente );
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

                    $mail = $this->obtenerMailCamania($idcampania); // TODO: obtenerMailCamania()
                    $destinatarios = $this->obtenerDestinatariosCampania( $idcampania ); // TODO: obtenerDestinatariosCampania()
                    $resMail = $this->mail->enviarMail( $destinatrio, $mail ); // TODO: clase Mail->enviarMail()

                }
            }
            catch(Exception $e ) {
			    //Logs::error_log($e->getMessage(), __METHOD__ . "::" .__LINE__);
                $this->error = 1;
            }
        }
    }
