<?php
declare(strict_types=1);

namespace simplerest\libs;

class Database {

    private static $conn;

    private function __construct() { }

    public static function getConnection($config) {
        $db_name = $config['db_name'];
		$host    = $config['host'] ?? 'localhost';
		$user    = $config['user'] ?? 'root';
		$pass    = $config['pass'] ?? '';
		
		try {
			$options = [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION ];
			self::$conn = new \PDO("mysql:host=" . $host . ";dbname=" . $db_name, $user, $pass, $options);
            self::$conn->exec("set names utf8");
		} catch (\PDOException $e) {
			throw new \PDOException($e->getMessage());
		}	
		
		return self::$conn;
    }
}
