<?php
require_once 'libs/database.php';
require_once 'models/product.php';
require_once 'models/user.php';


function index(){
	include "views/login.php";
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
		$payload = array(
			'iat' => $time, 
			'exp' => $time + 60*$config['token_expiration_time'],
			'id'  => $u->id,
			'username' => $u->username,
			'ip' => [
				'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? '',
				'HTTP_CLIENT_IP' => $_SERVER['HTTP_CLIENT_IP'] ?? '',
				'HTTP_FORWARDED' => $_SERVER['HTTP_FORWARDED'] ?? '',
				'HTTP_FORWARDED_FOR' => $_SERVER['HTTP_FORWARDED_FOR'] ?? '',
				'HTTP_X_FORWARDED' => $_SERVER['HTTP_X_FORWARDED'] ?? '',
				'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''
			]
		);
	
		$token = Firebase\JWT\JWT::encode($payload, $config['jwt_secret_key'],  $config['encryption']);
		
		echo json_encode(['token'=>$token, 'exp' => $payload['exp'] ]);
		
	}else
		echo json_encode(['error'=>"User or password are incorrect"]);
}

function renew()
{
	$config =  include 'config/config.php';
	
	$headers = apache_request_headers();
	$auth = $headers['Authorization'] ?? $headers['authorization'];
	
	if (empty($auth)){
		header('HTTP/1.0 400 Bad Request');
		throw new Exception('Authorization not found');
	}
		
	list($jwt) = sscanf($auth, 'Bearer %s');
	
	if($jwt)
	{
		try{
			// Checking for token invalidation or outdated token
			$data = Firebase\JWT\JWT::decode($jwt, $config['jwt_secret_key'],  [ $config['encryption'] ]);
	
			$time = time();
			$payload = array(
				'iat' => $time, 
				'exp' => $time + 60*$config['extended_token_expiration_time'], 
				'id' => $data->id,
				'username' => $data->username,
				'ip' => [
					'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? '',
					'HTTP_CLIENT_IP' => $_SERVER['HTTP_CLIENT_IP'] ?? '',
					'HTTP_FORWARDED' => $_SERVER['HTTP_FORWARDED'] ?? '',
					'HTTP_FORWARDED_FOR' => $_SERVER['HTTP_FORWARDED_FOR'] ?? '',
					'HTTP_X_FORWARDED' => $_SERVER['HTTP_X_FORWARDED'] ?? '',
					'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''
				]
			);
			
			$token = Firebase\JWT\JWT::encode($payload, $config['jwt_secret_key'],  $config['encryption']);
			
			echo json_encode(['token'=>$token, 'exp' => $payload['exp'] ]);
			
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