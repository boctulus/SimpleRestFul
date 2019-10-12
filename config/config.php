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

	'debug_mode'   => true,
	
	'enabled_auth' => true,

	'access_token' => [
		'secret_key' =>'BHH#**@())0))@Jhr&@&#()_hrrK@911kk19))K)_!.S>!_)#I@#(',
		'expiration_time' => 60,   // seconds
		'encryption' => 'HS256'			
	],

	'refresh_token' => [
		'secret_key' => '0abc45de0405060708090a0b0c0d0e0f10112131415161718191a1ff1c1d1e1f'
	],

	'session' => [
		'secret_key' => 'fabcefdeefdcabcdea457123efda67857123efda67857123efda00234244fabc'
	],

	// podría haber otro límite que dependa del rol del usuario o algo en su registro
	'max_records' => 50
		
];