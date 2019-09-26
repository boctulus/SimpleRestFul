<?php

require_once 'constants.php';

return [
		'BASE_URL' => '/',   
		'DEFAULT_CONTROLLER' => 'ProductsController',

		'database' => [
			'host' => 'localhost',
			'db_name' => 'api_sb', 
			'user' => 'boctulus', 
			'pass' => 'gogogo2k'
		], 

		'debug_mode' => true,
		
		// JWT
		'jwt_secret_key' =>'BHH#**@())0))@Jhr&@&#()_hrrK@911kk19))K)_!.S>!_)#I@#(',
		'token_expiration_time' => 300,
		'encryption' => 'HS256',
		'enabled_auth' => true,

		// Refresh token
		'refresh_secret_key' => '0abc45de0405060708090a0b0c0d0e0f10112131415161718191a1ff1c1d1e1f'
	];