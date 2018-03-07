<?php

class Model
{

    protected 
        $connection	
        , $table = '' 
        , $primaryKey = 'id' 
        , $vars = [];
        

    public function __construct ( $class )
    {
        $this->table = strtolower($class);
        $this->connection = new Database();
    }



    /**
     * Undocumented function
     *
     * @param [type] $name
     * @param [type] $value
     */
    public function __set ( $name, $value )
	{
		if( strtolower($name) === strtolower($this->primaryKey) ) {
			$this->vars[$this->primaryKey] = $value;
		}
		else {
			if( is_string($name) ) $this->vars[$name] = $value;
		}
		return $this;
	}
	
	
	/**
	 * Undocumented function
	 *
	 * @param string $name
	 * @return void
	 */
	public function __get ($name)
	{	
		if(is_array($this->vars))
		{
			if(array_key_exists($name,$this->vars))	{
				return $this->vars[$name];
			}
		}
		return NULL;
    }

    	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function begin ()
	{
		$this->connection->begin();
	}
	
	
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function commit ()
	{
		$this->connection->commit();
	}
	

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function rollback ()
	{
		$this->connection->rollback();
    }
    


    public function all ( $columns = array(), $sort = array() )
	{

		$sqlSort = '';
		$fields = '';
		$fields = [];
		$this->vars = [];
		
    	if ( ! empty($sort) )
		{
			$sortValues = array();
			foreach ($sort as $key => $value)
			{
				$sortValues[] = $key . " " . $value;
			}
			$sqlSort .= " ORDER BY " . implode(", ", $sortValues);
		}		
		
		if( count($columns) > 0 )
		{
			foreach($columns as $key => $value)
			{
				if( is_string($key) ){
					$fields[] = "{$value} AS {$key}";
				} else {
					$fields[] = "{$value}";
				}				
			}
			$fields = implode( ",", $fields);
		}
		else {
			$fields = '*';
		}

		return $this->connection->execute("SELECT {$fields} FROM {$this->table} {$sqlSort}" )->results();;
    }
    

	public function where( $where, $whereType = 'AND' )
	{
		$sWhere = [];

		if( count($where) > 0)
		{
			foreach ($where as $key => $value)
			{
				$sWhere[] = "$key = :$key";
				$this->vars[$key] = $value;
			}
			$sWhere = implode( " {$whereType} ", $sWhere);
			
			if( empty($this->sql) ){
				$sql = "SELECT * FROM {$this->table} WHERE {$sWhere}";
			} else {
				$sql .= " WHERE {$sWhere}";
			}	
		}

		return $this->connection->execute( $sql, $this->vars );
	}




		/**
	 * ----------------------------------------------------------------
	 * INSERT RECORD
	 * ----------------------------------------------------------------
	 * Insert a record in the table associated with the model.
	 * 
	 * Inserta un registro en la tabla asociada al modelo.
	 *
	 * @return void
	 */
	public function insert ()
	{
		$columns = array_keys($this->vars);

		if( ! empty($columns) )
		{
			$fieldsValues = array(
				implode(",",$columns),
				":".implode(",:",$columns)
			);
			$sql = "INSERT INTO {$this->table} ({$fieldsValues[0]}) VALUES ({$fieldsValues[1]})";
			return $this->connection->execute( $sql, $this->vars);
		}
		else {
			throw new Exception( "You have an error in your SQL syntax. There are no fields or values ​​to perform the data insertion." );
		}		
	}
	
	
	/**
	 * ----------------------------------------------------------------
	 * UPDATE RECORD
	 * ----------------------------------------------------------------
	 * Update a record in the database.
	 * ---
	 * Actualiza un registro en la base de datos.
	 * ---
	 * @param integer $fields
	 * @return void
	 */
	public function update ( $value, $field = NULL )
	{	
		$columns = array_keys($this->vars);
		$fields = [];

		foreach($columns as $column)
		{
			if($column !== $this->primaryKey) {
				$fields[] = $column . "=:". $column;
			}
		}
		$fields = implode( ', ', $fields);
				
		if( $field == NULL )
		{			
			$fieldCondition = $this->primaryKey;
			$this->vars[$this->primaryKey] = $value;
		}
		else {
			$fieldCondition = $field;
			$this->vars[$fieldCondition] = $value;
		}

		if( count($columns) > 0 )
		{
			$binding = $this->vars;
			$sql = "UPDATE {$this->table} SET {$fields} WHERE {$fieldCondition}=:{$fieldCondition}";
			$result = $this->connection->execute( $sql, $binding );
			return $result;
		}
		
		return null;
	}



		/**
	* Elimina un registro específico por su clave primaria
	* @param mixed $id 
	* 
	* @return
	*/
	public function destroy ( $id, $field = NULL )
	{
		if( ! empty($id) )
		{
			$this->vars = [];
			$field = empty($field) ? $this->primaryKey : $field;			
			$sql = "DELETE FROM {$this->table} WHERE {$field} = :{$field}" ;
		}
		return $this->connection->execute( $sql, [$field => $id] );
	}
    
}