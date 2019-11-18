<?php
namespace simplerest\core;

use simplerest\libs\Debug;
use simplerest\libs\Arrays;
use simplerest\libs\Validator;
use simplerest\core\interfaces\IValidator;
use simplerest\core\exceptions\InvalidValidationException;

class Model {

	protected $table_name;
	protected $table_alias = '';
	protected $id_name = 'id';
	protected $schema   = [];
	protected $nullable = [];
	protected $fillable;
	protected $hidden;
	protected $properties = [];
	protected $joins  = [];
	protected $show_deleted = false;
	protected $conn;
	protected $fields = [];
	protected $where;
	protected $where_group_op  = [];
	protected $where_having_op = [];
	protected $group  = [];
	protected $having = [];
	protected $w_vars = [];
	protected $h_vars = [];
	protected $w_vals = [];
	protected $h_vals = [];
	protected $order  = [];
	protected $raw_order = [];
	protected $select_raw_q;
	protected $select_raw_vals = [];
	protected $where_raw_q;
	protected $where_raw_vals  = [];
	protected $having_raw_q;
	protected $having_raw_vals = [];
	protected $table_raw_q;
	protected $from_raw_vals   = [];
	protected $union_q;
	protected $union_vals = [];
	protected $union_type;
	protected $randomize = false;
	protected $distinct  = false;
	protected $to_merge_bindings = [];
	protected $limit;
	protected $offset;
	protected $pag_vals = [];
	protected $roles;
	protected $validator;
	protected $fetch_mode = \PDO::FETCH_OBJ;
	
	
	/*
		Chequear en cada método si hay una conexión 
	*/

	public function __construct(\PDO $conn = null){

		if($conn){
			$this->conn = $conn;
			$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}

		//if (empty($this->schema))
		//	throw new \Exception ("Schema is empty!");

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
	}

	public function setFetchMode($mode){
		$this->fetch_mode = constant("PDO::FETCH_{$mode}");
		return $this;
	}

	public function setValidator(IValidator $validator){
		$this->validator = $validator;
		return $this;
	}

	public function setTableAlias($tb_alias){
		$this->table_alias = $tb_alias;
		return $this;
	}

	public function showDeleted($state = true){
		$this->show_deleted = $state;
		return $this;
	}

	protected function from(){
		if (!empty($this->table_raw_q))
			return $this->table_raw_q. ' ';

		return $this->table_name. ' '.(!empty($this->table_alias) ? 'as '.$this->table_alias : '');
	}

	/**
	 * removehidden
	 *
	 * @param  array $fields
	 *
	 * @return void
	 */
	protected function removehidden(&$fields)
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

	function orderByRaw(string $o){
		$this->raw_order[] = $o;
		return $this;
	}

	function take(int $limit){
		$this->limit = $limit;
		return $this;
	}

	function limit(int $limit){
		$this->limit = $limit;
		return $this;
	}

	function offset(int $n){
		$this->offset = $n;
		return $this;
	}

	function skip(int $n){
		$this->offset = $n;
		return $this;
	}

	function groupBy(array $g){
		$this->group = array_merge($this->group, $g);
		return $this;
	}

	function random(){
		$this->randomize = true;

		if (!empty($this->order))
			throw new \Exception("Random order is not compatible with OrderBy clausule");

		return $this;
	}

	function rand(){
		return $this->random();
	}

	function select(array $fields){
		$this->fields = $fields;
		return $this;
	}

	function addSelect(string $field){
		$this->fields[] = $field;
		return $this;
	}

	function selectRaw(string $q, array $vals = null){
		if (substr_count($q, '?') != count((array) $vals))
			throw new \InvalidArgumentException("Number of ? are not consitent with the number of passed values");
		
		$this->select_raw_q = $q;

		if ($vals != null)
			$this->select_raw_vals = $vals;

		return $this;
	}

	function whereRaw(string $q, array $vals = null){
		$qm = substr_count($q, '?'); 

		if ($qm !=0){
			if (!empty($vals)){
				if ($qm != count((array) $vals))
					throw new \InvalidArgumentException("Number of ? are not consitent with the number of passed values");
				
				$this->where_raw_vals = $vals;
			}else{
				if ($qm != count($this->to_merge_bindings))
					throw new \InvalidArgumentException("Number of ? are not consitent with the number of passed values");
					
				$this->where_raw_vals = $this->to_merge_bindings;		
			}

		}
		
		$this->where_raw_q = $q;
	
		return $this;
	}

	function whereExists(string $q, array $vals = null){
		$this->whereRaw("EXISTS $q", $vals);
		return $this;
	}

	function havingRaw(string $q, array $vals = null){
		if (substr_count($q, '?') != count($vals))
			throw new \InvalidArgumentException("Number of ? are not consitent with the number of passed values");
		
		$this->having_raw_q = $q;

		if ($vals != null)
			$this->having_raw_vals = $vals;
			
		return $this;
	}

	function distinct(array $fields = null){
		if ($fields !=  null)
			$this->fields = $fields;
		
		$this->distinct = true;
		return $this;
	}

	function fromRaw(string $q){
		$this->table_raw_q = $q;
		return $this;
	}

	function union(Model $m){
		$this->union_type = 'NORMAL';
		$this->union_q = $m->toSql();
		$this->union_vals = $m->getBindings();
		return $this;
	}

	function unionAll(Model $m){
		$this->union_type = 'ALL';
		$this->union_q = $m->toSql();
		$this->union_vals = $m->getBindings();
		return $this;
	}

	function toSql(array $fields = null, array $order = null, int $limit = NULL, int $offset = null, bool $existance = false, $aggregate_func = null, $aggregate_field = null)
	{
		// Agregar validaciones ?

		if (!empty($fields))
			$fields = array_merge($this->fields, $fields);
		else
			$fields = $this->fields;	

		if (!$existance){
			if (empty($conjunction))
				$conjunction = 'AND';

			/*
				Remover los campos ocultos siempre es un problema
				ya que puede agregar campos donde se necesita una sola columna
			*/
			if (!empty($this->select_raw_vals))	
				$this->removehidden($fields);		

			if ($this->distinct){
				$remove = [$this->id_name];

				if ($this->inSchema(['created_at']))
					$remove[] = 'created_at';

				if ($this->inSchema(['modified_at']))
					$remove[] = 'modified_at';

				if ($this->inSchema(['deleted_at']))
					$remove[] = 'deleted_at';

				if (!empty($fields)){
					$fields = array_diff($fields, $remove);
				}else{
					if (empty($aggregate_func))
						$fields = array_diff($this->getProperties(), $remove);
				}
			} 		

			$order  = (!empty($order) && !$this->randomize) ? array_merge($this->order, $order) : $this->order;
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

					$this->pag_vals = $paginator->getBinding();
				}catch (\Exception $e){
					throw new \Exception("Pagination error: {$e->getMessage()}");
				}
			}else
				$paginator = null;	
		}			


		//Debug::debug($fields, 'FIELDS:');

		if (!$existance){
			if ($aggregate_func != null){
				if (strtoupper($aggregate_func) == 'COUNT'){					
					if ($aggregate_field == null)
						$aggregate_field = '*';

					//Debug::debug($fields, 'FIELDS:');
					//Debug::debug([$aggregate_field], 'AGGREGATE FIELD:');

					if (!empty($fields))
						$_f = implode(", ", $fields). ',';
					else
						$_f = '';

					if ($this->distinct)
						$q  = "SELECT $_f $aggregate_func(DISTINCT $aggregate_field)";
					else
						$q  = "SELECT $_f $aggregate_func($aggregate_field)";
				}else{
					if (!empty($fields))
						$_f = implode(", ", $fields). ',';
					else
						$_f = '';

					$q  = "SELECT $_f $aggregate_func($aggregate_field)";
				}
					
			}else{
				$q = 'SELECT ';

				//Debug::debug($fields);
				
				// SELECT RAW
				if (!empty($this->select_raw_q)){
					$distinct = ($this->distinct == true) ? 'DISTINCT' : '';
					$other_fields = !empty($fields) ? ', '.implode(", ", $fields) : '';
					$q  .= $distinct .' '.$this->select_raw_q. $other_fields;
				}else {
					if (empty($fields))
						$q  .= '*';
					else {
						$distinct = ($this->distinct == true) ? 'DISTINCT' : '';
						$q  .= $distinct.' '.implode(", ", $fields);
					}
				}					
			}
		} else {
			$q  = 'SELECT EXISTS (SELECT 1';
		}	

		$q  .= ' FROM '.$this->from();

		////////////////////////
		$values = array_merge($this->w_vals, $this->h_vals); 
		$vars   = array_merge($this->w_vars, $this->h_vars); 
		////////////////////////

		// Validación
		if (!empty($this->validator)){
			$validado = $this->validator->validate($this->getRules(), array_combine($vars, $values), null, true);
			if ($validado !== true){
				throw new InvalidValidationException($validado);
			} 
		}
		
		// JOINS
		$joins = '';
		foreach ($this->joins as $j){
			$joins .= "$j[4] $j[0] ON $j[1]$j[2]$j[3] ";
		}

		$q  .= $joins;
		
		// WHERE
		$where = '';
		
		if (!empty($this->where_raw_q))
			$where = $this->where_raw_q.' ';

		if (!empty($this->where)){
			$implode = '';

			$cnt = count($this->where);

			if ($cnt>0){
				$implode .= $this->where[0];
				for ($ix=1; $ix<$cnt; $ix++){
					$implode .= ' '.$this->where_group_op[$ix] . ' '.$this->where[$ix];
				}
			}			

			if (!empty($where)){
				$where = "($where) AND ". $implode. ' ';
			}else{
				$where = "$implode ";
			}
		}			

		if ($this->inSchema(['deleted_at'])){
			if (!$this->show_deleted){
				if (empty($where))
					$where = "deleted_at IS NULL";
				else
					$where = "($where) AND deleted_at IS NULL";

			}
		}
		
		$q  .= "WHERE $where";

		$group = (!empty($this->group)) ? 'GROUP BY '.implode(',', $this->group) : '';
		$q  .= " $group";

	
		// HAVING

		$having = ''; 
		if (!empty($this->having_raw_q)){
			$having = 'HAVING '.$this->having_raw_q; 
		}

		if (!empty($this->having)){
			$implode = '';

			$cnt = count($this->having);

			if ($cnt>0){
				$implode .= $this->having[0];
				for ($ix=1; $ix<$cnt; $ix++){
					$implode .= ' '.$this->having_group_op[$ix] . ' '.$this->having[$ix];
				}
			}			

			if (!empty($having)){
				$having = "($having) AND ". $implode. ' ';
			}else{
				$having = "HAVING $implode ";
			}
		}	

		$q .= ' '.$having;


		if ($this->randomize)
			$q .= ' ORDER BY RAND() ';
		else {
			if (!empty($this->raw_order))
				$q .= ' ORDER BY '.implode(', ', $this->raw_order);
		}
		
		// UNION
		if (!empty($this->union_q)){
			$q .= 'UNION '.($this->union_type == 'ALL' ? 'ALL' : '').' '.$this->union_q.' ';
		}

		// PAGINATION
		if (!$existance && $paginator!==null){
			$q .= $paginator->getQuery();
		}
		
		if ($existance)
			$q .= ')';

		//DEBUG::debug($q, 'Query:');
		//DEBUG::debug($vars, 'Vars:');
		//DEBUG::debug($values, 'Vals:');
		//exit;
		//var_dump($q);
		//var_export($vars);
		//var_export($values);

		return $q;	
	}

	function getBindings(){
		$values = array_merge(	
								$this->select_raw_vals,
								$this->from_raw_vals,
								$this->where_raw_vals,
								$this->w_vals,
								$this->having_raw_vals,
								$this->h_vals,
								$this->pag_vals
							);
		
		//Debug::debug($values);

		return $values;
	}

	//
	function mergeBindings(Model $model){
		$this->to_merge_bindings = $model->getBindings();

		if (!empty($this->table_raw_q)){
			$this->from_raw_vals = $this->to_merge_bindings;	
		}

		return $this;
	}

	protected function bind(string $q)
	{
		$st = $this->conn->prepare($q);		

		foreach($this->select_raw_vals as $ix => $val){
				
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			else 
				$type = \PDO::PARAM_STR;	

			$st->bindValue($ix +1, $val, $type);
			//echo "Bind: ".($ix+1)." - $val ($type)\n";
		}
		
		$sh1 = count($this->select_raw_vals);	

		foreach($this->from_raw_vals as $ix => $val){
				
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			else 
				$type = \PDO::PARAM_STR;	

			$st->bindValue($ix +1 + $sh1, $val, $type);
			//echo "Bind: ".($ix+1+$sh1)." - $val ($type) <br/>\n";
		}
		
		$sh2 = count($this->from_raw_vals);	

		foreach($this->where_raw_vals as $ix => $val){
				
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			else 
				$type = \PDO::PARAM_STR;	

			$st->bindValue($ix +1 + $sh1 + $sh2, $val, $type);
			//echo "Bind: ".($ix+1)." - $val ($type)\n";
		}
		
		$sh3 = count($this->where_raw_vals);	


		foreach($this->w_vals as $ix => $val){
				
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(isset($this->w_vars[$ix]) && isset($this->schema[$this->w_vars[$ix]])){
				$const = $this->schema[$this->w_vars[$ix]];
				$type = constant("PDO::PARAM_{$const}");
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			elseif(is_string($val))
				$type = \PDO::PARAM_STR;	

			$st->bindValue($ix +1 + $sh1 + $sh2 + $sh3, $val, $type);
			//echo "Bind: ".($ix+1)." - $val ($type)\n";
		}

		$sh4 = count($this->w_vals);


		foreach($this->having_raw_vals as $ix => $val){
				
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			else 
				$type = \PDO::PARAM_STR;	

			$st->bindValue($ix +1 + $sh1 + $sh2 + $sh3 + $sh4, $val, $type);
			//echo "Bind: ".($ix+1)." - $val ($type)\n";
		}

		$sh5 = count($this->having_raw_vals);


		foreach($this->h_vals as $ix => $val){
				
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(isset($this->h_vars[$ix]) && isset($this->schema[$this->h_vars[$ix]])){
				$const = $this->schema[$this->h_vars[$ix]];
				$type = constant("PDO::PARAM_{$const}");
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			elseif(is_string($val))
				$type = \PDO::PARAM_STR;	

			$st->bindValue($ix +1 + $sh1 + $sh2 + $sh3 + $sh4 +$sh5, $val, $type);
			//echo "Bind: ".($ix+1)." - $val ($type)\n";
		}

		$sh6 = count($this->h_vals);
	

		foreach($this->union_vals as $ix => $val){
				
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			else 
				$type = \PDO::PARAM_STR;	

			$st->bindValue($ix +1 + $sh1 + $sh2 + $sh3 + $sh4 + $sh5 +$sh6, $val, $type);
			//echo "Bind: ".($ix+1)." - $val ($type)\n";
		}

		$sh7 = count($this->union_vals);


		$bindings = $this->pag_vals;
		foreach($bindings as $ix => $binding){
			$st->bindValue($ix +1 +$sh1 +$sh2 +$sh3 +$sh4 +$sh5 +$sh6 +$sh7, $binding[1], $binding[2]);
		}		
		
		return $st;	
	}

	function get(array $fields = null, array $order = null, int $limit = NULL, int $offset = null){
		$q = $this->toSql($fields, $order, $limit, $offset);
		$st = $this->bind($q);

		if ($st->execute())
			return $st->fetchAll($this->fetch_mode);
		else
			return false;	
	}

	function first(array $fields = null){
		$q = $this->toSql($fields, NULL, 1);
		$st = $this->bind($q);

		if ($st->execute())
			return $st->fetch($this->fetch_mode);
		else
			return false;	
	}
	
	function value($field){
		$q = $this->toSql([$field], NULL, 1);
		$st = $this->bind($q);

		if ($st->execute())
			return $st->fetch(\PDO::FETCH_NUM)[0];
		else
			return false;	
	}

	function exists(){
		$q = $this->toSql(null, null, null, null, true);
		$st = $this->bind($q);

		if ($st->execute())
			return (bool) $st->fetch(\PDO::FETCH_NUM)[0];
		else
			return false;	
	}

	function pluck(string $field){
		$this->setFetchMode('COLUMN');
		$this->fields = [$field];

		$q = $this->toSql();
		$st = $this->bind($q);
	
		if ($st->execute())
			return $st->fetchAll($this->fetch_mode);
		else
			return false;	
	}

	function avg($field){
		$q = $this->toSql(null, null, null, null, false, 'AVG', $field);
		$st = $this->bind($q);

		if (empty($this->group)){
			if ($st->execute())
				return $st->fetch(\PDO::FETCH_NUM)[0];
			else
				return false;	
		}else{
			if ($st->execute())
				return $st->fetchAll(\PDO::FETCH_NUM);
			else
				return false;
		}	
	}

	function sum($field){
		$q = $this->toSql(null, null, null, null, false, 'SUM', $field);
		$st = $this->bind($q);

		if (empty($this->group)){
			if ($st->execute())
				return $st->fetch(\PDO::FETCH_NUM)[0];
			else
				return false;	
		}else{
			if ($st->execute())
				return $st->fetchAll(\PDO::FETCH_NUM);
			else
				return false;
		}	
	}

	function min($field){
		$q = $this->toSql(null, null, null, null, false, 'MIN', $field);
		$st = $this->bind($q);

		if (empty($this->group)){
			if ($st->execute())
				return $st->fetch(\PDO::FETCH_NUM)[0];
			else
				return false;	
		}else{
			if ($st->execute())
				return $st->fetchAll(\PDO::FETCH_NUM);
			else
				return false;
		}		
	}

	function max($field){
		$q = $this->toSql(null, null, null, null, false, 'MAX', $field);
		$st = $this->bind($q);

		if (empty($this->group)){
			if ($st->execute())
				return $st->fetch(\PDO::FETCH_NUM)[0];
			else
				return false;	
		}else{
			if ($st->execute())
				return $st->fetchAll(\PDO::FETCH_NUM);
			else
				return false;
		}	
	}

	function count($field = null){
		$q = $this->toSql(null, null, null, null, false, 'COUNT', $field);
		$st = $this->bind($q);

		if (empty($this->group)){
			if ($st->execute())
				return $st->fetch(\PDO::FETCH_NUM)[0];
			else
				return false;	
		}else{
			if ($st->execute())
				return $st->fetchAll(\PDO::FETCH_NUM);
			else
				return false;
		}
		
	}


	function _where($conditions, $group_op = 'AND', $conjunction)
	{	
		if (Arrays::is_assoc($conditions)){
			$conditions = Arrays::nonassoc($conditions);
		}

		if (isset($conditions[0]) && is_string($conditions[0]))
			$conditions = [$conditions];

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

		////////////////////////////////////////////
		// group
		$ws_str = implode(" $conjunction ", $_where);
		
		if (count($conditions)>1)
			$ws_str = "($ws_str)";
		
		$this->where_group_op[] = $group_op;	

		$this->where[] = ' ' .$ws_str;
		////////////////////////////////////////////

		//Debug::debug($this->where);
		//Debug::debug($this->w_vars, 'WHERE VARS');	
		//Debug::debug($this->w_vals, 'WHERE VALS');	

		return $this;
	}

	function where($conditions, $conjunction = 'AND'){
		$this->_where($conditions, 'AND', $conjunction);
		return $this;
	}

	function orWhere($conditions, $conjunction = 'AND'){
		$this->_where($conditions, 'OR', $conjunction);
		return $this;
	}

	function orHaving($conditions, $conjunction = 'AND'){
		$this->_having($conditions, 'OR', $conjunction);
		return $this;
	}

	function find(int $id){
		return $this->where([$this->id_name => $id])->get();
	}

	function whereNull(string $field){
		$this->where([$field, NULL]);
		return $this;
	}

	function whereNotNull(string $field){
		$this->where([$field, NULL, 'IS NOT']);
		return $this;
	}

	function whereIn(string $field, array $vals){
		$this->where([$field, $vals, 'IN']);
		return $this;
	}

	function whereNotIn(string $field, array $vals){
		$this->where([$field, $vals, 'NOT IN']);
		return $this;
	}

	function whereBetween(string $field, array $vals){
		if (count($vals)!=2)
			throw new \InvalidArgumentException("whereBetween accepts an array of exactly two items");

		$min = min($vals[0],$vals[1]);
		$max = max($vals[0],$vals[1]);

		$this->where([$field, $min, '>=']);
		$this->where([$field, $max, '<=']);
		return $this;
	}

	function whereNotBetween(string $field, array $vals){
		if (count($vals)!=2)
			throw new \InvalidArgumentException("whereBetween accepts an array of exactly two items");

		$min = min($vals[0],$vals[1]);
		$max = max($vals[0],$vals[1]);

		$this->where([
						[$field, $min, '<'],
						[$field, $max, '>']
		], 'OR');
		return $this;
	}

	function oldest(){
		$this->orderBy(['created_at' => 'DESC']);
		return $this;
	}

	function latest(){
		$this->orderBy(['created_at' => 'DESC']);
		return $this;
	}

	function newest(){
		$this->orderBy(['created_at' => 'ASC']);
		return $this;
	}

	
	function _having(array $conditions, $group_op = 'AND', $conjunction)
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

		////////////////////////////////////////////
		// group
		$ws_str = implode(" $conjunction ", $_having);
		
		if (count($conditions)>1)
			$ws_str = "($ws_str)";
		
		$this->having_group_op[] = $group_op;	

		$this->having[] = ' ' .$ws_str;
		////////////////////////////////////////////

		//Debug::debug($this->having, 'HAVING:');
		//Debug::debug($this->h_vars, 'VARS');
		//Debug::debug($this->h_vals, 'VALUES');

		return $this;
	}

	function having(array $conditions, $conjunction = 'AND'){
		$this->_having($conditions, 'AND', $conjunction);
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

		// Validación
		if (!empty($this->validator)){
			$validado = $this->validator->validate($this->getRules(), $data, null, true);
			if ($validado !== true){
				throw new InvalidValidationException($validado);
			} 
		}
		
		$set = '';
		foreach($vars as $ix => $var){
			$set .= " $var = ?, ";
		}
		$set =trim(substr($set, 0, strlen($set)-2));

		if ($this->inSchema(['modified_at'])){
			$set .= ', modified_at = NOW()';
		}

		$where = implode(' AND ', $this->where);

		$q = "UPDATE ".$this->from() .
				" SET $set WHERE " . $where;		
	
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
		// Validación
		if (!empty($this->validator)){
			$validado = $this->validator->validate($this->getRules(), array_combine($this->w_vars, $this->w_vals), null, true);
			if ($validado !== true){
				throw new \InvalidValidationException($validado);
			} 
		}

		if ($soft_delete){
			if (!$this->inSchema(['deleted_at'])){
				throw new \Exception("There is no 'deleted_at' for ".$this->from(). ' schema');
			} 

			$d = new \DateTime();
			$at = $d->format('Y-m-d G:i:s');

			return $this->update(['deleted_at' => $at]);
		}

		$where = implode(' AND ', $this->where);

		$q = "DELETE FROM ". $this->from() . " WHERE " . $where;
		
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

		// Validación
		if (!empty($this->validator)){
			$validado = $this->validator->validate($this->getRules(), $data, null, true);
			if ($validado !== true){
				throw new \InvalidValidationException($validado);
			} 
		}

		$str_vars = implode(', ',$vars);

		$symbols = array_map(function($v){ return ":$v";}, $vars);
		$str_vals = implode(', ',$symbols);

		if ($this->inSchema(['created_at'])){
			$str_vars .= ', created_at';
			$str_vals .= ', NOW()';
		}

		$q = "INSERT INTO " . $this->from() . " ($str_vars) VALUES ($str_vals)";
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

	public function getNotHidden(){
		return array_diff($this->properties, $this->hidden);
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
	function setConn(\PDO $conn)
	{
		$this->conn = $conn;
		return $this;
	}
}