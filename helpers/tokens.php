<?php
require_once "../vendor/autoload.php";

$token = null;
$headers = apache_request_headers();


if (!isset($headers['Authorization'])){
	header('HTTP/1.0 400 Bad Request');
	throw new Exception('Token not found');
}
	
list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');

if($jwt)
{
	try{
		$data = Firebase\JWT\JWT::decode($jwt, $config['jwt_secret_key'], array('HS256'));
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