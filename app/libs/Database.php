<?php
declare(strict_types=1);

namespace simplerest\libs;

class Database {

    private static $conn;

    private function __construct() { }

    public static function getConnection() {
		$config = include CONFIG_PATH . 'config.php';

        $db_name = $config['database']['db_name'];
		$host    = $config['database']['host'] ?? 'localhost';
		$user    = $config['database']['user'] ?? 'root';
		$pass    = $config['database']['pass'] ?? '';
		
		try {
			$options = [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION ];
			self::$conn = new \PDO("mysql:host=" . $host . ";dbname=" . $db_name, $user, $pass, $options);
            self::$conn->exec("set names utf8");
		} catch (\PDOException $e) {
			throw new \PDOException($e->getMessage());
		}	
		
		return self::$conn;
	}
	
	public static function model($name) {
		$class = '\\simplerest\\models\\' . $name . 'Model';
		return new $class(self::getConnection());
	}

	public static function table($from, $alias = NULL) {

		// Usar un wrapper y chequear el tipo
		if (stripos($from, 'FROM') === false){
			$tb_name = $from;
		
			$names = explode('_', $tb_name);
			$names = array_map(function($str){ return ucfirst($str); }, $names);
			$model = implode('', $names).'Model';		

			$class = '\\simplerest\\models\\' . $model;
			$obj = new $class(self::getConnection(), $alias);
			
			if (!is_null($alias))
				$obj->setTableAlias($alias);

			return $obj;	
		}

		return (new \simplerest\core\Model(self::getConnection()))
        ->fromRaw($from);
	}

		
}
