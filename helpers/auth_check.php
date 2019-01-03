<?php
require_once "../vendor/autoload.php";
require_once '../libs/database.php';
require_once '../models/user.php';
require_once 'messages.php';

// Authorization checkin

function check_auth() {
	$headers = apache_request_headers();
	$auth = $headers['Authorization'] ?? $headers['authorization'] ?? NULL;
	
	// logger($headers);
	// logger("\n-----------------------------\n\n");
	
	if (empty($auth)){
		sendError('Authorization not found',400);
	}
		
	list($jwt) = sscanf($auth, 'Bearer %s');


	if($jwt)
	{
		try{
			// Checking for token invalidation or outdated token
			$config =  include '../config/config.php';
			$conn = Database::getConnection($config);
			
			$data = Firebase\JWT\JWT::decode($jwt, $config['jwt_secret_key'], [ $config['encryption'] ]);
			
			// deberia guardar los tokens en otra tabla y hacer un INNER JOIN
			//
			// Payload: meter 'id', 'username', 'IP', 'createad_at', 'expiration_time' (los ultimos de tipo TIMESTAMP)
			//
			// No hacer mas consultas....... confiar en la criptografia
			//
			$u = new User($conn);
			$u->id = $data->data->id;
			$u->read();
			
			if (empty($u->token) || $jwt!=$u->token || $u->tokenExpiration<time()){
				sendError('Unauthorized',401);
			}
				
		} catch (Exception $e) {
			/*
			 * the token was not able to be decoded.
			 * this is likely because the signature was not able to be verified (tampered token)
			 *
			 * reach this point if token is empty or invalid
			 */
			sendError('Unauthorized',401);
		}	
	}else{
		 sendError('Authorization not found',400);
	}
}

