<?php
declare(strict_types=1);

class Model {

	protected $table_name;
	protected $id_name = 'id';
	protected $conn;
	protected $properties = [];
	protected $missing_properties = [];
	protected $schema;
	protected $fillable = '*';


	public function __construct(object $conn = null){
		$this->conn = $conn;
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if (empty($this->schema))
			throw Exception ("Schema is empty!");

		$this->properties = array_keys($this->schema);
	}

	/** 
	 * Get by id
	 */
	function fetchOne(array $fields = null)
	{
		if ($fields == null){
			$q  = 'SELECT *';
			$select_fields_array = $this->properties;
		} else {
			$select_fields_array = array_intersect($fields, $this->properties);
			$q  = "SELECT ".implode(", ", $select_fields_array);
		}

		$q  .= " FROM {$this->table_name} WHERE {$this->id_name} = :id";

		$st = $this->conn->prepare($q);
		$st->bindParam(":id", $this->{$this->id_name}, constant('PDO::PARAM_'.$this->schema[$this->id_name]));
		$st->execute();
		
		$row = $st->fetch(PDO::FETCH_OBJ);
		if (!$row)
			return false;
	 
		foreach ($select_fields_array as $prop){
			$this->{$prop} = $row->{$prop};
		}	
	}
	
	function exists()
	{
		$q  = "SELECT *FROM {$this->table_name} WHERE {$this->id_name}=:id";
		$st = $this->conn->prepare( $q );
		$st->bindParam(":id", $this->{$this->id_name}, PDO::PARAM_INT);
		$st->execute();
		
		$row = $st->fetch(PDO::FETCH_ASSOC);

		if (!$row)
			return false;
		else
			return true;
	}

	function fetchAll(array $fields = null, Paginator $paginator = null)
	{
		if ($fields == null)
			$q  = 'SELECT *';
		else {
			$select_fields_array = array_intersect($fields, $this->properties);
			$q  = "SELECT ".implode(", ", $select_fields_array);
		}

		$q  .= ' FROM '.$this->table_name;

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
			return $st->fetchAll(PDO::FETCH_ASSOC);
		else
			return false;	
	}

	function filter(array $fields = null, array $conditions, Paginator $paginator = null)
	{
		if ($fields == null)
			$q  = 'SELECT *';
		else {
			$select_fields_array = array_intersect($fields, $this->properties);
			$q  = "SELECT ".implode(", ", $select_fields_array);
		}

		$q  .= ' FROM '.$this->table_name;

		$vars   = array_keys($conditions);
		$values = array_values($conditions);

		$where = '';
		foreach($vars as $ix => $var){
			$where .= "$var = :$var AND ";
		}
		$where =trim(substr($where, 0, strlen($where)-5));
		
		$q  .= " {$this->table_name} WHERE $where";
		
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
			if (!isset($this->schema[$vars[$ix]]))
				throw new InvalidArgumentException("there is an error near '{$vars[$ix]}'");

			$st->bindValue(":{$vars[$ix]}", $val, constant("PDO::PARAM_{$this->schema[$vars[$ix]]}"));
		}

		if ($st->execute())
			return $st->fetchAll(PDO::FETCH_ASSOC);
		else
			return false;	
	}

	function delete()
	{
		$q = "DELETE FROM {$this->table_name} WHERE {$this->id_name} = ?";
		$st = $this->conn->prepare($q);
		$st->bindParam(1, $this->{$this->id_name});
	 
		if($st->execute())
			return $st->rowCount();
		else
			return false;	
	}

	function create(array $data)
	{
		$vars   = array_keys($data);
		$vals = array_values($data);

		if($this->fillable!='*' && is_array($this->fillable)){
			foreach($vars as $var){
				if (!in_array($var,$this->fillable))
					throw new InvalidArgumentException("$var is no fillable");
			}
		}

		$str_vars = implode(', ',$vars);

		$symbols = array_map(function($v){ return ":$v";}, $vars);
		$str_vals = implode(', ',$symbols);

		$q = "INSERT INTO {$this->table_name} ($str_vars) VALUES ($str_vals)";
		$st = $this->conn->prepare($q);
	 
		foreach($vals as $ix => $val){
			if (!isset($this->schema[$vars[$ix]]))
				throw new InvalidArgumentException("there is an error near '{$vars[$ix]}'");

			$st->bindValue(":{$vars[$ix]}", $val, constant("PDO::PARAM_{$this->schema[$vars[$ix]]}"));
		}
		
		$result = $st->execute();
		if ($result){
			return $this->{$this->id_name} = $this->conn->lastInsertId();
		}else
			return false;
	}

	// It really admits partial updates
	function update($data)
	{
		$vars   = array_keys($data);
		$values = array_values($data);

		if($this->fillable!='*' && is_array($this->fillable)){
			foreach($vars as $var){
				if (!in_array($var,$this->fillable))
					throw new InvalidArgumentException("$var is no fillable");
			}
		}
		
		$set = '';
		foreach($vars as $ix => $var){
			$set .= " $var = :$var, ";
		}
		$set =trim(substr($set, 0, strlen($set)-2));

		$q = "UPDATE {$this->table_name} 
				SET $set
				WHERE {$this->id_name} = :id";
	 
		$st = $this->conn->prepare($q);
	
		foreach($values as $ix => $val){
			if (!isset($this->schema[$vars[$ix]]))
				throw new InvalidArgumentException("there is an error near '{$vars[$ix]}'");

			$st->bindValue(":{$vars[$ix]}", $val, constant("PDO::PARAM_{$this->schema[$vars[$ix]]}"));
		}
		$st->bindParam(':id', $this->{$this->id_name});
	 
		if($st->execute())
			return $st->rowCount();
		else 
			return false;	
	}

	/*
		'''Reflection'''
	*/

	function getMissingProperties() {
		return $this->missing_properties;
	}
	
	function has_properties($props, array $excluded = []){
		if (is_object($props))
			$props = (array) $props;
		
		$props = array_keys($props);
		
		if (empty($props))
			throw new InvalidArgumentException("Properties not found!");
		
		$success = true;
		foreach ($this->properties as $exp){
			if (!in_array($exp, $props) && !in_array($exp, $excluded)){
				$this->missing_properties[] = $exp; 
				$success = false;
			}	
		}

		return $success;
	}
	

	/**
	 * Get the value of properties
	 */ 
	public function getProperties()
	{
		return $this->properties;
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