<?php
declare(strict_types=1);

class Database {

    private static $conn;

    private function __construct() {
    }

    public static function getConnection($config) {
        $db_name = $config['db_name'];
		$host    = $config['host'] ?? 'localhost';
		$user    = $config['user'] ?? 'root';
		$pass    = $config['pass'] ?? '';
		
		try {
			self::$conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $user, $pass);
            self::$conn->exec("set names utf8");
			self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		} catch (PDOException $e) {
			die($e->getMessage());
		}	
		
		return self::$conn;
    }
}
