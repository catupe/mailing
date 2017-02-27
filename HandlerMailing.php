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
    }
