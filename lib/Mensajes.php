<?php
/**
 * Manejo de Mensajes
 *
 * Contiene los mensajes
 *
 * @package Mensajes
 *
 */
namespace lib;

/**
 * Es la clase que maneja los mensajes
 *
 * Contiene los mensajes para el sistema de transferencia de archivos.
 *
 * @package Mensajes
 * @copyright
 * @version 1.0
 *
 */
class Mensajes extends MensajesNucleo {

	/**
	 * Constante que define el estado 'CONFIRMADO' de una transferencia
	 *
	 * @property static
	 * @access private
	 * @var string
	 */
	private static $mensajes = array (
										"000" => "No se pudo enviar el mail",
										"001" => "Faltan definir parametros (#0#)",

	);

	/**
	 * Devuelve el mensaje correspondiente
	 *
	 * Dado un codigo de mensaje y una lista de parametros retorna el mensaje dado por el codigo
	 * y formateado con los parametros
	 *
	 * @method static
	 * @access public
	 * @param string $codigoMensaje id de mensaje a devolver
	 * @param array $params parametros para formatear con el mensaje
	 * @return string mensaje
	 */
	public static function getMensaje($codigoMensaje = "", $params = array()) {
		$mensaje = self::$mensajes [$codigoMensaje];
		foreach ( $params as $k => $v ) {
			$mensaje = preg_replace ( "/#$k#/", $v, $mensaje );
		}
		return mb_convert_encoding($mensaje, "UTF-8", "HTML-ENTITIES");
		//return $mensaje;
	}
}
