<?php

if (!defined('ROOT_PATH'))
	define('ROOT_PATH', dirname(__DIR__) . '/');

if (!defined('CORE_PATH'))
	define('CORE_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'core/');

return [
		// DB
		'host' => 'localhost',
		'db_name' => 'api_sb', 
		'user' => 'root', 
		'pass' => '', 
		
		// JWT
		'jwt_secret_key' =>'BHH#**@())0))@Jhr&@&#()_hrrK@911kk19))K)_!.S>!_)#I@#(',
		'token_expiration_time' => 5, // minutes, i.e 5
		'extended_token_expiration_time' => 3, // minutes, i.e 3
		'encryption' => 'HS256',
		'enabled_auth' => false
	];