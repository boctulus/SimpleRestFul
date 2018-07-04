<?php

require_once 'libs/database.php';
require_once 'models/product.php';
require_once 'models/user.php';


call_user_func($_REQUEST['a'] ?? 'index');


function index(){
	include "views/login.php";
}

function login(){
	$config =  include 'config/config.php';
	
	// Get db connection  
	$db = new Database($config);
	$conn = $db->conn;
	
	$u = new User($conn);
	$u->username = $_REQUEST['username'];
	$u->password = $_REQUEST['password'];
	
	if ($u->exists()){
		$time = time();

		$token = array(
			'iat' => $time, // Tiempo que inició el token
			'exp' => $time + (60*60), // Tiempo que expirará el token (+1 hora)
			'data' => [ // información del usuario
				'id' => $u->id,
				'username' => $u->username
			]
		);
	
		$jwt = Firebase\JWT\JWT::encode($token, $config['jwt_secret_key']);
		echo json_encode(['token'=>$jwt]);
		
	}else
		echo "Error en usuario o password";
}

