<?php
namespace simplerest\core;

use simplerest\libs\Debug;
use simplerest\libs\Arrays;
use simplerest\libs\Validator;

class Model {

	protected $table_name;
	protected $table_alias = '';
	protected $id_name = 'id';
	protected $schema;
	protected $nullable = [];
	protected $fillable;
	protected $hidden;
	protected $properties = [];
	protected $joins = [];
	protected $show_deleted = false;
	protected $conn;
	protected $where;
	protected $group  = [];
	protected $having = [];
	protected $w_vars = [];
	protected $h_vars = [];
	protected $w_vals = [];
	protected $h_vals = [];
	protected $order = [];
	protected $limit;
	protected $offset;
	protected $roles;
	

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

		if (empty($this->table_name)){
			$class_name = get_class($this);
			$class_name = substr($class_name, strrpos($class_name, '\\')+1);
			$this->table_name = strtolower(substr($class_name, 0, strlen($class_name)-5));
		}			

		if ($this->fillable == NULL){
			$this->fillable = $this->properties;
			$this->unfill([$this->id_name, 'created_at', 'modified_at', 'deleted_at', 'locked']);
		}

		$this->nullable[] = $this->id_name;
		$this->nullable[] = 'locked';
		$this->nullable[] = 'belongs_to';
		$this->nullable[] = 'created_at';
		$this->nullable[] = 'modified_at';
		$this->nullable[] = 'deleted_at';

		// Validations
		
		if (!empty($this->rules)){
			foreach ($this->rules as $field => $rule){
				if (!isset($this->rules[$field]['type']) || empty($this->rules[$field]['type'])){
					$this->rules[$field]['type'] = strtolower($this->schema[$field]);
				}
			}
		}
		
		
		foreach ($this->schema as $field => $type){
			if (!isset($this->rules[$field])){
				$this->rules[$field]['type'] = strtolower($type);
			}

			if (!$this->isNullable($field)){
				$this->rules[$field]['required'] = true;
			}
		}
			
		//var_export($this->rules);
		
	}

	public function setTableAlias($tb_alias){
		$this->table_alias = $tb_alias;
		return $this;
	}

	public function showDeleted($state = true){
		$this->show_deleted = $state;
		return $this;
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
		return $this;
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

		return $this;	
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

		return $this;	
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

		return $this;
	}

	// INNER JOIN
	function join($table, $on1, $op, $on2) {
		$this->joins[] = [$table, $on1, $op, $on2, ' INNER JOIN'];
		return $this;
	}

	function leftJoin($table, $on1, $op, $on2) {
		$this->joins[] = [$table, $on1, $op, $on2, ' LEFT JOIN'];
		return $this;
	}

	function rightJoin($table, $on1, $op, $on2) {
		$this->joins[] = [$table, $on1, $op, $on2, ' RIGHT JOIN'];
		return $this;
	}
	
	function orderBy(array $o){
		$this->order = array_merge($this->order, $o);
		return $this;
	}

	function take(int $limit){
		$this->limit = $limit;
		return $this;
	}

	function offset(int $n){
		$this->offset = $n;
		return $this;
	}

	function groupBy(array $g){
		$this->group = array_merge($this->group, $g);
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

		$q  .= " FROM ".(empty($this->table_name) ? '' : 'as '.$this->table_alias.' ')." WHERE ".$this->id_name." = :id";

		if ($this->inSchema(['deleted_at'])){
			if (!$this->show_deleted)
				$q  .= " AND deleted_at IS NULL";	
		}

		$st = $this->conn->prepare($q);
		$st->bindParam(":id", $this->{$this->id_name}, constant('PDO::PARAM_'.$this->schema[$this->id_name]));
		$st->execute();
		
		$row = $st->fetch(\PDO::FETCH_OBJ);

		if ($row === false)
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
	function fetchAll(array $fields = null, array $order = NULL, int $limit = NULL, int $offset = null)
	{
		if ($this->inSchema(['deleted_at'])){
			return $this->get($fields, $order, $limit, $offset);
		}

		$this->removehidden($fields);

		$order  = !empty($order) ? array_merge($this->order, $order) : $this->order;
		$limit  = $limit  ?? $this->limit  ?? null;
		$offset = $offset ?? $this->offset ?? 0; 

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

		$q  .= ' FROM '.$this->table_name. ' '.(!empty($this->table_alias) ? 'as '.$this->table_alias : '');

		/// start pagination
		if($paginator!==null)
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
	 * get
	 *
	 * @param  array $fields
	 * @param  array $order
	 * @param  int $limit
	 * @param  int $offset
	 *
	 * @return array | false
	 */
	function get(array $fields = null, array $order = null, int $limit = NULL, int $offset = null)
	{
		if (empty($conjunction))
			$conjunction = 'AND';

		$this->removehidden($fields);	

		$order  = !empty($order) ? array_merge($this->order, $order) : $this->order;
		$limit  = $limit  ?? $this->limit  ?? null;
		$offset = $offset ?? $this->offset ?? 0; 

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

		if (empty($fields))
			$q  = 'SELECT *';
		else {
			//$fields = array_intersect($fields, $this->properties);
			$q  = "SELECT ".implode(", ", $fields);
		}

		$q  .= ' FROM '.$this->table_name. ' '.(!empty($this->table_alias) ? 'as '.$this->table_alias : '');

		////////////////////////
		$where  = $this->where;
		$values = array_merge($this->w_vals, $this->h_vals);
		$vars   = array_merge($this->w_vars, $this->h_vars);
		////////////////////////

		$shift = substr_count($where, '?');
		
		// JOINS
		$joins = '';
		foreach ($this->joins as $j){
			$joins .= "$j[4] $j[0] ON $j[1]$j[2]$j[3] ";
		}

		$q  .= $joins;
		
		if (empty($where))
			$where = 1;
		
		$q  .= "WHERE $where";

		if ($this->inSchema(['deleted_at'])){
			if (!$this->show_deleted)
				$q  .= (empty(trim($where)) ? '' : ' AND') . " deleted_at IS NULL";	
		}

		$group = (!empty($this->group)) ? 'GROUP BY '.implode(',', $this->group) : '';
		$q  .= " $group";

		$having = (!empty($this->having)) ? 'HAVING '.$this->having : '';
		$q  .= " $having";

		if($paginator!==null){
			$q .= $paginator->getQuery();
		}
		
		//DEBUG::debug($q, 'Query:');
		//DEBUG::debug($vars, 'Vars:');
		//DEBUG::debug($values, 'Vals:');
		
		//var_dump($q);
		//var_export($vars);
		//var_export($values);

		$st = $this->conn->prepare($q);
				
		foreach($values as $ix => $val){
			
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(isset($vars[$ix]) && isset($this->schema[$vars[$ix]])){
				$const = $this->schema[$vars[$ix]];
				$type = constant("PDO::PARAM_{$const}");
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			elseif(is_string($val))
				$type = \PDO::PARAM_STR;	

			$st->bindValue($ix+1, $val, $type);
			//echo "Bind: ".($ix+1)." - $val ($type)\n";
		}

			
		if ($paginator !== null){
			$bindings = $paginator->getBinding();
			foreach($bindings as $ix => $binding){
				$st->bindValue($shift +$ix +1, $binding[1], $binding[2]);
				//echo "Bind: ".($shift +$ix +1)." - $binding[1] ($binding[2])\n";
			}	
		}			

		if ($st->execute())
			return $st->fetchAll(\PDO::FETCH_ASSOC);
		else
			return false;	
	}
	
	/**
	 * where
	 *
	 * @param  array  $conditions
	 * @param  string $conjunction
	 *
	 * @return object
	 */
	function where(array $conditions, $conjunction = 'AND')
	{		
		if (Arrays::is_assoc($conditions)){
			$conditions = Arrays::nonassoc($conditions);
		}

		$_where = [];

		$vars   = [];
		$ops    = [];
		if (count($conditions)>0){
			if(is_array($conditions[Arrays::array_key_first($conditions)])){
				foreach ($conditions as $cond) {
					if(is_array($cond[1]) && (empty($cond[2]) || in_array($cond[2], ['IN', 'NOT IN']) ))
					{						
						if($this->schema[$cond[0]] == 'STR')	
							$cond[1] = array_map(function($e){ return "'$e'";}, $cond[1]);   
						
						$in_val = implode(', ', $cond[1]);
						
						$op = isset($cond[2]) ? $cond[2] : 'IN';
						$_where[] = "$cond[0] $op ($in_val) ";	
					}else{
						$vars[]   = $cond[0];
						$this->w_vals[] = $cond[1];

						if ($cond[1] === NULL && (empty($cond[2]) || $cond[2]=='='))
							$ops[] = 'IS';
						else	
							$ops[] = $cond[2] ?? '=';
					}	
				}
			}else{
				$vars[]   = $conditions[0];
				$this->w_vals[] = $conditions[1];
		
				if ($conditions[1] === NULL && (empty($conditions[2]) || $conditions[2]== '='))
					$ops[] = 'IS';
				else	
					$ops[] = $conditions[2] ?? '='; 
			}	
		}

		foreach($vars as $ix => $var){
			$_where[] = "$var $ops[$ix] ?";
		}

		$this->w_vars = $vars;

		$this->where = implode(" $conjunction ", $_where);
		return $this;
	}

	function having(array $conditions, $conjunction = 'AND')
	{	
		if (Arrays::is_assoc($conditions)){
            $conditions = Arrays::nonassoc($conditions);
        }

		if ((count($conditions) == 3 || count($conditions) == 2) && !is_array($conditions[1]))
			$conditions = [$conditions];
	
		//Debug::debug($conditions, 'COND:');

		$_having = [];
		foreach ((array) $conditions as $cond) {		
		
			if (Arrays::is_assoc($cond)){
				//Debug::debug($cond, 'COND PRE-CAMBIO');
				$cond[0] = Arrays::array_key_first($cond);
				$cond[1] = $cond[$cond[0]];

				//Debug::debug([$cond[0], $cond[1]], 'COND POST-CAMBIO');
			}
			
			$op = $cond[2] ?? '=';	
			
			$_having[] = "$cond[0] $op ?";
			$this->h_vars[] = $cond[0];
			$this->h_vals[] = $cond[1];
		}

		$this->having = implode(" $conjunction ", $_having);

		//Debug::debug($this->having, 'HAVING:');
		//Debug::debug($this->h_vars, 'VARS');
		//Debug::debug($this->h_vals, 'VALUES');

		return $this;
	}

	/**
	 * update
	 * It admits partial updates
	 *
	 * @param  array $data
	 *
	 * @return mixed
	 * 
	 */
	function update(array $data)
	{
		if (!Arrays::is_assoc($data))
			throw new \InvalidArgumentException('Array of data should be associative');

		$vars   = array_keys($data);
		$values = array_values($data);

		if(!empty($this->fillable) && is_array($this->fillable)){
			foreach($vars as $var){
				if (!in_array($var,$this->fillable))
					throw new \InvalidArgumentException("update: $var is no fillable");
			}
		}

		$validado = (new Validator)->validate($this->getRules(), $data);
		if ($validado !== true){
			//Debug::debug($validado);
			
			$e = [];
			foreach ($validado as $field => $errors){
				$fe = [];
				foreach ($errors as $error){
					$fe[] = $error['error_detail'];
				}
				$e[] = "$field => ". implode(' & ', $fe);
			}	

			throw new \InvalidArgumentException('Data validation error '. implode ('; ', $e));
		}  
		
		$set = '';
		foreach($vars as $ix => $var){
			$set .= " $var = ?, ";
		}
		$set =trim(substr($set, 0, strlen($set)-2));

		if ($this->inSchema(['modified_at'])){
			$d = new \DateTime();
			$set .= ', modified_at = "'. $d->format('Y-m-d G:i:s').'"';
		}   

		$q = "UPDATE ".$this->table_name .
				" SET $set WHERE " . $this->where;		
	
		$st = $this->conn->prepare($q);

		$values = array_merge($values, $this->w_vals);
		$vars   = array_merge($vars, $this->w_vars);

		//var_export($q);
		//var_export($vars);
		//var_export($values);

		foreach($values as $ix => $val){			
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(isset($vars[$ix]) && isset($this->schema[$vars[$ix]])){
				$const = $this->schema[$vars[$ix]];
				$type = constant("PDO::PARAM_{$const}");
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			elseif(is_string($val))
				$type = \PDO::PARAM_STR;	

			$st->bindValue($ix+1, $val, $type);
			//echo "Bind: ".($ix+1)." - $val ($type)\n";
		}
	 
		if($st->execute())
			return $st->rowCount();
		else 
			return false;	
	}

	/**
	 * delete
	 *
	 * @param  bool  $soft_delete 
	 * @return mixed
	 */
	function delete($soft_delete = true)
	{
		if ($soft_delete){
			if (!$this->inSchema(['deleted_at'])){
				throw new \Exception("There is no 'deleted_at' for ".$this->table_name. ' schema');
			} 

			$d = new \DateTime();
			$at = $d->format('Y-m-d G:i:s');

			return $this->update(['deleted_at' => $at]);
		}

		$q = "DELETE FROM ". $this->table_name . " WHERE " . $this->where;
		
		$st = $this->conn->prepare($q);
		
		$vars = $this->vars;
		foreach($this->values as $ix => $val){			
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(isset($vars[$ix]) && isset($this->schema[$vars[$ix]])){
				$const = $this->schema[$vars[$ix]];
				$type = constant("PDO::PARAM_{$const}");
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			elseif(is_string($val))
				$type = \PDO::PARAM_STR;	

			$st->bindValue($ix+1, $val, $type);
			//echo "Bind: ".($ix+1)." - $val ($type)\n";
		}
	 
		if($st->execute())
			return $st->rowCount();
		else 
			return false;		
	}

	/*
		@return mixed false | integer 
	*/
	function create(array $data)
	{
		if (!Arrays::is_assoc($data))
			throw new \InvalidArgumentException('Array of data should be associative');

		$vars   = array_keys($data);
		$vals = array_values($data);

		if(!empty($this->fillable) && is_array($this->fillable)){
			foreach($vars as $var){
				if (!in_array($var,$this->fillable))
					throw new \InvalidArgumentException("$var is no fillable");
			}
		}

		$validado = (new Validator)->validate($this->getRules(), $data);
		if ($validado !== true){
			//Debug::debug($validado);

			$e = [];
			foreach ($validado as $field => $errors){
				$fe = [];
				foreach ($errors as $error){
					$fe[] = $error['error_detail'];
				}
				$e[] = "$field => ". implode(' & ', $fe);
			}	

			throw new \InvalidArgumentException('Data validation error '. implode ('; ', $e));
		}  

		$str_vars = implode(', ',$vars);

		$symbols = array_map(function($v){ return ":$v";}, $vars);
		$str_vals = implode(', ',$symbols);

		$q = "INSERT INTO ".$this->table_name. ' '.$this->table_alias." ($str_vars) VALUES ($str_vals)";
		$st = $this->conn->prepare($q);
		
		foreach($vals as $ix => $val){
			$const = $this->schema[$vars[$ix]];
			$st->bindValue(":{$vars[$ix]}", $val, constant("PDO::PARAM_{$const}"));
		}
		
		$result = $st->execute();
		if ($result){
			return $this->{$this->id_name} = $this->conn->lastInsertId();
		}else
			return false;
	}
		
	/*
		'''Reflection'''
	*/
	
	/**
	 * inSchema
	 *
	 * @param  array $props
	 *
	 * @return bool
	 */
	function inSchema(array $props){

		if (empty($props))
			throw new \InvalidArgumentException("Properties not found!");
		
		foreach ($props as $prop)
			if (!in_array($prop, $this->properties)){
				return false; 
			}	
		
		return true;
	}

	/**
	 * getMissing
	 *
	 * @param  array $fields
	 *
	 * @return array
	 */
	function getMissing(array $fields){
		$diff =  array_diff($this->properties, array_keys($fields));
		return array_diff($diff, $this->nullable);
	}

	// dejar de utilizar (remover)
	/*
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
	*/
	
	/**
	 * Get schema 
	 */ 
	public function getProperties()
	{
		return $this->properties;
	}

	public function isNullable(string $field){
		return in_array($field, $this->nullable);
	}

	public function isFillable(string $field){
		return in_array($field, $this->fillable);
	}

	public function getFillables(){
		return $this->fillable;
	}

	public function getRules(){
		return $this->rules;
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