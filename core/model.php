<?php
declare(strict_types=1);

require_once 'paginator.php';

class Model {

	protected $table_name;
	protected $id_name = 'id';
	protected $conn;
	protected $properties = [];
	protected $missing_properties = [];
	protected $schema;


	public function __construct($db){
		$this->conn = $db;
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if (empty($this->schema))
			throw Exception ("Schema is empty!");

		$this->properties = array_keys($this->schema);
	}

	public function schema(){
		return $this->schema;
	}	

	function fetchOne()
	{
		$q  = "SELECT *FROM {$this->table_name} WHERE {$this->id_name} = :id";

		$st = $this->conn->prepare($q);
		$st->bindParam(":id", $this->{$this->id_name}, constant('PDO::PARAM_'.$this->schema[$this->id_name]));
		$st->execute();
		
		$row = $st->fetch(PDO::FETCH_OBJ);
		if (!$row)
			return false;
	 
		foreach ($this->properties as $prop){
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

	function fetchAll(Paginator $paginator = null)
	{
		$q  = "SELECT * FROM {$this->table_name}";

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

	function filter($conditions, Paginator $paginator = null)
	{
		$vars   = array_keys($conditions);
		$values = array_values($conditions);

		$where = '';
		foreach($vars as $ix => $var){
			$where .= "$var = :$var AND ";
		}
		$where =trim(substr($where, 0, strrpos( $where, 'AND ')));
		
		$q  = "SELECT * FROM {$this->table_name} WHERE $where";
		
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

		$str_vars = implode(', ',$vars);

		$symbols = array_map(function($v){ return ":$v";}, $vars);
		$str_vals = implode(', ',$symbols);

		$q = "INSERT INTO {$this->table_name} ($str_vars) VALUES ($str_vals)";
		$st = $this->conn->prepare($q);
	 
		foreach($vals as $ix => $val){
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
		
		$set = '';
		foreach($vars as $ix => $var){
			$set .= " $var = :$var, ";
		}
		$set =trim(substr($set, 0, strrpos( $set, ',')));

		$q = "UPDATE {$this->table_name} 
				SET $set
				WHERE {$this->id_name} = :id";
	 
		$st = $this->conn->prepare($q);
	
		foreach($values as $ix => $val){
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
}