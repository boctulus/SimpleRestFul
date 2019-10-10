<?php
namespace simplerest\core;

use simplerest\libs\Debug;
use simplerest\libs\Arrays;

class Model {

	protected $table_name;
	protected $id_name = 'id';
	protected $schema;
	protected $nullable = [];
	protected $fillable = [];
	protected $hidden;
	protected $properties = [];
	protected $joins = [];
	protected $conn;

	/*
		Chequear en cada método si hay una conexión 
	*/

	public function __construct(object $conn = null){
		if($conn){
			$this->conn = $conn;
			$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		
		if (empty($this->schema))
			throw new \Exception ("Schema is empty!");

		$this->properties = array_keys($this->schema);
	}


	/**
	 * removehidden
	 *
	 * @param  array $fields
	 *
	 * @return void
	 */
	private function removehidden(&$fields)
	{	
		if (!empty($this->hidden)){
			if (empty($fields)) {
				$fields = $this->properties;
			}

			foreach ($this->hidden as $h){
				$k = array_search($h, $fields);
				if ($k != null)
					unset($fields[$k]);
			}
		}
	}

	
	/**
	 * unhide
	 * remove from hidden list of fields
	 * 
	 * @param  mixed $unhidden_fields
	 *
	 * @return void
	 */
	function unhide(array $unhidden_fields){
		if (!empty($this->hidden) && !empty($unhidden_fields)){			
			foreach ($unhidden_fields as $uf){
				$k = array_search($uf, $this->hidden);
				unset($this->hidden[$k]);
			}
		}
	}

	
	/**
	 * hide
	 * turn off field visibility from fetch methods 
	 * 
	 * @param  mixed $fields
	 *
	 * @return void
	 */
	function hide(array $fields){
		foreach ($fields as $f)
			$this->hidden[] = $f;
	}

	
	/**
	 * fill
	 * makes a field fillable
	 *
	 * @param  mixed $fields
	 *
	 * @return void
	 */
	function fill(array $fields){
		foreach ($fields as $f)
			$this->fillable[] = $f;
	}

	
	/**
	 * unfill
	 * remove from fillable list of fields
	 * 
	 * @param  mixed $fields
	 *
	 * @return void
	 */
	function unfill(array $fields){
		if (!empty($this->fillable) && !empty($fields)){			
			foreach ($fields as $uf){
				$k = array_search($uf, $this->fillable);
				unset($this->fillable[$k]);
			}
		}
	}

	// INNER JOIN
	function join($table, $on1, $op, $on2) {
		$this->joins[] = [$table, $on1, $op, $on2, 'INNER JOIN'];
		return $this;
	}

	function leftJoin($table, $on1, $op, $on2) {
		$this->joins[] = [$table, $on1, $op, $on2, 'LEFT JOIN'];
		return $this;
	}

	function rightJoin($table, $on1, $op, $on2) {
		$this->joins[] = [$table, $on1, $op, $on2, 'RIGHT JOIN'];
		return $this;
	}
	
	/**
	 * fetch
	 *
	 * @param  array $fields
	 *
	 * @return bool
	 */
	function fetch(array $fields = null)
	{
		$this->removehidden($fields);

		if ($fields == null){
			$q  = 'SELECT *';
			$select_fields_array = $this->properties;
		} else {
			$select_fields_array = array_intersect($fields, $this->properties);
			$q  = "SELECT ".implode(", ", $select_fields_array);
		}

		$q  .= " FROM ".$this->table_name." WHERE ".$this->id_name." = :id";

		$st = $this->conn->prepare($q);
		$st->bindParam(":id", $this->{$this->id_name}, constant('PDO::PARAM_'.$this->schema[$this->id_name]));
		$st->execute();
		
		$row = $st->fetch(\PDO::FETCH_OBJ);
		if (!$row)
			return false;
	 
		foreach ($select_fields_array as $prop){
			$this->{$prop} = $row->{$prop};
		}	

		return true;
	}
	
	/**
	 * fetchAll
	 *
	 * @param  array $fields
	 * @param  array $order
	 * @param  int $limit
	 * @param  int $offset
	 *
	 * @return mixed
	 */
	function fetchAll(array $fields = null, array $order = NULL, int $limit = NULL, int $offset = 0)
	{
		$this->removehidden($fields);

		if($limit>0 || $order!=NULL){
			try {
				$paginator = new Paginator();
				$paginator->limit  = $limit;
				$paginator->offset = $offset;
				$paginator->orders = $order;
				$paginator->properties = $this->properties;
				$paginator->compile();
			}catch (\Exception $e){
				throw new \Exception("Pagination error: {$e->getMessage()}");
			}
		}else
			$paginator = null;	

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

		// JOINS
		$joins = '';
		foreach ($this->joins as $j){
			$joins .= "$j[4] $j[0] ON $j[1]$j[2]$j[3] ";
		}

		$q  .= " $joins";

		//var_dump($q);

		$st = $this->conn->prepare($q);

		if($paginator!=null){	
			$bindings = $paginator->getBinding();
			foreach($bindings as $ix => $binding){
				$st->bindValue($ix +1, $binding[1], $binding[2]);
			}
		} 

		if ($st->execute())
			return $st->fetchAll(\PDO::FETCH_ASSOC);
		else
			return false;	
	}

	/**
	 * filter
	 *
	 * @param  array $fields
	 * @param  array $conditions
	 * @param  string $conjunction
	 * @param  array $order
	 * @param  int $limit
	 * @param  int $offset
	 *
	 * @return array | false
	 */
	function filter(array $fields = null, array $conditions, $conjunction = null, array $order = null, int $limit = NULL, int $offset = 0)
	{
		if (empty($conjunction))
			$conjunction = 'AND';

		$this->removehidden($fields);

		if($limit>0 || $order!=NULL){
			try {
				$paginator = new Paginator();
				$paginator->limit  = $limit;
				$paginator->offset = $offset;
				$paginator->orders = $order;
				$paginator->properties = $this->properties;
				$paginator->compile();
			}catch (\Exception $e){
				throw new \Exception("Pagination error: {$e->getMessage()}");
			}
		}else
			$paginator = null;	

		if ($fields == null)
			$q  = 'SELECT *';
		else {
			$select_fields_array = array_intersect($fields, $this->properties);
			$q  = "SELECT ".implode(", ", $select_fields_array);
		}

		$q  .= ' FROM '.$this->table_name;

		$_where = [];

		$vars   = [];
		$values = [];
		$ops    = [];
		if (count($conditions)>0){
			if(is_array($conditions[Arrays::array_key_first($conditions)])){
				foreach ($conditions as $cond) {
					if(is_array($cond[1])){

						if($this->schema[$cond[0]] == 'STR')	
							$cond[1] = array_map(function($e){ return "'$e'";}, $cond[1]);   
						
						$in_val = implode(', ', $cond[1]);
						$_where[] = "$cond[0] IN ($in_val) ";
					}else{
						$vars[]   = $cond[0];
						$values[] = $cond[1];

						if ($cond[1] === NULL && (empty($cond[2]) || $cond[2]=='='))
							$ops[] = 'IS';
						else	
							$ops[] = $cond[2] ?? '=';
					}	
				}
			}else{
				$vars[]   = $conditions[0];
				$values[] = $conditions[1];
		
				if ($conditions[1] === NULL && (empty($conditions[2]) || $conditions[2]=='='))
					$ops[] = 'IS';
				else	
					$ops[] = $conditions[2] ?? '='; 
			}	
		}

		$shift = 0;
		foreach($vars as $ix => $var){
			$_where[] = "$var $ops[$ix] ?";
			$shift++;
		}
		$where = implode(" $conjunction ", $_where);
		
		// JOINS
		$joins = '';
		foreach ($this->joins as $j){
			$joins .= "$j[4] $j[0] ON $j[1]$j[2]$j[3] ";
		}

		$q  .= " $joins WHERE $where";


		//DEBUG::debug($vars);
		//DEBUG::debug($values);
		
		if($paginator!=null){
			$q .= $paginator->getQuery();
		}
		
		$st = $this->conn->prepare($q);
				
		foreach($values as $ix => $val){
			if(is_null($val))
				$type = \PDO::PARAM_NULL;
			elseif(isset($this->schema[$vars[$ix]])){
				$const = $this->schema[$vars[$ix]];
				$type = constant("PDO::PARAM_{$const}");
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			elseif(is_string($val))
				$type = \PDO::PARAM_STR;			
				
			$st->bindValue($ix+1, $val, $type);
		}

		if($paginator!=null){	
			$bindings = $paginator->getBinding();
			foreach($bindings as $ix => $binding){
				$st->bindValue($shift +$ix +1, $binding[1], $binding[2]);
			}
		} 

		if ($st->execute())
			return $st->fetchAll(\PDO::FETCH_ASSOC);
		else
			return false;	
	}

	function exists()
	{
		$q  = "SELECT * FROM ".$this->table_name." WHERE ".$this->id_name."=:id";
		$st = $this->conn->prepare( $q );
		$st->bindParam(":id", $this->{$this->id_name}, \PDO::PARAM_INT);
		$st->execute();
		
		$row = $st->fetch(\PDO::FETCH_ASSOC);

		if (!$row)
			return false;
		else
			return true;
	}

	/*
		@return mixed false | integer 
	*/
	function create(array $data)
	{
		$vars   = array_keys($data);
		$vals = array_values($data);

		if(!empty($this->fillable) && is_array($this->fillable)){
			foreach($vars as $var){
				if (!in_array($var,$this->fillable))
					throw new \InvalidArgumentException("$var is no fillable");
			}
		}

		$str_vars = implode(', ',$vars);

		$symbols = array_map(function($v){ return ":$v";}, $vars);
		$str_vals = implode(', ',$symbols);

		$q = "INSERT INTO ".$this->table_name." ($str_vars) VALUES ($str_vals)";
		$st = $this->conn->prepare($q);
	 
		foreach($vals as $ix => $val){
			//if (!isset($this->schema[$vars[$ix]]))  # posible dupe
			//	throw new InvalidArgumentException("there is an error near '{$vars[$ix]}'");

			$const = $this->schema[$vars[$ix]];
			$st->bindValue(":{$vars[$ix]}", $val, constant("PDO::PARAM_{$const}"));
		}
		
		$result = $st->execute();
		if ($result){
			return $this->{$this->id_name} = $this->conn->lastInsertId();
		}else
			return false;
	}

	
	/**
	 * update
	 * It admits partial updates
	 *
	 * @param  array $data
	 *
	 * @return mixed
	 */
	function update(array $data)
	{
		$vars   = array_keys($data);
		$values = array_values($data);

		if(!empty($this->fillable) && is_array($this->fillable)){
			foreach($vars as $var){
				if (!in_array($var,$this->fillable))
					throw new \InvalidArgumentException("$var is no fillable");
			}
		}
		
		$set = '';
		foreach($vars as $ix => $var){
			$set .= " $var = :$var, ";
		}
		$set =trim(substr($set, 0, strlen($set)-2));

		$q = "UPDATE ".$this->table_name." 
				SET $set
				WHERE ".$this->id_name."= :id";
	 
		$st = $this->conn->prepare($q);
	
		foreach($values as $ix => $val){
			if (!isset($this->schema[$vars[$ix]]))
				throw new \InvalidArgumentException("there is an error near '{$vars[$ix]}'");

			$const = $this->schema[$vars[$ix]];	
			$st->bindValue(":{$vars[$ix]}", $val, constant("PDO::PARAM_{$const}"));
		}
		$st->bindParam(':id', $this->{$this->id_name});
	 
		if($st->execute())
			return $st->rowCount();
		else 
			return false;	
	}

	/**
	 * delete
	 *
	 * @return mixed int | false
	 */
	function delete()
	{
		$q = "DELETE FROM ".$this->table_name." WHERE ".$this->id_name." = ?";
		$st = $this->conn->prepare($q);
		$st->bindParam(1, $this->{$this->id_name});
	 
		if($st->execute())
			return $st->rowCount();
		else
			return false;	
	}

	/*
		'''Reflection'''
	*/
	
	function inSchema($props, array $excluded = []){
		if (is_object($props))
			$props = (array) $props;
		
		$props = array_keys($props);
		
		if (empty($props))
			throw new \InvalidArgumentException("Properties not found!");
		
		$missing_properties = [];

		foreach ($this->properties as $exp){
			if (!in_array($exp, $props) && !in_array($exp, $excluded)){
				return false; 
			}	
		}

		return true;
	}

	function diffWithSchema($props, array $excluded = []){
		if (is_object($props))
			$props = (array) $props;
		
		$props = array_keys($props);
		
		if (empty($props))
			throw new \InvalidArgumentException("Properties not found!");
		
		$missing_properties = [];

		$excluded = array_merge($this->nullable, $excluded);
		foreach ($this->properties as $ix => $exp){
			if (!in_array($exp, $props) && !in_array($exp, $excluded)){
				$missing_properties[] = $exp; 
			}	
		}

		return $missing_properties;
	}
	
	/**
	 * Get schema 
	 */ 
	public function getSchema()
	{
		return $this->properties;
	}

	/**
	 * Set the value of conn
	 *
	 * @return  self
	 */ 
	function setConn($conn)
	{
		$this->conn = $conn;
		return $this;
	}
}