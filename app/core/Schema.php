<?php

namespace simplerest\core;

use simplerest\libs\DB;
use simplerest\libs\Strings;
use simplerest\libs\Debug;

/*
	Migrations
*/

class Schema 
{
	protected $tables;
	protected $tb_name;

	protected $engine;
	protected $charset = 'utf8';
	protected $collation;
	
	protected $fields  = [];
	protected $current_field;
	protected $indices = []; // 'PRIMARY', 'UNIQUE', 'INDEX', 'FULLTEXT', 'SPATIAL'
	protected $fks = [];
	
	protected $prev_schema;

	function __construct($tb_name){
		$this->tables = DB::select('SHOW TABLES', 'COLUMN');
		$this->tb_name = $tb_name;
		$this->fromDB();
	}	

	function setEngine(string $val){
		$this->engine = $val;
		return $this;
	}

	function setCharset(string $val){
		$this->chartset = $val;
		return $this;
	}

	function setCollation(string $val){
		$this->collation = $val;
		return $this;
	}

	function column(string $name){
		$this->current_field = $name;
		return $this;
	}

	function field(string $name){
		$this->column($name);
		return $this;
	}
	
	// type
	
	function int(string $name, int $len = NULL){
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'INT';
		
		if ($len != NULL)
			$this->fields[$this->current_field]['len'] = $len;
		
		return $this;		
	}	
	
	function integer(string $name, int $len = NULL){
		$this->int($name, $len);
		return $this;		
	}	
	
	function serial(string $name, int $len = NULL){		
		$this->current_field = $name;
		//$this->bigint($name, $len)->unsigned()->auto()->unique();
		$this->fields[$this->current_field]['type'] = 'SERIAL';
		return $this;		
	}	
	
	function bigint(string $name){
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'BIGINT';
		return $this;		
	}	
	
	function mediumint(string $name){
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'MEDIUMINT';
		return $this;		
	}	
	
	function smallint(string $name){
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'SMALLINT';
		return $this;		
	}	
	
	function tinyint(string $name){
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'TINYINT';
		return $this;		
	}	
	
	function boolean(string $name){
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'BOOLEAN';
		return $this;		
	}	
	
	function bool(string $name){
		$this->boolean($name);
		return $this;		
	}
	
	function bit(string $name, int $len){
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'BIT';
		$this->fields[$this->current_field]['len'] = $len;		
		return $this;		
	}
	
	function decimal(string $name, int $len = 15, int $len_dec = 4){
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'DECIMAL';
		$this->fields[$this->current_field]['len'] = [$len, $len_dec];		
		return $this;		
	}	
	
	function float(string $name){
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'FLOAT';
		return $this;		
	}	
	
	function double(string $name){
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'DOUBLE';
		return $this;		
	}	
	
	function real(string $name){
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'REAL';
		return $this;		
	}	
	
	function char(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'CHAR';
		return $this;		
	}	
	
	function varchar(string $name, int $len = 60){
		if ($len > 65535)
			throw new \InvalidArgumentException("Max length is 65535");
		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'VARCHAR';
		$this->fields[$this->current_field]['len'] = $len;
		return $this;		
	}	
	
	function text(string $name, int $len = NULL){
		if ($len > 65535)
			throw new \InvalidArgumentException("Max length is 65535");
		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'TEXT';
		
		if ($len != NULL)
			$this->fields[$this->current_field]['len'] = $len;
		
		return $this;		
	}	
	
	function tinytext(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'TINYTEXT';
		return $this;		
	}
	
	function mediumtext(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'MEDIUMTEXT';
		return $this;		
	}
	
	function longtext(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'LONGTEXT';
		return $this;		
	}
	
	function varbinary(string $name, int $len = 60){
		if ($len > 65535)
			throw new \InvalidArgumentException("Max length is 65535");
		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'VARBINARY';
		$this->fields[$this->current_field]['len'] = $len;
		return $this;		
	}
	
	function blob(string $name){
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'BLOB';
		return $this;		
	}	
	
	function binary(string $name, int $len){
		if ($len > 255)
			throw new \InvalidArgumentException("Max length is 65535");
		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'BINARY';
		$this->fields[$this->current_field]['len'] = $len;
		return $this;		
	}
	
	function tinyblob(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'TINYBLOB';
		return $this;		
	}
	
	function mediumblob(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'MEDIUMBLOB';
		return $this;		
	}
	
	function longblob(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'LONGBLOB';
		return $this;		
	}
	
	function json(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'JSON';
		return $this;		
	}
	
	function set(string $name, array $values){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'SET';
		$this->fields[$this->current_field]['array'] = $values;
		return $this;		
	}
	
	function enum(string $name, array $values){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'ENUM';
		$this->fields[$this->current_field]['array'] = $values;
		return $this;		
	}
	
	function time(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'TIME';
		return $this;		
	}
	
	function year(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'YEAR';
		return $this;		
	}
	
	function date(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'DATE';
		return $this;		
	}
	
	function datetime(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'DATETIME';
		return $this;		
	}
	
	function timestamp(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'TIMESTAMP';
		return $this;		
	}
	
	function softDeletes(){		
		$this->current_field = 'deleted_at';
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'DATETIME';
		return $this;		
	}
	
	function datetimes(){		
		$this->current_field = 'created_at';
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'DATETIME';
		$this->current_field = 'updated_at';
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'DATETIME';
		return $this;		
	}
	
	function point(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'POINT';
		return $this;		
	}
	
	function multipoint(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'MULTIPOINT';
		return $this;		
	}
	
	function linestring(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'LINESTRING';
		return $this;		
	}
	
	function polygon(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'POLYGON';
		return $this;		
	}
	
	function multipolygon(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'MULTIPOLYGON';
		return $this;		
	}
	
	function geometry(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'GEOMETRY';
		return $this;		
	}
	
	function geometrycollection(string $name){		
		$this->current_field = $name;
		$this->fields[$this->current_field] = [];
		$this->fields[$this->current_field]['type'] = 'GEOMETRYCOLLECTION';
		return $this;		
	}	
	
	// collation && charset 
	
	function collation(string $val){
		$this->fields[$this->current_field]['collation'] = $val;
		return $this;		
	}

	// alias
	function collate(string $val){
		$this->collation($val);
		return $this;		
	}
	
	function charset(string $val){
		$this->fields[$this->current_field]['charset'] = $val;
		return $this;		
	}
	
	// modifiers
	
	function auto(){
		$this->fields[$this->current_field]['auto'] =  true;
		return $this;
	}

	function nullable(bool $value =  true){
		$this->fields[$this->current_field]['nullable'] =  $value ? 'NULL' : 'NOT NULL';
		return $this;
	}
	
	function comment($string){
		$this->fields[$this->current_field]['comment'] =  $string;
		return $this;
	}
	
	function default($val = NULL){
		if ($val == NULL)
			$val = 'NULL';
		
		$this->fields[$this->current_field]['default'] =  $val;
		return $this;
	}
	
	function currentTimestamp(){
		$this->default('current_timestamp()');	
		return $this;
	}
	
	protected function setAttr($attr){
		if (!in_array($attr, ['UNSIGNED', 'UNSIGNED ZEROFILL', 'BINARY'])){
			throw new \Exception("Attribute '$attr' is not valid.");
		}

		$this->fields[$this->current_field]['attr'] = $attr;
	}
	
	function unsigned(){
		$this->setAttr('UNSIGNED');
		return $this;
	}
	
	function zeroFill(){
		$this->setAttr('UNSIGNED ZEROFILL');
		return $this;
	}
	
	function binaryAttr(){
		$this->setAttr('BINARY');
		return $this;
	}
	
	// ALTER TABLE `aaa` ADD `ahora` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `fecha`;
	function onUpdateCurrent(){
		$this->setAttr('current_timestamp()');	
		return $this;
	}
	
	function after(string $field){
		$this->fields[$this->current_field]['after'] =  $field;
		return $this;
	}
	
	// ALTER TABLE `aaa` ADD `inicio` INT NOT NULL FIRST;
	function first(){
		if (isset($this->fields[$this->current_field]['after']))
			unset($this->fields[$this->current_field]['after']);
		
		foreach ($this->fields as $k => $field){
			if (isset($this->fields[$k]['first']))
				unset($this->fields[$k]['first']);
		}	
		
		$this->fields[$this->current_field]['first'] =  true;
		return $this;
	}
	
	// FKs
	
	function foreign(string $name){
		$this->current_field = $name;
		$this->fks[$this->current_field] = [];
		return $this;
	}
	
	function references(string $field){
		$this->fks[$this->current_field]['references'] = $field;
		return $this;
	}
	
	function on(string $table){
		$this->fks[$this->current_field]['on'] = $table;
		return $this;
	}
	
	function onDelete(string $action){
		$this->fks[$this->current_field]['on_delete'] = $action;
		return $this;
	}
	
	function onUpdate(string $action){
		$this->fks[$this->current_field]['on_update'] = $action;
		return $this;
	}
	
	// INDICES >>>
	
	protected function setIndex(string $type){
		if (!in_array($type, ['PRIMARY', 'UNIQUE', 'INDEX', 'FULLTEXT', 'SPATIAL']))
			throw new \InvalidArgumentException("Invalid index $type");
		
		$this->indices[$this->current_field] = $type;
	}
	
	function primary(){
		$this->setIndex('PRIMARY');
		return $this;
	}
	
	function pri(){
		$this->primary();
		return $this;
	}
	
	function unique(){
		$this->setIndex('UNIQUE');
		return $this;
	}
	
	function index(){
		$this->setIndex('INDEX');
		return $this;
	}
	
	function fulltext(){
		$this->setIndex('FULLTEXT');
		return $this;
	}
	
	function spatial(){
		$this->setIndex('SPATIAL');
		return $this;
	}
	
	///////////////////////////////
	
	/*
		`nombre_campo` tipo[(longitud)] [(array_set_enum)] [charset] [collate] [attributos] NULL|NOT_NULL [default] [AUTOINCREMENT]
	*/
	function getDefinition($field){
		$cmd = '';		
		if (in_array($field['type'], ['SET', 'ENUM'])){
			$values = implode(',', array_map(function($e){ return "'$e'"; }, $field['array']));	
			$cmd .= "($values) ";
		}else{
			if (isset($field['len'])){
				$len = implode(',', (array) $field['len']);	
				$cmd .= "($len) ";
			}else
				$cmd .= " ";	
		}
		
		if (isset($field['attr'])){
			$cmd .= "{$field['attr']} ";
		}
		
		if (isset($field['charset'])){
			$cmd .= "CHARACTER SET {$field['charset']} ";
		}
		
		if (isset($field['collation'])){
			$cmd .= "COLLATE {$field['collation']} ";
		}
			
		if (isset($field['nullable'])){
			$cmd .= "{$field['nullable']} ";
		}else
			$cmd .= "NOT NULL ";

		if (isset($field['default'])){
			$cmd .= "DEFAULT {$field['default']} ";
		}

		if (isset($field['auto'])){
			$cmd .= "AUTO_INCREMENT PRIMARY KEY";
		}
		
		return trim($cmd);
	}

	private function showTable(){
		$conn = DB::getConnection();
		
		$stmt = $conn->query("SHOW CREATE TABLE `{$this->tb_name}`", \PDO::FETCH_ASSOC);
		$res  = $stmt->fetch();
		
		return $res;
	}
		
	function createTable(){
		if (empty($this->fields))
			throw new \Exception("No fields!");
		
		$commands = [
			'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";',
			'SET AUTOCOMMIT = 0;',
			'START TRANSACTION;',
			'SET time_zone = "+00:00";'
		];
	
		$cmd = '';
		foreach ($this->fields as $name => $field){
			$cmd .= "`$name` {$field['type']} ";			
			$cmd .= $this->getDefinition($field);			
			$cmd .= ",\n";
		}
		
		$cmd = substr($cmd,0,strlen($cmd)-2);
		
		$cmd = "CREATE TABLE `{$this->tb_name}` (\n$cmd\n) ENGINE={$this->engine} DEFAULT CHARSET={$this->charset};";
		
		$commands[] = $cmd;
		
		// Indices
		
		if (count($this->indices) >0)
		{			
			$cmd = '';		
			foreach ($this->indices as $nombre => $tipo){
				$cmd .= 'ADD ';
				
				switch ($tipo){
					case 'INDEX':
						$cmd .= "INDEX (`$nombre`),\n";
					break;
					case 'PRIMARY':
						//$cmd .= "PRIMARY KEY (`$nombre`),\n";
					break;
					case 'UNIQUE':
						$cmd .= "UNIQUE KEY `$nombre` (`$nombre`),\n";
					break;
					case 'SPATIAL':
						$cmd .= "SPATIAL KEY `$nombre` (`$nombre`),\n";
					break;
					
					default:
						throw new \Exception("Invalid index type");
				}				
			}
			
			$cmd = substr($cmd,0,-2);
			$cmd = "ALTER TABLE `{$this->tb_name}` \n$cmd;";
			
			$commands[] = $cmd;
		}		
		
		
		// FKs
		
		// FOREIGN KEY (`abono_id`) REFERENCES `abonos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		foreach ($this->fks as $name => $fk){
			$on_delete = !empty($fk['on_delete']) ? 'ON DELETE '.$fk['on_delete'] : '';
			$on_update = !empty($fk['on_update']) ? 'ON UPDATE '.$fk['on_update'] : '';
			
			//Debug::dd("FOREIGN KEY `($name)` REFERENCES `{$fk['on']}` (`{$fk['references']}`) {$fk['on']} $on_delete $on_update");
		}
		//exit; //		
				
		$commands[] = 'COMMIT;';		
		$sql = implode("\r\n",$commands)."\n";

		$conn = DB::getConnection();   
        $st = $conn->prepare($sql);
		$res = $st->execute(); 
	}

	// alias
	function create(){
		return $this->createTable();
	}
	
	function dropTable(){
		return "DROP TABLE `{$this->tb_name}`;\n";
	}

	// TRUNCATE `az`.`xxy`
	function truncateTable(string $tb){
		return "TRUNCATE `{$this->tb_name}`.`$tb`;\n";
	}

	// RENAME TABLE `az`.`xxx` TO `az`.`xxy`;
	function renameTable(string $ori, string $final){
		return "RENAME TABLE `{$this->tb_name}`.`$ori` TO `{$this->tb_name}`.`$final`;\n";
	}	


	function dropColumn(string $name){
		return "ALTER TABLE `{$this->tb_name}` DROP `$name`;\n";
	}

	// https://popsql.com/learn-sql/mysql/how-to-rename-a-column-in-mysql/
	function renameColumn(string $ori, string $final){
		return "ALTER TABLE `{$this->tb_name}` RENAME COLUMN `$ori` TO `$final`;\n";
	}
	

	function addIndex(string $column){
		return "ALTER TABLE `{$this->tb_name}` ADD INDEX(`$column`);\n";
	}

	function dropIndex(string $name){
		return "ALTER TABLE `{$this->tb_name}` DROP INDEX `$name`;\n";
	}

	// https://stackoverflow.com/questions/1463363/how-do-i-rename-an-index-in-mysql
	function renameIndex(string $ori, string $final){
		return "ALTER TABLE `{$this->tb_name}` RENAME INDEX `$ori` TO `$final`;\n";
	}


	function addPrimary(string $column){
		return "ALTER TABLE `{$this->tb_name}` ADD PRIMARY KEY(`$column`);\n";	
	}
		
	// implica primero remover el AUTOINCREMENT sobre el campo !
	// ej: ALTER TABLE `super_cool_table` CHANGE `id` `id` INT(11) NOT NULL;
	function dropPrimary(string $name){
		return "ALTER TABLE `$name` DROP PRIMARY KEY;\n";
	}


	function addUnique(string $column){
		return "ALTER TABLE `{$this->tb_name}` ADD UNIQUE(`$column`);\n";
	}
		
	function dropUnique(string $name){
		return "ALTER TABLE `$name` DROP UNIQUE;\n";
	}


	#
	# Hay operaciones muy complejas para realizarse con un sola función porque llevarían
	# demasiados parámetros:
	#
	# addColumn()
	# addAutoIncrement()
	# removeAutoIncrement()
	#
	#

	// From DB
	protected function fromDB(){
		if (!in_array($this->tb_name, $this->tables)){
			return;
		}

		$table_def = $this->showTable();

		if ($table_def == NULL){
			throw new \Exception("[ Fatal error ] Table definition could not be recovered");
		}

		$lines = explode("\n", $table_def["Create Table"]);
		$lines = array_map(function($l){ return trim($l); }, $lines);
		
		$fields = [];
		$cnt = count($lines)-1;
		for ($i=1; $i<$cnt; $i++){
			$str = $lines[$i];
			
			if ($lines[$i][0] == '`')
			{
				$field 		= NULL;
				$type  		= NULL;
				$array		= NULL;				
				$len   		= NULL;
				$charset  	= NULL;
				$collation 	= NULL;
				$nullable	= NULL;
				$default	= NULL;
				$auto 		= NULL;
				$check 		= NULL;
				
				$field      = Strings::slice($str, '/`([a-z_]+)`/i');
				$type       = Strings::slice($str, '/([a-z_]+)/i');

				if ($type == 'enum' || $type == 'set'){
					$array = Strings::slice($str, '/\((.*)\)/i');
				}else{
					$len = Strings::slice($str, '/\(([0-9,]+)\)/');					
				}
				
				$charset    = Strings::slice($str, '/CHARACTER SET ([a-z0-9_]+)/');
				$collation  = Strings::slice($str, '/COLLATE ([a-z0-9_]+)/');
				
				$default    = Strings::slice($str, '/DEFAULT ([a-zA-Z0-9_\(\)]+)/');
				//Debug::dd($default, 'DEFAULT');

				$nullable   = Strings::slice($str, '/(NOT NULL)/') == NULL;
				$auto       = Strings::slice($str, '/(AUTO_INCREMENT)/');
				//Debug::dd($nullable, "NULLABLE($field)");


				// [CONSTRAINT [symbol]] CHECK (expr) [[NOT] ENFORCED]	
				$check      = Strings::slice($str, '/CHECK (\(.*)/', function($s){
					$s = substr($s, 1);
					
					if ($s[strlen($s)-1] == '"')
						$s = substr($s, 0, -1);
					
					if ($s[strlen($s)-1] == ',')
						$s = substr($s, 0, -1);
					
					$s = substr($s, 0, -1);
					
					return $s;
				});
				
				//if (strlen($str)>1)
				//	throw new \Exception("Parsing error!");				
			
				/*
				Debug::dd($field, 'FIELD ***');
				Debug::dd($lines[$i], 'LINES');
				Debug::dd($type, 'TYPE');
				Debug::dd($array, 'ARRAY / SET');
				Debug::dd($len, 'LEN');
				Debug::dd($charset, 'CHARSET');
				Debug::dd($collation, 'COLLATION');
				Debug::dd($nullable, 'NULLBALE');
				Debug::dd($default, 'DEFAULT');
				Debug::dd($auto, 'AUTO');
				Debug::dd($check, 'CHECK');
				echo "-----------\n";
				*/					

				$this->prev_schema['fields'][$field]['type'] = strtoupper($type);
				$this->prev_schema['fields'][$field]['auto'] = $auto; 
				//$this->prev_schema['fields'][$field]['attr'] = ...
				$this->prev_schema['fields'][$field]['len'] = $len;
				$this->prev_schema['fields'][$field]['nullable'] = $nullable;
				$this->prev_schema['fields'][$field]['charset'] = $charset;
				$this->prev_schema['fields'][$field]['collation'] = $collation;
				$this->prev_schema['fields'][$field]['default'] = $default;
				// $this->prev_schema['fields'][$field]['after'] =  ...
				// $this->prev_schema['fields'][$field]['first'] = ...

			}else{
				// son índices de algún tipo
				//Debug::dd($str);
				
				$primary = Strings::slice($str, '/PRIMARY KEY \(`([a-zA-Z0-9_]+)`\)/');				
				$unique  = Strings::slice_all($str, '/UNIQUE KEY `([a-zA-Z0-9_]+)` \(`([a-zA-Z0-9_]+)`\)/');
				$index   = Strings::slice_all($str, '/KEY `([a-zA-Z0-9_]+)` \(`([a-zA-Z0-9_]+)`\)/');
				
				/*
				Debug::dd($primary);
				Debug::dd($unique);
				Debug::dd($index);
				echo "-----------\n";
				*/	
				
				if ($primary != NULL){
					$this->prev_schema['indices'][$field] = 'PRIMARY';
				} elseif ($unique != NULL){
					$this->prev_schema['indices'][$field] = 'UNIQUE';
				}if ($index != NULL){
					$this->prev_schema['indices'][$field] = 'INDEX';
				}
			}
		}
		
	}
	
	// ALTER TABLE `users` CHANGE `lastname` `lastname` VARCHAR(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
	
	// ALTER TABLE `users` CHANGE `id` `id` INT(20) UNSIGNED NOT NULL;
	function change()
	{	
		$changes = [];

		foreach ($this->fields as $name => $field){

			$this->fields[$name]  = array_merge($this->prev_schema['fields'][$name], $this->fields[$name]);
			$this->indices[$name] = $this->prev_schema['indices'][$name] ?? NULL;
			//$this->fields[$name]['charset'] = $this->prev_schema[$name]['charset'] ?? $this->;
			//$this->fields[$name]['collation'] = $this->prev_schema[$name]['collation'] ?? NULL;

			$field = $this->fields[$name];

			//Debug::dd($this->fields[$name]);
			//exit;

			$charset   = isset($field['charset']) ? "CHARACTER SET {$field['charset']}" : '';
			$collation = isset($field['collation']) ? "COLLATE {$field['collation']}" : '';
			
			$def = "{$this->fields[$name]['type']}";		
			if (in_array($field['type'], ['SET', 'ENUM'])){
				$values = implode(',', array_map(function($e){ return "'$e'"; }, $field['array']));	
				$def .= "($values) ";
			}else{
				if (isset($field['len'])){
					$len = implode(',', (array) $field['len']);	
					$def .= "($len) ";
				}else
					$def .= " ";	
			}
			
			if (isset($field['attr'])){
				$def .= "{$field['attr']} ";
			}
			
			if (in_array($field['type'], ['CHAR', 'VARCHAR', 'TEXT', 'TINYTEXT', 'MEDIUMTEXT', 'LONGTEXT', 'JSON', 'SET', 'ENUM'])){
				$def .= "$charset $collation ";	
			}		
			
			if ($field['nullable']){
				$def .= "NULL ";
			} else {		
				$def .= "NOT NULL ";
			}	
				
			if (isset($field['default'])){
				$def .= "DEFAULT {$field['default']} ";
			} 
			
			$def = trim(preg_replace('!\s+!', ' ', $def));
			
			$changes[] = "ALTER TABLE `{$this->tb_name}` CHANGE `$name` `$name` $def;";
		}


		$conn = DB::getConnection();   

		DB::transaction(function() use($changes, $conn) {
			foreach($changes as $change){     
				$st = $conn->prepare($change);
				$res = $st->execute();
			}
		});
	}	
	
	// reflexion
	
	function getSchema(){
		return [
			'engine'	=> $this->engine,
			'charset'	=> $this->charset,
			'collation'	=> $this->collation,
			'fields'	=> $this->fields,
			'indices'	=> $this->indices,
			'fks'		=> $this->fks
		];
	}
}

