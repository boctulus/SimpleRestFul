<?php
require_once "../vendor/autoload.php";
require_once '../libs/database.php';
require_once '../models/user.php';

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
		$config =  include '../config/config.php';
		$conn = Database::getConnection($config);
	
		$u = new User($conn);
		$u->id = $data->data->id;
		$u->read();
		
		if (empty($u->token) || $u->tokenExpiration<time()){
			header('HTTP/1.0 401 Unauthorized');
			throw new Exception("Unauthorized");
		}
			
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