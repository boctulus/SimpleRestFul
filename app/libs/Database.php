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
}
