<?php

require_once 'constants.php';

return [
		# public_html
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
		'token_expiration_time' => 5, // minutes, i.e 5
		'extended_token_expiration_time' => 3, // minutes, i.e 3
		'encryption' => 'HS256',
		'enabled_auth' => true
	];