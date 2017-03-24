<?php
namespace lib;

use \lib\Configuracion;
//use Exception;
use PDO;
use PDOException;

//putenv("INFORMIXDIR=/opt/informix");
//require "Configuracion.php";

class Database{

	protected $confguracion = null;
	protected $connection	= null;
	protected $nonQueryStmt	= null;
	protected $queryStmt	= null;

	protected $database_host   = null;
	protected $database_dbname = null;
	protected $server		   = null;
	protected $service	 	   = null;
	protected $protocol	 	   = null;
	protected $username 	   = null;
	protected $password	 	   = null;

	function __construct(/*$database_host, $database_dbname, $server, $service = 1526, $protocol = 'onsoctcp', $username, $password*/){
		try{
			$database_host 		= "";
			$database_dbname 	= "";
			$server				= "";
			$service 			= 1526;
			$protocol 			= 'onsoctcp';
			$username 			= "";
			$password 			= "";
			if(func_num_args() == 2){
				/* se recibe como parametro la ruta al archivo de confirguarion y el ambiente */
				$params 			 = func_get_args();
				$ruta_configuracion  = $params[0];
				$ambiente 		 	 = $params[1];

				$this->configuracion = new Configuracion($ruta_configuracion, $ambiente);

				/*
					$database_host 		 = $this->configuracion->getDato("database.host");
					$database_dbname	 = $this->configuracion->getDato("database.dbname");
					$server		 		 = $this->configuracion->getDato("database.server");
					$service	 		 = $this->configuracion->getDato("database.service");
					$protocol	 		 = $this->configuracion->getDato("database.protocol");
					$username 			 = $this->configuracion->getDato("database.username");
					$password	 		 = $this->configuracion->getDato("database.password");
					*/
				$this->database_host 	 = $this->configuracion->getDato("database.host");
				$this->database_dbname	 = $this->configuracion->getDato("database.dbname");
				$this->server		 	 = $this->configuracion->getDato("database.server");
				$this->service	 		 = $this->configuracion->getDato("database.service");
				$this->protocol	 		 = $this->configuracion->getDato("database.protocol");
				$this->username 		 = $this->configuracion->getDato("database.username");
				$this->password	 		 = $this->configuracion->getDato("database.password");
			}
			elseif(func_num_args() == 7){
				/* se reciben los parametros de conexion a la base de datos */
				$params 			 = func_get_args();
				/*
					$database_host 		 = $params[0];
					$database_dbname	 = $params[1];
					$server		 		 = $params[2];
					$service	 		 = $params[3];
					$protocol	 		 = $params[4];
					$username 			 = $params[5];
					$password	 		 = $params[6];
					*/
				$this->database_host 	 = $params[0];
				$this->database_dbname	 = $params[1];
				$this->server		 	 = $params[2];
				$this->service	 		 = $params[3];
				$this->protocol	 		 = $params[4];
				$this->username 		 = $params[5];
				$this->password	 		 = $params[6];
			}
			else{
				/* error */
				throw new PDOException("ERROR :: ". __CLASS__ . " :: " . __METHOD__ ." line ". __LINE__, 100);
			}
			//$connectionString = "informix:host=$database_host; service=$service;database=$database_dbname; server=$server; protocol=$protocol;EnableScrollableCursors=1, Autocommit=0";
			//$connectionString = "informix:host=$this->database_host; service=$this->service;database=$this->database_dbname; server=$this->server; protocol=$this->protocol;EnableScrollableCursors=1, Autocommit=0";
			$connectionString = "mysql:host=$this->database_host;dbname=$this->database_dbname";

			$this->connection = new PDO($connectionString, $this->username, $this->password);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

		}
		catch(PDOException $e){
			throw new PDOException( $e->getMessage( ) , (int)$e->getCode( ) );
		}
	}

	function BeginTransaction(){
		if(!$this->connection->beginTransaction()){
			throw new PDOException("ERROR :: ". __CLASS__ . " :: " . __METHOD__ ." line ". __LINE__, 100);
		}
	}

	function CommitTransaction(){
		if(!$this->connection->commit()){
			throw new PDOException("ERROR :: ". __CLASS__ . " :: " . __METHOD__ ." line ". __LINE__, 100);
		}
	}

	function RollBackTransaction(){
		if(!$this->connection->rollBack()){
			throw new PDOException("ERROR :: ". __CLASS__ . " :: " . __METHOD__ ." line ". __LINE__, 100);
		}
	}

	function ExecuteNonQuery($nonQueryStatement, $parameters, $returnLastId = false){
		try{
			$this->nonQueryStmt = $this->connection->prepare($nonQueryStatement);
			$res = $this->nonQueryStmt->execute($parameters);

			if($returnLastId){
				return $this->connection->lastInsertId();
			}
			else{
				return $res;
			}
		}
		catch(PDOException $e){
			throw new PDOException( $e->getMessage( ) , (int)$e->getCode( ) );
		}
	}

	function ExecuteLastNonQuery($parameters, $returnLastId = false){
		try{
			$res = $this->nonQueryStmt->execute($parameters);

			if($returnLastId){
				return $connection->lastInsertId();
			}
			else{
				return $res;
			}
		}
		catch(PDOException $e){
			throw new PDOException( $e->getMessage( ) , (int)$e->getCode( ) );
		}
	}

	function ExecuteQuery($queryStatement, $parameters){
		try{
			$this->queryStmt = $this->connection->prepare($queryStatement);
			$this->queryStmt->execute($parameters);
			$res = $this->queryStmt->fetchAll(PDO::FETCH_OBJ);
			foreach($res as $row){
				foreach($row as $clave => $valor){
					$aux1 = htmlentities($row->$clave, ENT_QUOTES, "UTF-8");
					if($aux1==""){
						$aux1 = htmlentities($row->$clave, ENT_QUOTES, "ISO-8859-1");
					}
					$row->$clave = $aux1;
				}
			}
			return $res;
		}
		catch(PDOException $e){
			throw new PDOException( $e->getMessage( ) , (int)$e->getCode( ) );
		}
	}

	function ExecuteQueryByte ($queryStatement, $parameters, $nombre_columna_byte=""){
		// Se le tiene que pasar el nombre de la columna qe contiene los byte
		// El nombre de la columna tiene que pertenecer al conjunto de columnas resultado de la conslta
		// No se pueden usar alias para la columna que trae los byte
		try{
			// Si no se pasa el nombre de la columna que contiene los datos tira una Excepcion
			if($nombre_columna_byte == ""){
				throw new PDOException("Falta especificar el nombre de la columna que contiene los byts", 666 );
			}
			$this->queryStmt = $this->connection->prepare($queryStatement);
			$this->queryStmt->execute($parameters);
			$this->queryStmt->bindColumn($nombre_columna_byte, $dato, PDO::PARAM_LOB);
			// Recorro el resultado de la consulta y le asigno a la columna $nombre_columna_byte el contenido de $dato
			$res = array();
			while ($fila = $this->queryStmt->fetch( PDO::FETCH_OBJ )) {
				foreach($fila as $clave => $valor){
					if(strcmp($clave,$nombre_columna_byte)==0){
						$texto = stream_get_contents($dato);
						$fila->$clave = $texto;
					}else{
						$aux1 = htmlentities($fila->$clave, ENT_QUOTES, "UTF-8");
						if($aux1==""){
							$aux1 = htmlentities($fila->$clave, ENT_QUOTES, "ISO-8859-1");
						}
						$fila->$clave = $aux1;
					}
				}
				$res[] = $fila;
			}
			return $res;
		}
		catch(PDOException $e){
			throw new PDOException( $e->getMessage( ) , (int)$e->getCode( ) );
		}
	}

	function ExecuteLastQuery($parameters){
		try{
			$this->queryStmt->execute($parameters);
			$res = $this->queryStmt->fetchAll(PDO::FETCH_OBJ);
			foreach($res as $row){
				foreach($row as $clave => $valor){
					$aux1 = htmlentities($row->$clave, ENT_QUOTES, "UTF-8");
					if($aux1==""){
						$aux1 = htmlentities($row->$clave, ENT_QUOTES, "ISO-8859-1");
					}
					$row->$clave = $aux1;
				}
			}
			return $res;
		}
		catch(PDOException $e){
			throw new PDOException( $e->getMessage( ) , (int)$e->getCode( ) );
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////////////
	////	Consulta para realizar paginado
	////	Devuelve un hash data  => registros a partir de ($pageNumber-1) * $rowsPerPage
	////					 total => cantidad de registros
	////	TODO: capaz sacar el total de aca
	///////////////////////////////////////////////////////////////////////////////////////////////
	function ExecutePaginationQuery($queryStatement, $parameters, $pageNumber = 1, $rowsPerPage = 1){
		try{
			//// preparo el select para paginar
			$skip 			= ( $pageNumber - 1 ) * $rowsPerPage;
			$reemplazo 		= " $1 SKIP $skip FIRST $rowsPerPage $2 $3 ";//" $1 SKIP $skip first $rowsPerPage $2 $3 ";
			$pattern 		= '/^(\s*SELECT\s+)(.*?)(\s+FROM)/i';//'/^(\s*SELECT)\s+(.*)\s+(FROM\s+.*)/i';
			$queryPage	 	= preg_replace($pattern, $reemplazo, $queryStatement);

			//// preparo select para total
			$reemplazo 		= " $1 count(*) as cantidad $3 ";
			$pattern 		= '/^(\s*SELECT\s+)(.*?)(\s+FROM)/i';
			$queryCount	 	= preg_replace($pattern, $reemplazo, $queryStatement);
			$pattern 		= '/(\s*ORDER\s+BY\s+.*\s*)$/i';
			$queryCount	 	= preg_replace($pattern, '', $queryCount);

			$this->queryStmt = $this->connection->prepare($queryCount);
			$this->queryStmt->execute($parameters);
			$resCount = $this->queryStmt->fetchAll(PDO::FETCH_OBJ);

			$this->queryStmt = $this->connection->prepare($queryPage);
			$this->queryStmt->execute($parameters);
			$res = $this->queryStmt->fetchAll(PDO::FETCH_OBJ);
			foreach($res as $row){
				foreach($row as $clave => $valor){
					$aux1 = htmlentities($row->$clave, ENT_QUOTES, "UTF-8");
					if($aux1==""){
						$aux1 = htmlentities($row->$clave, ENT_QUOTES, "ISO-8859-1");
					}
					$row->$clave = $aux1;
				}
			}
			return array( 'data' => $res,
					'total'=> $resCount[0]->cantidad);
		}
		catch(PDOException $e){
			throw new PDOException( $e->getMessage( ) , (int)$e->getCode( ) );
		}
	}

}
