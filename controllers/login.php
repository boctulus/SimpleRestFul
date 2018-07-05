<?php

require_once 'libs/database.php';
require_once 'models/product.php';
require_once 'models/user.php';


call_user_func($_REQUEST['a'] ?? 'index');


function index(){
	include "views/login.php";
}

function logout(){
	// destruir la "session" revocando el token
	
	$config =  include 'config/config.php';
	$conn = Database::getConnection($config);
	
	// Extraigo id de usuario del token 
	require_once "vendor/autoload.php";
	
	$token = null;
	$headers = apache_request_headers();
	
	if (!isset($headers['Authorization'])){
		header('HTTP/1.0 400 Bad Request');
		throw new Exception('Authorization not found');
	}	
	
	list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');
	
	
	if($jwt)
	{
		try{
			$data = Firebase\JWT\JWT::decode($jwt, $config['jwt_secret_key'], array('HS256'));
			
			// chequeo que el token no haya sido revocado o este vencido  
			$config =  include 'config/config.php';
			$conn = Database::getConnection($config);
		
			$u = new User($conn);
			$u->id = $data->data->id;
			
			$u->token = '';
			$u->tokenExpiration = 0;
			$u->updateToken();
		
				
		} catch (Exception $e) {
			/*
			 * the token was not able to be decoded.
			 * this is likely because the signature was not able to be verified (tampered token)
			 */
			header('HTTP/1.0 401 Unauthorized');
			throw new Exception("Unauthorized");
		}	
	}else{
		 header('HTTP/1.0 400 Bad Request');
		 throw new Exception('Token not found');
	}
		

}

function login()
{
	$config =  include 'config/config.php';
	$conn = Database::getConnection($config);
	
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
	
		$u->token = Firebase\JWT\JWT::encode($token, $config['jwt_secret_key']);
		$u->tokenExpiration = $token['exp'];
		$u->updateToken();
		
		echo json_encode(['token'=>$u->token]);
		
	}else
		echo json_encode(['error'=>"Error en usuario o password"]);
}

