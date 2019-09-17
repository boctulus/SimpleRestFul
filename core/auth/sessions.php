<?php
declare(strict_types=1);

header('access-control-allow-credentials: true');
header('access-control-allow-headers: AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2'); 
header('access-control-allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS'); 
header('access-control-allow-Origin: *');
header('content-type: application/json; charset=UTF-8');

include '../../config/constants.php';
require_once VENDOR_PATH.'autoload.php';
require_once LIBS_PATH . 'database.php'; 
require_once MODELS_PATH . 'users.php';
include_once HELPERS_PATH . 'debug.php';

include_once HELPERS_PATH . 'factory.php';
require_once CORE_PATH . 'request.php';
require_once CORE_PATH . 'response.php';


/*
	Mini-router
*/
$allowed = ['signin', 'login', 'renew', 'revoke'];

if (in_array($_GET['a'],$allowed)){
	$_GET['a']();
}else
	exit();


function signin()
{
	if($_SERVER['REQUEST_METHOD']!='POST')
		exit;
		
	try {
		$data  = request()->getBody();
		
		if ($data == null)
			response()->sendError('Invalid JSON',400);

		$config =  include '../../config/config.php';

		$conn = \Core\Database::getConnection($config['database']);
		$u = new \Models\UsersModel($conn);

		
		$missing = $u::diffWithSchema($data, ['id']);
		if (!empty($missing))
			response()->sendError('Lack some properties in your request: '.implode(',',$missing));
				
		if ($data['password'] != $data['passwordconfirmation'])
			response()->sendError('Password confimation fails');
		
		$data['password'] = sha1($data['password']);

		unset($data['passwordconfirmation']);
		
		if (empty($u->create($data)))
			response()->sendError("Error in user registration!");
		
		$time = time();
		$payload = array(
			'iat' => $time, 
			'exp' => $time + 60 * $config['token_expiration_time'],
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
		
		response()->send(['token'=>$token, 'exp' => $payload['exp'] ]);

	}catch(\Exception $e){
		response()->sendError($e->getMessage());
	}	
		
}

	
/*
	Login for API Rest

	@param username
	@param password
*/
function login()
{
	switch($_SERVER['REQUEST_METHOD']) {
		case 'OPTIONS':
			// passs
			http_response_code(200);
			exit();
		break;

		case 'POST':
			$data  = request()->getBody(false);

			if ($data == null)
				response()->sendError('Invalid JSON',400);
			
			$username = $data->username ?? null;
			$password = $data->password ?? null;
		break;

		default:
			response()->sendError('Incorrect verb',405);
		break;	
	}	
	
	if (empty($username) || empty($password)){
		response()->sendError('Username and password are required',400);
	}
	
	$config =  include '../../config/config.php';
	
	$conn = \Libs\Database::getConnection($config['database']);
	
	$u = new \Models\UsersModel($conn);
	$u->username = $username;
	$u->password = $password;
	
	if ($u->getUserIfExists()){
		$time = time();
		$payload = array(
			'iat' => $time, 
			'exp' => $time + 60 * $config['token_expiration_time'],
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
		
		response()->send(['token'=>$token, 'exp' => $payload['exp']]);
		
	}else
		response()->sendError("User or password are incorrect", 401);
}


/*
	Token refresh
	
	Only by POST*
*/	
function renew()
{
	if ($_SERVER['REQUEST_METHOD']=='OPTIONS'){
		// passs
		response()->send('OK',200);
	}elseif ($_SERVER['REQUEST_METHOD']!='POST')
		response()->sendError('Incorrect verb',405);
	
	$config =  include '../../config/config.php';
	
	$headers = request()->headers();
	$auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;

	try {
		if (empty($auth)){
			response()->sendError('Authorization not found',400);
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
				
				response()->send(['token'=>$token, 'exp' => $payload['exp'] ]);
				
			} catch (\Exception $e) {
				/*
				 * the token was not able to be decoded.
				 * this is likely because the signature was not able to be verified (tampered token)
				 */
				 response()->sendError('Unauthorized',401);
			}	
		}else{
			response()->sendError('Token not found',400);
		}
	} catch (\Exception $e) {
		response()->sendError($e->getMessage(), 400);
	}	
}