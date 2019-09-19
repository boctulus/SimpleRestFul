<?php
declare(strict_types=1);

namespace Core;

require_once CORE_PATH. 'paginator.php';

class Model {

	static protected $table_name;
	static protected $id_name = 'id';
	static protected $schema;
	static protected $fillable = '*';
	static protected $hidden;
	static protected $properties = [];
	protected $conn;

	/*
		Chequear en cada método si hay una conexión 
	*/

	public function __construct(object $conn = null){
		if($conn){
			$this->conn = $conn;
			$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		
		if (empty(static::$schema))
			throw new \Exception ("Schema is empty!");

		static::$properties = array_keys(static::$schema);
	}

	// filter results 
	private function _removehidden(&$fields)
	{	
		//debug($fields, 'campos a mostrar');
		//debug(static::$properties, 'campos');

		if (!empty(static::$hidden)){
			if (empty($fields)) {
				$fields = static::$properties;
			}

			foreach (static::$hidden as $h){
				$k = array_search($h, $fields);
				if ($k != null)
					unset($fields[$k]);
			}
		}
	}

	// remove from hidden list of fields
	function unhide($unhidden_fields){
		if (!empty(static::$hidden) && !empty($unhidden_fields)){			
			foreach ($unhidden_fields as $uf){
				$k = array_search($uf, static::$hidden);
				unset(static::$hidden[$k]);
			}
		}
	}

	// hide a field from fetch methods 
	function hide(array $fields){
		foreach ($fields as $f)
			static::$hidden[] = $f;
	}

	// makes a field fillable
	function fill(array $fields){
		foreach ($fields as $f)
			static::$fillable[] = $f;
	}

	// remove from fillable list of fields
	function unfill($fields){
		if (!empty(static::$fillable) && !empty($fields)){			
			foreach ($fields as $uf){
				$k = array_search($uf, static::$fillable);
				unset(static::$fillable[$k]);
			}
		}
	}


	/** 
	 * Get by id
	 * 
	 * No filter
	 */
	function fetch(array $fields = null)
	{
		$this->_removehidden($fields);

		if ($fields == null){
			$q  = 'SELECT *';
			$select_fields_array = static::$properties;
		} else {
			$select_fields_array = array_intersect($fields, static::$properties);
			$q  = "SELECT ".implode(", ", $select_fields_array);
		}

		$q  .= " FROM ".static::$table_name." WHERE ".static::$id_name." = :id";

		$st = $this->conn->prepare($q);
		$st->bindParam(":id", $this->{static::$id_name}, constant('PDO::PARAM_'.static::$schema[static::$id_name]));
		$st->execute();
		
		$row = $st->fetch(\PDO::FETCH_OBJ);
		if (!$row)
			return false;
	 
		foreach ($select_fields_array as $prop){
			$this->{$prop} = $row->{$prop};
		}	
	}
	
	function fetchAll(array $fields = null, array $order = NULL, int $limit = NULL, int $offset = 0)
	{
		$this->_removehidden($fields);

		if($limit>0 || $order!=NULL){
			try {
				$paginator = new Paginator();
				$paginator->limit  = $limit;
				$paginator->offset = $offset;
				$paginator->orders = $order;
				$paginator->properties = static::$properties;
				$paginator->compile();
			}catch (\Exception $e){
				sendError("Pagination error: {$e->getMessage()}");
			}
		}else
			$paginator = null;	

		if ($fields == null)
			$q  = 'SELECT *';
		else {
			$select_fields_array = array_intersect($fields, static::$properties);
			$q  = "SELECT ".implode(", ", $select_fields_array);
		}

		$q  .= ' FROM '.static::$table_name;

		/// start pagination
		if($paginator!=null)
			$q .= $paginator->getQuery();

		$st = $this->conn->prepare($q);

		if($paginator!=null){	
			$bindings = $paginator->getBinding();
			foreach($bindings as $binding){
				$st->bindParam(...$binding);
			}
		} 
		/// end pagination

		if ($st->execute())
			return $st->fetchAll(\PDO::FETCH_ASSOC);
		else
			return false;	
	}

	function filter(array $fields = null, array $conditions, array $order = null, int $limit = NULL, int $offset = 0)
	{
		$this->_removehidden($fields);

		if($limit>0 || $order!=NULL){
			try {
				$paginator = new Paginator();
				$paginator->limit  = $limit;
				$paginator->offset = $offset;
				$paginator->orders = $order;
				$paginator->properties = static::$properties;
				$paginator->compile();
			}catch (\Exception $e){
				sendError("Pagination error: {$e->getMessage()}");
			}
		}else
			$paginator = null;	

		if ($fields == null)
			$q  = 'SELECT *';
		else {
			$select_fields_array = array_intersect($fields, static::$properties);
			$q  = "SELECT ".implode(", ", $select_fields_array);
		}

		$q  .= ' FROM '.static::$table_name;

		$vars   = array_keys($conditions);
		$values = array_values($conditions);

		$where = '';
		foreach($vars as $ix => $var){
			$where .= "$var = :$var AND ";
		}
		$where =trim(substr($where, 0, strlen($where)-5));
		
		$q  .= " ".static::$table_name." WHERE $where";
		
		/// start pagination
		if($paginator!=null)
			$q .= $paginator->getQuery();
			
		$st = $this->conn->prepare($q);

		if($paginator!=null){	
			$bindings = $paginator->getBinding();
			foreach($bindings as $binding){
				$st->bindValue(...$binding);
			}
		} 
		/// end pagination
		
		foreach($values as $ix => $val){
			if (!isset(static::$schema[$vars[$ix]]))
				throw new \InvalidArgumentException("there is an error near '{$vars[$ix]}'");

			$const = static::$schema[$vars[$ix]];
			$st->bindValue(":{$vars[$ix]}", $val, constant("PDO::PARAM_{$const}"));
		}

		if ($st->execute())
			return $st->fetchAll(\PDO::FETCH_ASSOC);
		else
			return false;	
	}

	function exists()
	{
		$q  = "SELECT * FROM ".static::$table_name." WHERE ".static::$id_name."=:id";
		$st = $this->conn->prepare( $q );
		$st->bindParam(":id", $this->{static::$id_name}, \PDO::PARAM_INT);
		$st->execute();
		
		$row = $st->fetch(\PDO::FETCH_ASSOC);

		if (!$row)
			return false;
		else
			return true;
	}

	function create(array $data)
	{
		$vars   = array_keys($data);
		$vals = array_values($data);

		if(static::$fillable!='*' && is_array(static::$fillable)){
			foreach($vars as $var){
				if (!in_array($var,static::$fillable))
					throw new \InvalidArgumentException("$var is no fillable");
			}
		}

		$str_vars = implode(', ',$vars);

		$symbols = array_map(function($v){ return ":$v";}, $vars);
		$str_vals = implode(', ',$symbols);

		$q = "INSERT INTO ".static::$table_name." ($str_vars) VALUES ($str_vals)";
		$st = $this->conn->prepare($q);
	 
		foreach($vals as $ix => $val){
			//if (!isset(static::$schema[$vars[$ix]]))  # posible dupe
			//	throw new InvalidArgumentException("there is an error near '{$vars[$ix]}'");

			$const = static::$schema[$vars[$ix]];
			$st->bindValue(":{$vars[$ix]}", $val, constant("PDO::PARAM_{$const}"));
		}
		
		$result = $st->execute();
		if ($result){
			return $this->{static::$id_name} = $this->conn->lastInsertId();
		}else
			return false;
	}

	// It admits partial updates
	function update($data)
	{
		$vars   = array_keys($data);
		$values = array_values($data);

		if(static::$fillable!='*' && is_array(static::$fillable)){
			foreach($vars as $var){
				if (!in_array($var,static::$fillable))
					throw new \InvalidArgumentException("$var is no fillable");
			}
		}
		
		$set = '';
		foreach($vars as $ix => $var){
			$set .= " $var = :$var, ";
		}
		$set =trim(substr($set, 0, strlen($set)-2));

		$q = "UPDATE ".static::$table_name." 
				SET $set
				WHERE ".static::$id_name."= :id";
	 
		$st = $this->conn->prepare($q);
	
		foreach($values as $ix => $val){
			if (!isset(static::$schema[$vars[$ix]]))
				throw new \InvalidArgumentException("there is an error near '{$vars[$ix]}'");

			$const = static::$schema[$vars[$ix]];	
			$st->bindValue(":{$vars[$ix]}", $val, constant("PDO::PARAM_{$const}"));
		}
		$st->bindParam(':id', $this->{static::$id_name});
	 
		if($st->execute())
			return $st->rowCount();
		else 
			return false;	
	}

	function delete()
	{
		$q = "DELETE FROM ".static::$table_name." WHERE ".static::$id_name." = ?";
		$st = $this->conn->prepare($q);
		$st->bindParam(1, $this->{static::$id_name});
	 
		if($st->execute())
			return $st->rowCount();
		else
			return false;	
	}

	/*
		'''Reflection'''
	*/
	
	static function inSchema($props, array $excluded = []){
		if (is_object($props))
			$props = (array) $props;
		
		$props = array_keys($props);
		
		if (empty($props))
			throw new \InvalidArgumentException("Properties not found!");
		
		$missing_properties = [];

		foreach (static::$properties as $exp){
			if (!in_array($exp, $props) && !in_array($exp, $excluded)){
				return false; 
			}	
		}

		return true;
	}

	static function diffWithSchema($props, array $excluded = []){
		if (is_object($props))
			$props = (array) $props;
		
		$props = array_keys($props);
		
		if (empty($props))
			throw new \InvalidArgumentException("Properties not found!");
		
		$missing_properties = [];

		foreach (static::$properties as $ix => $exp){
			if (!in_array($exp, $props) && !in_array($exp, $excluded)){
				$missing_properties[] = $exp; 
			}	
		}

		return $missing_properties;
	}
	
	/**
	 * Get schema 
	 */ 
	static public function getSchema()
	{
		return static::$properties;
	}

	/**
	 * Set the value of conn
	 *
	 * @return  self
	 */ 
	public function setConn($conn)
	{
		$this->conn = $conn;
		return $this;
	}
}