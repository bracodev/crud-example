<?php

require_once __DIR__ .'/config.php';

define('DB_MYSQL_DRIVER', 'mysql');
define('DB_MSSQL_DRIVER', 'mssql');
define('DB_PGSQL_DRIVER', 'pgsql');
define('DB_SQLITE_DRIVER', 'sqlite');

/**
* Esta clase permite crear conexiones con MySQL y hacer consultas a base de datos
*/
class Database 
{

	const 
		  DNS_MYSQL_DRIVER = 'mysql:dbname=@dbname;host=@host;port=@port;charset=@charset'
		, DNS_MSSQL_DRIVER = 'sqlsrv:Server=@host;Database=@dbname;ConnectionPooling=0'
		, DNS_PGSQL_DRIVER = 'pgsql:dbname=@dbname;port=@port;host=@host';

	protected 
		  $DRIVER		= DB_MYSQL_DRIVER
		, $DB_USER 		= NULL
		, $DB_PASSWORD 	= NULL
		, $DB_HOST 		= NULL
		, $DB_DATABASE 	= NULL
		, $DB_PORT 		= NULL
		, $DB_COLLATE 	= NULL
		, $PREFIX		= ''
		, $connection	= NULL
		, $statement 	= NULL;
	
    private 
    	  $error 	= NULL
		, $result 	= NULL
		, $conected = FALSE
		, $options 	= array (
			  PDO::ATTR_PERSISTENT		 	=> FALSE
			, PDO::ATTR_EMULATE_PREPARES 	=> FALSE
			, PDO::ATTR_ERRMODE			 	=> PDO::ERRMODE_EXCEPTION
			, PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC
	    );
    
	

    /**
	* -----------------------------------------------------------------------------
	* Conexant Constructor
	* -----------------------------------------------------------------------------
	* 
	* @return
	*/
    public function __construct ($DB_USER = NULL, $DB_PASSWORD = NULL, $DB_DATABASE = NULL, $DB_HOST = 'localhost', $DB_PORT = '3306', $DB_COLLATE = 'utf8' )
	{
		if( $DB_USER != NULL )
		{
			$this->open($DB_USER, $DB_PASSWORD, $DB_DATABASE, $DB_HOST, $DB_PORT, $DB_COLLATE);
		}			
    }
   
    /**
	* Esta es la función que se conecta a la base de datos, 
	* en caso de existir un error en la conexión, 
	* lo almacena en el log de errores de PHP
	* @return
	*/
	public function open ( $DB_USER = NULL, $DB_PASSWORD = NULL, $DB_DATABASE = NULL, $DB_HOST = 'localhost', $DB_PORT = '3306', $DB_COLLATE = 'utf8' )
	{		
		
		if( $DB_USER == NULL)
		{
			$this->DB_USER 		= DB_USER;
			$this->DB_PASSWORD 	= DB_PASSWORD;
			$this->DB_HOST 		= DB_HOST;
			$this->DB_DATABASE 	= DB_DATABASE;
			$this->DB_PORT 		= DB_PORT;
			$this->DB_COLLATE 	= DB_COLLATE;
		} else {
			$this->DB_USER 		= $DB_USER;
			$this->DB_PASSWORD 	= $DB_PASSWORD;
			$this->DB_HOST 		= $DB_HOST;
			$this->DB_DATABASE 	= $DB_DATABASE;
			$this->DB_PORT 		= $DB_PORT;
			$this->DB_COLLATE 	= $DB_COLLATE;
		}
		
    	mb_internal_encoding( 'UTF-8' );
		mb_regex_encoding( 'UTF-8' );
		
		try 
		{			
			//Seleccionar el Driver de Base de Datos por defecto para armar el DSN
			switch( $this->DRIVER )
			{
				//Connection to MariaDB or MySQL
				case 'mariadb':	

				case DB_MYSQL_DRIVER:
					$dsn = str_ireplace(
						array('@dbname', '@host','@port', '@charset'),
						array($this->DB_DATABASE, $this->DB_HOST, $this->DB_PORT, $this->DB_COLLATE),
						self::DNS_MYSQL_DRIVER
					);
					$options = array_merge($this->options, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
					$this->connection = new PDO($dsn, $this->DB_USER, $this->DB_PASSWORD, $options);
				break;

				//Connection to PostgreSQL
				case DB_PGSQL_DRIVER:
					$dsn = str_ireplace(
						array('@dbname','@host','@port'),
						array($this->DB_DATABASE, $this->DB_HOST, $this->DB_PORT),
						self::DNS_PGSQL_DRIVER
					);
					$this->connection = new PDO($dsn, $this->DB_USER, $this->DB_PASSWORD);
				break;

				//Connection to Microsoft SQL Server
				case DB_MSSQL_DRIVER:
					$dsn = str_ireplace(
						array('@dbname','@host'),
						array($this->DB_DATABASE, $this->DB_HOST),
						self::DNS_MSSQL_DRIVER
					);
					$this->connection = new PDO($dsn, $this->DB_USER, $this->DB_PASSWORD);
				break;

				//Connection to SQL Lite
				case DB_SQLITE_DRIVER:
					$this->pdo = new PDO('sqlite:' . $options[ 'connection_file' ], null, null,  $this->options);
				break;

				default :
					throw new Exception( 'DRIVER DATABASE Not support' );
				break;
			}
			
			$this->conected = TRUE;
			return $this->conected;

		} 
		catch (Exception $ex)
		{
			
			$message  = '<strong>Connection Data:</strong><br/>';
			$message .= '<strong>Driver:</strong> '.$this->DRIVER.'<br/>';
			$message .= '<strong>Server:</strong> '.$this->DB_HOST.'<br/>';
			$message .= '<strong>DataBase:</strong> '.$this->DB_DATABASE.'<br/>';
			$message .= '<strong>User:</strong> '.$this->DB_USER.'<br/>';
			$message .= '<strong>Password:</strong> ********** <br/>';

			throw new Exception( $message );

			return FALSE;
		}
	}    
    
       
    /**
    * Desconecta y cierra la conexión activa con la base de datos
    * @return void
    */
    public function close ()
	{
    	try
		{
    		if( $this->conected )
			{
				$this->connection = null;
		        $this->connection = NULL;
		        $this->conected = FALSE;
			}
		} catch ( Excepcion $ex) {
			
		}
    }
    
	
    /**
	* Esta función nos devuelve el número de error (en caso de haberlo) 
	* al haberse ejecutado una consulta o procedimiento.
	* 
	* @return
	*/
    public function getErrorNo ()
	{
        return $this->resource->errno;
    }

    
    /**
	* Esta función nos devuelve el mensaje de error (sin el número).
	* 
	* @return
	*/
    public function getError ()
	{
        return $this->resource->error;
    }
    
	
	/**
	 * Undocumented function
	 *
	 * @param [type] $driver
	 * @return void
	 */
	public function setDriver ( $driver )
	{
		$this->DRIVER = $driver;
	}


    /**
	* 
	* @param undefined $query
	* 
	* @return
	*/
	private function formatBefore ( $query )
	{
		return 
			trim(
				str_ireplace(
					array(
						chr(13).chr(10),
						"\r\n",
						"\n",
						"\r",
						"\t"
					),
					array("", "", "", "", " "),
					$query)
			);
	}
    
    
    /**
	* Devuelve el número de filas afectadas por una sentencia DELETE, INSERT, o UPDATE.
	* 
	* @return
	*/
    public function rowCount ()
	{
		$result = $this->statement->rowCount();
		return $result;
	}
    
   	
	/**
	* Cambia la base de datos
	* @param undefined $database
	* 
	* @return
	*/
    public function setDatabase ( $database )
	{
        $this->connection->changeDB($database);
    }
	
	
    /**
     * Iniciar una transacción
     * @return boolean, true on success or false on failure
     */
    public function begin ()
	{
	  	if ( ! $this->conected )
		{
	        $this->open( $this->DB_USER, $this->DB_PASSWORD, $this->DB_DATABASE, $this->DB_HOST );
	    }
        return $this->connection->beginTransaction();
    }
    

    /**
     * confirmar una transacción
     *  @return boolean, true on success or false on failure
     */
    public function commit ()
	{
        return $this->connection->commit();
    }
    

    /**
     *  Revertir una transacción
     *  @return boolean, true on success or false on failure
     */
    public function rollback ()
	{
        return $this->connection->rollBack();
    }
	
		
	/**
	 *  Devuelve el ultimo ID autonumerico insertado
	 *  @return string
	 */
    public function lastId (){
		return $this->connection->lastInsertId();
	}
	
	
	
	/** 
	 * TODO: Revisar
	 * -----------------------------------------------------------------------
	 * Query
	 * ------------------------------------------------------------------------
	 * Execute an SQL statement, returning a result set as a PDOStatement object
	 * 
	 * @category Controllers
	 * @version 1.0.0
	 * @author name <name@email.com>
	 */
	public function query ( $query )
	{	
		if ( !$this->conected )
		{
	        $this->open( $this->DB_USER, $this->DB_PASSWORD, $this->DB_DATABASE, $this->DB_HOST );
	    }

		try
		{
			$std = $this->connection->query($query);
			return $std;
		} catch (Exception $ex) {
			return FALSE;
		}
	}
	

	/**
	 * ----------------------------------------------------------------------------
	 * Execute SQL Query
	 * ----------------------------------------------------------------------------
	 * It executes a prepared SQL statement, returning a result set as an 
	 * indexed array either by column name, or numerically with base index 0 
	 * as returned in the result set.
	 * 
	 * Ejecuta una sentencia SQL preparada, devolviendo un conjunto de 
	 * resultados como un array indexado tanto por nombre de columna, 
	 * como numéricamente con índice de base 0 tal como fue devuelto en 
	 * el conjunto de resultados.
	 * 
	 * @param string $query
	 * @param array $params
	 * 
	 * @return void
	 */
	public function execute ( $query, $params = [] ) 
	{
		$result = NULL;
		$bindParam = [];

		
	  	if ( !$this->conected ) {
	        $this->open( $this->DB_USER, $this->DB_PASSWORD, $this->DB_DATABASE, $this->DB_HOST );
	    }	    

    	$sql = $this->formatBefore($query);
		    
		if ( ! empty($sql) AND count($params) <= 0)
		{
			try
			{
				if ( $this->statement = $this->connection->prepare($sql) )
				{
					if ( $this->statement->execute() )
					{
						// get affetcted rows and set it to class property
						$this->affectedRows = $this->statement->rowCount();

						// set pdo result array with class property
						$this->results      = $this->statement->fetchAll( PDO::FETCH_ASSOC );

						// close pdo cursor
						$this->statement->closeCursor();
						
						// return pdo result
						return $this;					
					} 
					else {
						//echo $this->statement->errorInfo();
						return FALSE;
					}				
				}
			} catch(Exception $ex) {
				//CreativeDatabaseException::showErrorInQuery( $ex->getMessage(), $query );
			}
		}
		else if ( ! empty($sql) AND count($params) > 0) {			
			try
			{
				if ( $this->statement = $this->connection->prepare($sql) )				
				{
					$opreation = strtoupper(explode(' ', $sql)[0]);

					if ( $this->statement->execute($params) )
					{
						switch ( $opreation )
						{
							case 'SELECT':
								// get pdo result array
								$this->results = $this->statement->fetchAll( PDO::FETCH_ASSOC );
							break;
							case 'INSERT':
								$this->lastId = $this->lastId();
								$this->results = $this->lastId;
							break;
							case 'UPDATE':
								// get affected rows
								$this->affectedRows = $this->statement->rowCount();
								$this->results = $this->affectedRows;
							break;
							case 'DELETE':
								// get affected rows
								$this->affectedRows = $this->statement->rowCount();
								$this->results = $this->affectedRows;
							break;
						}
						
						return $this;					
					} 
					else {						
						return $this;
					}				
				}
			} catch(Exception $ex) {
				$e = $ex;
			}
		}

    }
	
	
	/**
	 * Undocumented function
	 *
	 * @param string $type
	 * @return void
	 */
	public function results($type = 'array', $row = 0 )
	{
		$results = $this->results;
        switch ($type) {
            case 'array':
                return $results;
                break;
            case 'xml':
                return $this->array2xml($results);
                break;
            case 'json':
                return json_encode($results);
                break;
        }
	}
	

	/**
	 * Undocumented function
	 *
	 * @param integer $row
	 * @return void
	 */
	public function result( $row = 0 )
	{      
        return $this->results[ $row ];    
	}


	/**
	 * Undocumented function
	 *
	 * @param [type] $arrayData
	 * @return void
	 */
	private function array2xml($arrayData)
	{
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xml .= "<root>";
        foreach ($arrayData as $key => $value) {
            $xml .= "<data>";
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    //$k holds the table column name
                    $xml .= "<$k>";
                    //embed the SQL data in a CDATA element to avoid XML entity issues
                    $xml .= "<![CDATA[$v]]>";
                    //and close the element
                    $xml .= "</$k>";
                }
            } else {
                //$key holds the table column name
                $xml .= "<$key>";
                //embed the SQL data in a CDATA element to avoid XML entity issues
                $xml .= "<![CDATA[$value]]>";
                //and close the element
                $xml .= "</$key>";
            }
            $xml .= "</data>";
        }
        $xml .= "</root>";
        return $xml;
	}
	

    /**
	* Ejecuta una consulta de tipo escalar
	* 
	* @param string $query
	* @param array $params
	* 
	* @return
	*/
	public function row ( $query, $params = array() )
	{
        $result = $this->execute($query, $params);
        if ( ! is_null($result) )
		{
			$result = $this->result();
            return $result;
        }
        return NULL;
    }
    	
	
	/**
	* Extra function to filter when only mysqli_real_escape_string is needed
	* @access public
	* @param mixed $data
	* @return mixed $data
	*/
	public function escape ( $data )
	{
	   if( !is_array($data) )
	   {
	       $data = $this->connection->real_escape_string( $data );
	   } else {
	       $data = array_map( array( $this, 'escape' ), $data );
	   }
	   return $data;
	}


    /**
    * Destruir el objeto
    * Cierra todas las conexiones a la base de datos
    */
    public function __deconstruct ()
	{        
        $this->connections->close();
    }

}
