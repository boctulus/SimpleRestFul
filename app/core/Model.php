<?php

namespace simplerest\core;

use simplerest\libs\DB;
use simplerest\libs\Arrays;
use simplerest\libs\Strings;
use simplerest\libs\Validator;
use simplerest\libs\ValidationRules;
use simplerest\libs\Factory;
use simplerest\libs\Debug;
use simplerest\core\interfaces\IValidator;
use simplerest\core\exceptions\InvalidValidationException;
use simplerest\core\exceptions\SqlException;
use simplerest\core\interfaces\ITransformer;
use simplerest\traits\ExceptionHandler;


class Model {
	use ExceptionHandler;

	// for internal use
	protected $table_alias = '';
	protected $table_name;

	// Schema
	protected $schema;

	protected $fillable = [];
	protected $not_fillable = [];
	protected $hidden   = [];
	protected $attributes = [];
	protected $joins  = [];
	protected $show_deleted = false;
	protected $conn;
	protected $fields = [];
	protected $where  = [];
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
	protected $last_pre_compiled_query;
	protected $last_bindings = [];
	protected $limit;
	protected $offset;
	protected $pag_vals = [];
	protected $roles;
	protected $validator;
	protected $input_mutators = [];
	protected $output_mutators = [];
	protected $transformer;
	protected $controller;
	protected $exec = true;
	protected $fetch_mode;
	protected $soft_delete;
	protected $last_inserted_id;
	protected $paginator = true;
	protected $fetch_mode_default = \PDO::FETCH_ASSOC;
	protected $data = []; 

	
	function __construct(bool $connect = false, $schema = null){
		if ($connect){
			$this->connect();
		}

		if ($schema != null){
			$this->schema = $schema->get();
			$this->table_name = $this->schema['table_name'];
		}

		$this->config = Factory::config();

		if ($this->config['error_handling']) {
            set_exception_handler([$this, 'exception_handler']);
		}
		
		/////////////// ***
		/*
		if (empty($this->table_name)){
			$class_name = get_class($this);
			$class_name = substr($class_name, strrpos($class_name, '\\')+1);
			$str = Strings::fromCamelCase($class_name);
			$this->table_name = strtolower(substr($str, 0, strlen($str)-6));
		}
		*/
	

		//Debug::dd($this->table_name, 'table_name:');  // mode <-- por "model" recortado!!
		
		if ($this->schema == null){
			return;
		}	

		//Debug::dd($this->schema, 'SCHEMA:');

		$this->attributes = array_keys($this->schema['attr_types']);
		
		if ($this->schema['id_name'] == NULL){
			if ($this->inSchema(['id'])){
				$this->schema['id_name'] = 'id';
			} else {
				throw new \Exception("Undefined table identifier for '".$this->table_name. "' Use 'id' or \$id_name to specify another field name");
			}
		}			


		if ($this->fillable == NULL){
			$this->fillable = $this->attributes;
			$this->unfill(['locked', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by']);
		}

		$this->unfill($this->not_fillable);

		// innecesario, debería provenir del propio schema !
		$this->schema['nullable'][] = 'locked';
		$this->schema['nullable'][] = 'belongs_to';
		$this->schema['nullable'][] = 'created_at';
		$this->schema['nullable'][] = 'updated_at';
		$this->schema['nullable'][] = 'deleted_at';
		$this->schema['nullable'][] = 'created_by';
		$this->schema['nullable'][] = 'updated_by';
		$this->schema['nullable'][] = 'deleted_by';

		$to_fill = [$this->schema['id_name']];

		if ($this->inSchema(['created_by'])){
			$to_fill[] = 'created_by';
		}

		if ($this->inSchema(['updated_by'])){
			$to_fill[] = 'updated_by';
		}

		$this->fill($to_fill);				
		
		$this->soft_delete = $this->inSchema(['deleted_at']);
	
		/*
		 Validations
		*/
		if (!empty($this->schema['rules'])){
			foreach ($this->schema['rules'] as $field => $rule){
				if (!isset($this->schema['rules'][$field]['type']) || empty($this->schema['rules'][$field]['type'])){
					$this->schema['rules'][$field]['type'] = strtolower($this->schema['attr_types'][$field]);
				}
			}
		}
		
		foreach ($this->schema['attr_types'] as $field => $type){
			if (!isset($this->schema['rules'][$field])){
				$this->schema['rules'][$field]['type'] = strtolower($type);
			}

			if (!$this->isNullable($field)){
				$this->schema['rules'][$field]['required'] = true;
			}
		}
		
		// event handler
		$this->boot();
	}


	function addRules(ValidationRules $vr){
		$this->schema['rules'] = array_merge($this->schema['rules'], $vr->getRules());
	}

	/*
		Returns prmary key
	*/
	function getKeyName(){
		return $this->schema['id_name'];
	}

	/*
		Turns on / off pagination
	*/
	function setPaginator(bool $status){
		$this->paginator = $status;
		return $this;
	}


	function registerInputMutator(string $field, callable $fn, ?callable $apply_if_fn){
		$this->input_mutators[$field] = [$fn, $apply_if_fn];
		return $this;
	}

	function registerOutputMutator(string $field, callable $fn){
		$this->output_mutators[$field] = $fn;
		return $this;
	}

	// acepta un Transformer
	function registerTransformer(ITransformer $t, $controller = NULL){
		$this->unhideAll();
		$this->transformer = $t;
		$this->controller  = $controller;
		return $this;
	}
	
	function applyInputMutator(array $data, string $current_op){	
		if ($current_op != 'CREATE' && $current_op != 'UPDATE'){
			throw new \InvalidArgumentException("Operation '$current_op' is invalid for Input Mutator");
		}

		foreach ($this->input_mutators as $field => list($fn, $apply_if_fn)){
			if (!in_array($field, $this->getAttr()))
				throw new \Exception("Invalid accesor: $field field is not present in " . $this->table_name); 

			$dato = $data[$field] ?? NULL;
					
			if ($apply_if_fn == null || $apply_if_fn(...[$current_op, $dato])){				
				$data[$field] = $fn($dato);
			} 				
		}

		return $data;
	}

	/*
		Es complicado hacerlo funcionar y falla cuando se selecciona un único registro
		quizás por el FETCH_MODE

		Está confirmado que si el FETCH_MODE no es ASSOC, va a fallar
	*/
	function applyOutputMutators($rows){
		if (empty($rows))
			return;
		
		if (empty($this->output_mutators))
			return $rows;

		//$by_id = in_array('id', $this->w_vars);	
		
		foreach ($this->output_mutators as $field => $fn){
			if (!in_array($field, $this->getAttr()))
				throw new \Exception("Invalid transformer: $field field is not present in " . $this->table_name); 

			if ($this->getFetchMode() == \PDO::FETCH_ASSOC){
				foreach ($rows as $k => $row){
					$rows[$k][$field] = $fn($row[$field]);
				}
			}elseif ($this->getFetchMode() == \PDO::FETCH_OBJ){
				foreach ($rows as $k => $row){
					$rows[$k]->$field = $fn($row->$field);
				}
			}			
		}
		return $rows;
	}
	
	function applyTransformer($rows){
		if (empty($rows))
			return;
		
		if (empty($this->transformer))
			return $rows;
		
		foreach ($rows as $k => $row){
			//var_dump($row);

			if (is_array($row))
				$row = (object) $row;

			$rows[$k] = $this->transformer->transform($row, $this->controller);
		}

		return $rows;
	}


	function setFetchMode(string $mode){
		$this->fetch_mode = constant("PDO::FETCH_{$mode}");
		return $this;
	}

	function assoc(){
		$this->fetch_mode = \PDO::FETCH_ASSOC;
		return $this;
	}

	protected function getFetchMode($mode_wished = null){
		if ($this->fetch_mode == NULL){
			if ($mode_wished != NULL) {
				return constant("PDO::FETCH_{$mode_wished}");
			} else {
				return $this->fetch_mode_default;
			}
		} else {
			return $this->fetch_mode;
		}
	}

	function setValidator(IValidator $validator){
		$this->validator = $validator;
		return $this;
	}

	function setTableAlias($tb_alias){
		$this->table_alias = $tb_alias;
		return $this;
	}

	// debe remover cualquier condición que involucre a 'deleted_at' en el WHERE !!!!
	function showDeleted($state = true){
		$this->show_deleted = $state;
		return $this;
	}

	function setSoftDelete(bool $status) {
		if (!$this->inSchema(['deleted_at'])){
			if ($status){
				throw new SqlException("There is no 'deleted_at' for table '".$this->from()."' in the attr_types");
			}
		} 
		
		$this->soft_delete = $status;
		return $this;
	}
	

	/*
		Don't execute the query
	*/
	function dontExec(){
		$this->exec = false;
		return $this;
	}

	// set table and alias
	function table(string $table, $table_alias = null){
		$this->table_name = $table;
		$this->table_alias = $table_alias;
		return $this;		
	}

	protected function from(){
		if ($this->table_raw_q != null){
			return $this->table_raw_q;
		}

		if ($this->table_name == null){
			throw new \Exception("No table_name defined");
			$this->table_name = '';
		}

		$from = $this->table_alias != null ? $this->table_name. ' as '.$this->table_alias : $this->table_name.' ';  
		return $from;
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

	function unhideAll(){
		$this->hidden = [];
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

	/*
		Make all fields fillable
	*/
	function fillAll(){
		$this->fillable = $this->attributes;
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
			throw new SqlException("Random order is not compatible with OrderBy clausule");

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

	function toSql(array $fields = null, array $order = null, int $limit = NULL, int $offset = null, bool $existance = false, $aggregate_func = null, $aggregate_field = null, $aggregate_field_alias = NULL)
	{		
		if (!empty($fields))
			$fields = array_merge($this->fields, $fields);
		else
			$fields = $this->fields;	

		if (!$existance){
			if (empty($conjunction))
				$conjunction = 'AND';

			// remove hidden
			
			if (!empty($this->hidden)){			
			
				if (empty($this->select_raw_q)){
					if (empty($fields) && $aggregate_func == null) {
						$fields = $this->attributes;
					}
		
					foreach ($this->hidden as $h){
						$k = array_search($h, $fields);
						if ($k != null)
							unset($fields[$k]);
					}
				}			

			}

							
			if ($this->distinct){
				$remove = [$this->schema['id_name']];

				if ($this->inSchema(['created_at']))
					$remove[] = 'created_at';

				if ($this->inSchema(['updated_at']))
					$remove[] = 'updated_at';

				if ($this->inSchema(['deleted_at']))
					$remove[] = 'deleted_at';

				if (!empty($fields)){
					$fields = array_diff($fields, $remove);
				}else{
					if (empty($aggregate_func))
						$fields = array_diff($this->getAttr(), $remove);
				}
			} 		


			if ($this->paginator){
				$order  = (!empty($order) && !$this->randomize) ? array_merge($this->order, $order) : $this->order;
				$limit  = $limit  ?? $this->limit  ?? null;
				$offset = $offset ?? $this->offset ?? 0; 
				
				if($limit>0 || $order!=NULL){
					try {
						$paginator = new Paginator();
						$paginator->setLimit($limit);
						$paginator->setOffset($offset);
						$paginator->setOrders($order);
						$paginator->setAttr($this->attributes);
						$paginator->compile();

						$this->pag_vals = $paginator->getBinding();
					}catch (SqlException $e){
						throw new SqlException("Pagination error: {$e->getMessage()}");
					}
				}else{
					$paginator = null;
				}
							
			} else {
				$paginator = null;	
			}	

		}			


		//Debug::dd($fields, 'FIELDS:');

		if (!$existance){
			if ($aggregate_func != null){
				if (strtoupper($aggregate_func) == 'COUNT'){					
					if ($aggregate_field == null)
						$aggregate_field = '*';

					//Debug::dd($fields, 'FIELDS:');
					//Debug::dd([$aggregate_field], 'AGGREGATE FIELD:');

					if (!empty($fields))
						$_f = implode(", ", $fields). ',';
					else
						$_f = '';

					if ($this->distinct)
						$q  = "SELECT $_f $aggregate_func(DISTINCT $aggregate_field)" . (!empty($aggregate_field_alias) ? " as $aggregate_field_alias" : '');
					else
						$q  = "SELECT $_f $aggregate_func($aggregate_field)" . (!empty($aggregate_field_alias) ? " as $aggregate_field_alias" : '');
				}else{
					if (!empty($fields))
						$_f = implode(", ", $fields). ',';
					else
						$_f = '';

					$q  = "SELECT $_f $aggregate_func($aggregate_field)" . (!empty($aggregate_field_alias) ? " as $aggregate_field_alias" : '');
				}
					
			}else{
				$q = 'SELECT ';

				//Debug::dd($fields);
				
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


		//Debug::dd($vars, 'VARS:');
		//Debug::dd($values, 'VALS:');

		// Validación
		if (!empty($this->validator)){
			$validado = $this->validator->validate($this->getRules(), array_combine($vars, $values));
			if ($validado !== true){
				throw new InvalidValidationException(json_encode($validado));
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

			$where = trim($where);

			if (!empty($where)){
				$where = rtrim($where);
				$where = "($where) AND ". $implode. ' ';
			}else{
				$where = "$implode ";
			}
		}			

		$where = trim($where);
		
		if ($this->inSchema(['deleted_at'])){
			if (!$this->show_deleted){
				if (empty($where))
					$where = "deleted_at IS NULL";
				else
					$where =  ($where[0]=='(' && $where[strlen($where)-1] ==')' ? $where :   "($where)" ) . " AND deleted_at IS NULL";

			}
		}
		
		if (!empty($where)){
			$q  .= 'WHERE '.ltrim($where);
		}
		
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
				$having = rtrim($having);
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


		$q = rtrim($q);
		$q = Strings::removeRTrim('AND', $q);
		$q = Strings::removeRTrim('OR',  $q);


		// PAGINATION
		if (!$existance && $paginator!==null){
			$q .= $paginator->getQuery();
		}

		$q  = rtrim($q);
		
		if ($existance)
			$q .= ')';

		
		/*
		$q = rtrim($q);
		$q = String::removeRTrim('AND', $q);
		$q = String::removeRTrim('OR',  $q);
		*/

		//Debug::dd($q, 'Query:');
		//Debug::dd($vars, 'Vars:');
		//Debug::dd($values, 'Vals:');
		//var_dump($q);
		//exit;
		//var_export($vars);
		//var_export($values);
		
		$this->last_bindings = $this->getBindings();
		$this->last_pre_compiled_query = $q;
		return $q;	
	}

	function getBindings(){
		$pag = !empty($this->pag_vals) ? [ $this->pag_vals[0][1], $this->pag_vals[1][1] ] : [];

		$values = array_merge(	
								$this->select_raw_vals,
								$this->from_raw_vals,
								$this->where_raw_vals,
								$this->w_vals,
								$this->having_raw_vals,
								$this->h_vals,
								$pag
							);
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
			}elseif(isset($this->w_vars[$ix]) && isset($this->schema['attr_types'][$this->w_vars[$ix]])){
				$const = $this->schema['attr_types'][$this->w_vars[$ix]];
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
			}elseif(isset($this->h_vars[$ix]) && isset($this->schema['attr_types'][$this->h_vars[$ix]])){
				$const = $this->schema['attr_types'][$this->h_vars[$ix]];
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

	function getLastPrecompiledQuery(){
		return $this->last_pre_compiled_query;
	}

	private function _dd($pre_compiled_sql, $bindings){		
		foreach($bindings as $ix => $val){			
			if(is_null($val)){
				$bindings[$ix] = 'NULL';
			}elseif(isset($vars[$ix]) && isset($this->schema['attr_types'][$vars[$ix]])){
				$const = $this->schema['attr_types'][$vars[$ix]];
				if ($const == 'STR')
					$bindings[$ix] = "'$val'";
			}elseif(is_int($val)){
				// pass
			}
			elseif(is_bool($val)){
				// pass
			} elseif(is_string($val))
				$bindings[$ix] = "'$val'";	
		}
				
		$sql = Arrays::str_replace_array('?', $bindings, $pre_compiled_sql);
		return trim(preg_replace('!\s+!', ' ', $sql));
	}

	// Debug query
	function dd(){		
		return $this->_dd($this->toSql(), $this->getBindings());
	}

	// Debug last query
	function dd2(){		
		return $this->_dd($this->last_pre_compiled_query, $this->last_bindings);
	}

	// Debug last query
	function getLog(){
		return $this->dd2();
	}

	function get(array $fields = null, array $order = null, int $limit = NULL, int $offset = null, $pristine = false){
		$this->onReading();

		$q = $this->toSql($fields, $order, $limit, $offset);
		$st = $this->bind($q);


		//Debug::dd($q, 'Q'); ////////
		//var_dump($this->from());
		//exit;

		$count = null;
		if ($this->exec && $st->execute()){
			$output = $st->fetchAll($this->getFetchMode());
			
			$count  = $st->rowCount();
			if (empty($output)) {
				$ret = [];
			}else {
				$ret = $pristine ? $output : $this->applyTransformer($this->applyOutputMutators($output));
			}

			$this->onRead($count);
		}else
			$ret = false;
				
		return $ret;	
	}

	function first(array $fields = null, $pristine = false){
		$this->onReading();

		$q = $this->toSql($fields, NULL);
		$st = $this->bind($q);

		$count = null;
		if ($this->exec && $st->execute()){
			$output = $st->fetch($this->getFetchMode());
			$count = $st->rowCount();

			if (empty($output)) {
				$ret = [];
			}else {
				$ret = $pristine ? $output : $this->applyTransformer($this->applyOutputMutators($output));
			}

			$this->onRead($count);
		}else
			$ret = false;
				
		return $ret;
	}
	
	function value($field){
		$this->onReading();

		$q = $this->toSql([$field], NULL, 1);
		$st = $this->bind($q);

		$count = null;
		if ($this->exec && $st->execute()) {
			$ret = $st->fetch(\PDO::FETCH_NUM)[0];
			
			$count = $st->rowCount();
			$this->onRead($count);
		} else
			$ret = false;
			
		return $ret;	
	}

	function exists(){
		$q = $this->toSql(null, null, null, null, true);
		$st = $this->bind($q);

		if ($this->exec && $st->execute()){
			return (bool) $st->fetch(\PDO::FETCH_NUM)[0];
		}else
			return false;	
	}

	function pluck(string $field){
		$this->setFetchMode('COLUMN');
		$this->fields = [$field];

		$q = $this->toSql();
		$st = $this->bind($q);
	
		if ($this->exec && $st->execute()) {
			$res = $this->applyTransformer($this->applyOutputMutators($st->fetchAll($this->getFetchMode())));
			
			//var_dump($res);
			//exit;	
			
			return $res;
		} else
			return false;	
	}

	function avg($field, $alias = NULL){
		$q = $this->toSql(null, null, null, null, false, 'AVG', $field, $alias);
		$st = $this->bind($q);

		if (empty($this->group)){
			if ($this->exec && $st->execute())
				return $st->fetch($this->getFetchMode());
			else
				return false;	
		}else{
			if ($this->exec && $st->execute())
				return $st->fetchAll($this->getFetchMode());
			else
				return false;
		}	
	}

	function sum($field, $alias = NULL){
		$q = $this->toSql(null, null, null, null, false, 'SUM', $field, $alias);
		$st = $this->bind($q);

		if (empty($this->group)){
			if ($this->exec && $st->execute())
				return $st->fetch($this->getFetchMode());
			else
				return false;	
		}else{
			if ($this->exec && $st->execute())
				return $st->fetchAll($this->getFetchMode());
			else
				return false;
		}	
	}

	function min($field, $alias = NULL){
		$q = $this->toSql(null, null, null, null, false, 'MIN', $field, $alias);
		$st = $this->bind($q);

		if (empty($this->group)){
			if ($this->exec && $st->execute())
				return $st->fetch($this->getFetchMode());
			else
				return false;	
		}else{
			if ($this->exec && $st->execute())
				return $st->fetchAll($this->getFetchMode());
			else
				return false;
		}	
	}

	function max($field, $alias = NULL){
		$q = $this->toSql(null, null, null, null, false, 'MAX', $field, $alias);
		$st = $this->bind($q);

		if (empty($this->group)){
			if ($this->exec && $st->execute())
				return $st->fetch($this->getFetchMode());
			else
				return false;	
		}else{
			if ($this->exec && $st->execute())
				return $st->fetchAll($this->getFetchMode());
			else
				return false;
		}	
	}

	function count($field = NULL, $alias = NULL){
		$q = $this->toSql(null, null, null, null, false, 'COUNT', $field, $alias);
		$st = $this->bind($q);

		//Debug::dd($q, 'Q');
		//Debug::dd($this->table_raw_q, 'RAW Q');
		//exit;

		if (empty($this->group)){
			if ($this->exec && $st->execute()){
				return $st->fetch($this->getFetchMode('COLUMN'));
			}else
				return false;	
		}else{
			if ($this->exec && $st->execute()){
				return $st->fetchAll($this->getFetchMode('COLUMN'));
			}else
				return false;
		}	
	}


	function _where($conditions, $group_op = 'AND', $conjunction)
	{
		if (empty($conditions)){
			return;
		}

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
					if ($cond[0] == null)
						throw new SqlException("Field can not be NULL");

					if(is_array($cond[1]) && (empty($cond[2]) || in_array($cond[2], ['IN', 'NOT IN']) ))
					{						
						if($this->schema['attr_types'][$cond[0]] == 'STR')	
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

		$this->w_vars = array_merge($this->w_vars, $vars); //

		////////////////////////////////////////////
		// group
		$ws_str = implode(" $conjunction ", $_where);
		
		if (count($conditions)>1 && !empty($ws_str))
			$ws_str = "($ws_str)";
		
		$this->where_group_op[] = $group_op;	

		$this->where[] = ' ' .$ws_str;
		////////////////////////////////////////////

		//Debug::dd($this->where);
		//exit;
		//Debug::dd($this->w_vars, 'WHERE VARS');	
		//Debug::dd($this->w_vals, 'WHERE VALS');	

		return;
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
		return $this->where([$this->schema['id_name'] => $id])->get();
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
	
		//Debug::dd($conditions, 'COND:');

		$_having = [];
		foreach ((array) $conditions as $cond) {		
		
			if (Arrays::is_assoc($cond)){
				//Debug::dd($cond, 'COND PRE-CAMBIO');
				$cond[0] = Arrays::array_key_first($cond);
				$cond[1] = $cond[$cond[0]];

				//Debug::dd([$cond[0], $cond[1]], 'COND POST-CAMBIO');
			}
			
			$op = $cond[2] ?? '=';	
			
			$_having[] = "$cond[0] $op ?";
			$this->h_vars[] = $cond[0];
			$this->h_vals[] = $cond[1];
		}

		////////////////////////////////////////////
		// group
		$ws_str = implode(" $conjunction ", $_having);
		
		if (count($conditions)>1 && !empty($ws_str))
			$ws_str = "($ws_str)";
		
		$this->having_group_op[] = $group_op;	

		$this->having[] = ' ' .$ws_str;
		////////////////////////////////////////////

		//Debug::dd($this->having, 'HAVING:');
		//Debug::dd($this->h_vars, 'VARS');
		//Debug::dd($this->h_vals, 'VALUES');

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
	function update(array $data, $set_updated_at = true)
	{
		if ($this->conn == null)
			throw new SqlException('No conection');
			
		if (empty($data)){
			throw new SqlException('There is no data to update');
		}

		if (!Arrays::is_assoc($data)){
			throw new SqlException('Array of data should be associative');
		}
			
		if (isset($data['created_by']))
			unset($data['created_by']);
	

		$data = $this->applyInputMutator($data, 'UPDATE');
		$vars   = array_keys($data);
		$values = array_values($data);

		
		//var_dump($data); ///
		//exit;

		if(!empty($this->fillable) && is_array($this->fillable)){
			foreach($vars as $var){
				if (!in_array($var,$this->fillable))
					throw new SqlException("update: $var is not fillable");
			}
		}

		// Validación
		if (!empty($this->validator)){
			$validado = $this->validator->validate($this->getRules(), $data);
			if ($validado !== true){
				throw new InvalidValidationException(json_encode($validado));
			} 
		}
	
		$this->data = $data;
		$this->onUpdating($data);
		
		$set = '';
		foreach($vars as $ix => $var){
			$set .= " $var = ?, ";
		}
		$set =trim(substr($set, 0, strlen($set)-2));

		if ($set_updated_at && $this->inSchema(['updated_at'])){
			$d = new \DateTime(NULL, new \DateTimeZone($this->config['DateTimeZone']));
			$at = $d->format('Y-m-d G:i:s');

			$set .= ", updated_at = '$at'";
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
		//exit;

		foreach($values as $ix => $val){			
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(isset($vars[$ix]) && isset($this->schema['attr_types'][$vars[$ix]])){
				$const = $this->schema['attr_types'][$vars[$ix]];
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

		$this->last_bindings = $values;
		$this->last_pre_compiled_query = $q;
	 
		if (!$this->exec){
			return 0;
		}

		if($st->execute()) {
			$count = $st->rowCount();
			$this->onUpdated($data, $count);
		} else 
			$count = false;
			
		return $count;
	}

	/**
	 * delete
	 *
	 * @param  array $data (aditional fields in case of soft-delete)
	 * @return mixed
	 */
	function delete(array $data = [])
	{
		if ($this->conn == null)
			throw new SqlException('No conection');

		// Validación
		if (!empty($this->validator)){
			$validado = $this->validator->validate($this->getRules(), array_combine($this->w_vars, $this->w_vals));
			if ($validado !== true){
				throw new InvalidValidationException(json_encode($validado));
			} 
		}

		$this->onDeleting();

		if ($this->soft_delete){
			$d = new \DateTime(NULL, new \DateTimeZone($this->config['DateTimeZone']));
			$at = $d->format('Y-m-d G:i:s');

			$to_fill = [];
			if (!empty($data)){
				$to_fill = array_keys($data);
			}
			$to_fill[] = 'deleted_at';

			$data =  array_merge($data, ['deleted_at' => $at]);

			$this->fill($to_fill);
			return $this->update($data, false);
		}

		$where = implode(' AND ', $this->where);

		$q = "DELETE FROM ". $this->from() . " WHERE " . $where;
		
		$st = $this->conn->prepare($q);
		
		$vars = $this->w_vars;		
		foreach($this->w_vals as $ix => $val){			
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(isset($vars[$ix]) && isset($this->schema['attr_types'][$vars[$ix]])){
				$const = $this->schema['attr_types'][$vars[$ix]];
				$type = constant("PDO::PARAM_{$const}");
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			elseif(is_string($val))
				$type = \PDO::PARAM_STR;	

			$st->bindValue($ix+1, $val, $type);
		}
	 
		$this->last_bindings = $this->getBindings();
		$this->last_pre_compiled_query = $q;

		if($this->exec && $st->execute()) {
			$count = $st->rowCount();
			$this->onDeleted($count);
		} else 
			$count = false;	
		
		return $count;	
	}

	/*
		@return mixed false | integer 
	*/
	function create(array $data)
	{
		if ($this->conn == null)
			throw new SqlException('No connection');

		if (!Arrays::is_assoc($data))
			throw new \InvalidArgumentException('Array of data should be associative');
	
		$this->data = $data;	

		//Debug::dd($data, 'DATA');
		//exit;
		
		$data = $this->applyInputMutator($data, 'CREATE');
		$vars = array_keys($data);
		$vals = array_values($data);

		if(!empty($this->fillable) && is_array($this->fillable)){
			foreach($vars as $var){
				if (!in_array($var,$this->fillable))
					throw new \InvalidArgumentException("$var is no fillable");
			}
		}

		// Validación
		if (!empty($this->validator)){
			$validado = $this->validator->validate($this->getRules(), $data);
			if ($validado !== true){
				throw new InvalidValidationException(json_encode($validado));
			} 
		}

		// Event hook
		$this->onCreating($data);

		$str_vars = implode(', ',$vars);

		$symbols = array_map(function($v){ return '?';}, $vars);
		$str_vals = implode(', ',$symbols);

		if ($this->inSchema(['created_at'])){
			$d = new \DateTime(NULL, new \DateTimeZone($this->config['DateTimeZone']));
			$at = $d->format('Y-m-d G:i:s');

			$str_vars .= ', created_at';
			$str_vals .= ", '$at'";
		}

		$q = "INSERT INTO " . $this->from() . " ($str_vars) VALUES ($str_vals)";
		$st = $this->conn->prepare($q);

		foreach($vals as $ix => $val){			
			if(is_null($val)){
				$type = \PDO::PARAM_NULL;
			}elseif(isset($vars[$ix]) && $this->schema != NULL && isset($this->schema['attr_types'][$vars[$ix]])){
				$const = $this->schema['attr_types'][$vars[$ix]];
				$type = constant("PDO::PARAM_{$const}");
			}elseif(is_int($val))
				$type = \PDO::PARAM_INT;
			elseif(is_bool($val))
				$type = \PDO::PARAM_BOOL;
			elseif(is_string($val))
				$type = \PDO::PARAM_STR;	

			//Debug::dd($type, "TYPE for $val");	

			$st->bindValue($ix+1, $val, $type);
		}

		$this->last_bindings = $vals;
		$this->last_pre_compiled_query = $q;

		if (!$this->exec){
			return NULL;
		}	

		$result = $st->execute();

		if ($result){
			// sin schema no hay forma de saber la PRI Key. Intento con 'id' 
			$id_name = ($this->schema != NULL) ? $this->schema['id_name'] : 'id';		

			if (isset($data[$id_name])){
				$this->last_inserted_id =	$data[$id_name];
			} else {
				$this->last_inserted_id = $this->conn->lastInsertId();
			}

			$this->onCreated($data, $this->last_inserted_id);
		}else {
			$this->last_inserted_id = false;	
		}

		return $this->last_inserted_id;	
		
	}
	
	/*
		 to be called inside onUpdating() event hook

		 el problema es que necesito ejecutar el mismo WHERE que el UPDATE en un GET para seleccionar el mismo registro y tener contra que comparar.	

		 https://stackoverflow.com/questions/45702409/laravel-check-if-updateorcreate-performed-update/49350664#49350664
		 https://stackoverflow.com/questions/48793257/laravel-check-with-observer-if-column-was-changed-on-update/48793801
	*/	 

	function isDirty($fields = null) 
	{
		if ($fields == null){
			$fields = $this->attributes;
		}

		if (!is_array($fields)){
			$fields = [$fields];
		}

		// to be updated
		$keys = array_keys($this->data);

		if (!$this->inSchema($fields)){
			throw new \Exception("A field was not found in table {$this->table_name}");
		}
		
		$old_vals = $this->first($fields);
		foreach ($fields as $field){	
			if (!in_array($field, $keys)){
				continue;
			}

			$new_val = $this->data[$field];
			
			if ($new_val != $old_vals[$field]){
				return true;
			}	
		}

		return false;
	}


	/*
		Even hooks -podrían estar definidos en clase abstracta o interfaz-
	*/

	protected function boot() { }

	protected function onReading() { }
	protected function onRead(?int $count) { }
	
	protected function onDeleting() { }
	protected function onDeleted(?int $count) { }

	protected function onCreating(Array &$data) {	}
	protected function onCreated(Array &$data, $last_inserted_id) { }

	protected function onUpdating(Array &$data) { }
	protected function onUpdated(Array &$data, ?int $count) { }


	function getSchema(){
		return $this->schema;
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
			throw new \InvalidArgumentException("Attributes not found!");

		foreach ($props as $prop)
			if (!in_array($prop, $this->attributes)){
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
		$diff =  array_diff($this->attributes, array_keys($fields));
		return array_diff($diff, $this->schema['nullable']);
	}
	
	/**
	 * Get attr_types 
	 */ 
	function getAttr()
	{
		return $this->attributes;
	}

	function getIdName(){
		return $this->schema['id_name'];
	}

	function getNotHidden(){
		return array_diff($this->attributes, $this->hidden);
	}

	function isNullable(string $field){
		return in_array($field, $this->schema['nullable']);
	}

	function isFillable(string $field){
		return in_array($field, $this->fillable);
	}

	function getFillables(){
		return $this->fillable;
	}

	function setNullables(Array $arr){
		$this->schema['nullable'] = $arr;
	}

	function addNullables(Array $arr){
		$this->schema['nullable'] = array_merge($this->schema['nullable'], $arr);
	}

	function removeNullables(Array $arr){
		$this->schema['nullable'] = array_diff($this->schema['nullable'], $arr);
	}

	function getNullables(){
		return $this->schema['nullable'];
	}

	function getNotNullables(){
		return array_diff($this->attributes, $this->schema['nullable']);
	}

	function getRules(){
		return $this->schema['rules'] ?? NULL;
	}

	/**
	 * Set the value of conn
	 *
	 * @return  self
	 */ 
	function connect()
	{
		$this->conn = DB::getConnection();
		return $this;
	}

	function setConn($conn)
	{
		$this->conn = $conn;
		return $this;
	}

}