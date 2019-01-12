<?php
declare(strict_types=1);

require_once '../vendor/autoload.php';
// include_once '../helpers/debug.php';

/* 
	Authorization checkin
*/
function check_auth() {
	$headers = request()->headers();
	$auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;
	
	if (empty($auth)){
		response()->error('Authorization not found',400);
	}
		
	list($jwt) = sscanf($auth, 'Bearer %s');


	if($jwt)
	{
		try{
			// Checking for token invalidation or outdated token
			$config =  include 'config/config.php';
			
			$data = Firebase\JWT\JWT::decode($jwt, $config['jwt_secret_key'], [ $config['encryption'] ]);
			
			if (empty($data))
				response()->error('Unauthorized',401);
			
			if ($data->exp<time())
				response()->error('Token expired',401);
				
		} catch (Exception $e) {
			/*
			 * the token was not able to be decoded.
			 * this is likely because the signature was not able to be verified (tampered token)
			 *
			 * reach this point if token is empty or invalid
			 */
			response()->error('Unauthorized',401);
		}	
	}else{
		 response()->error('Authorization not found',400);
	}
}

