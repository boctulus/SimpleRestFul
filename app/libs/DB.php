<?php

namespace simplerest\libs;

use simplerest\core\Model;

class DB 
{
	protected static $connections = [];
	protected static $current_id_conn;
	protected static $model_instance;  
	protected static $raw_sql;

	protected function __construct() { }
	
	public static function setConnection($id){
		static::$current_id_conn = $id;
	}

    public static function getConnection(string $conn_id = null, $options = null) {
		$config = include CONFIG_PATH . 'config.php';
		
		$cc = count($config['db_connections']);
		
		if ($cc == 0){
			throw new \Exception('No database');
		}

		if ($conn_id != null){
			static::$current_id_conn = $conn_id;	
		} else {
			if (static::$current_id_conn == null){
				if ($cc == 1){
					static::$current_id_conn = array_keys($config['db_connections'])[0];
				} else {
					throw new \InvalidArgumentException('No database selected');
				}	
			}
		}

		if (isset(self::$connections[static::$current_id_conn]))
			return self::$connections[static::$current_id_conn];

		
		if (!isset($config['db_connections'][static::$current_id_conn])){
			throw new \InvalidArgumentException('Invalid database selected for '.static::$current_id_conn);
		}	
		
		$host    = $config['db_connections'][static::$current_id_conn]['host'] ?? 'localhost';
		$driver  = $config['db_connections'][static::$current_id_conn]['driver'];	
        $db_name = $config['db_connections'][static::$current_id_conn]['db_name'];
		$user    = $config['db_connections'][static::$current_id_conn]['user'] ?? 'root';
		$pass    = $config['db_connections'][static::$current_id_conn]['pass'] ?? '';
		
		try {
			if (empty($options)){
				$options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
				//$options[\PDO::ATTR_EMULATE_PREPARES] = false;  /* es posible desactivar ? */
			}
				
			self::$connections[static::$current_id_conn] = new \PDO("$driver:host=" . $host . ";dbname=" . $db_name, $user, $pass, $options);
            self::$connections[static::$current_id_conn]->exec("set names utf8");
		} catch (\PDOException $e) {
			throw new \PDOException($e->getMessage());
		}	
		
		return self::$connections[static::$current_id_conn];
	}
	
    static function closeConnection(string $conn_id = null) {
		if ($conn_id == null){
			unset(static::$connections[static::$current_id_conn]);
			static::$current_id_conn = NULL; // undefined
		} else {
			static::$connections[$conn_id] = null;
		}
		//echo 'Successfully disconnected from the database!';
	}

	static function closeAllConnections(){
		static::$connections = null;
	}
	
	public function __destruct()
    {
        static::closeAllConnections();        
    }
	
	public static function countConnections(){
		return count(static::$connections ?? []);
	}

	// Returns last executed query 
	public static function getLog(){
		if (static::$model_instance != NULL){
			return static::$model_instance->getLog();
		} else {
			return static::$raw_sql;
		}
	}
	
	public static function table($from, $alias = NULL, bool $connect = true) {
		// Usar un wrapper y chequear el tipo
		if (stripos($from, ' FROM ') === false){
			$tb_name = $from;
		
			$names = explode('_', $tb_name);
			$names = array_map(function($str){ return ucfirst($str); }, $names);
			$model_instance = implode('', $names).'Model';		

			$class = '\\simplerest\\models\\' . $model_instance;
			$obj = new $class($connect);

			if (!is_null($alias))
				$obj->setTableAlias($alias);

			static::$model_instance = $obj;			
			return $obj;	
		}

		static::$model_instance = (new Model($connect));

		$st = static::$model_instance->fromRaw($from);	
		return $st;
	}

	public static function beginTransaction(){
		/* 
		  Not much to it! Forcing PDO to throw exceptions instead errors is the key to being able to use the try / catch which simplifies the logic needed to perform the rollback.
		*/
		static::getConnection()->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		static::getConnection()->beginTransaction();
	}

	public static function commit(){
		static::getConnection()->commit();
	}

	public static function rollback(){
		static::getConnection()->rollback();
	}

	// https://github.com/laravel/framework/blob/4.1/src/Illuminate/DB/Connection.php#L417
	public static function transaction(\Closure $callback)
    {
		static::beginTransaction();

		try
		{
			$result = $callback();
			static::commit();
		}catch (\Exception $e){
			static::rollBack();
			throw $e;
		}

		return $result;
    }
		
	// falta hacer que acepte parámetros
	//
	// https://laravel.com/docs/5.0/database
	//
	public static function select(string $raw_sql, $fetch_mode = 'ASSOC'){
		static::$raw_sql = $raw_sql; 

		$conn = DB::getConnection();
        
        $st = $conn->prepare($raw_sql);
        $st->execute();

		$fetch_const = constant("\PDO::FETCH_{$fetch_mode}");
		$result = $st->fetchAll($fetch_const);
		return $result;
	}

	// faltan otras funciones raw para DELETE, UPDATE e INSERT

}
