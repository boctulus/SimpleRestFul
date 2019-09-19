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
$allowed = ['signup', 'login', 'renew', 'revoke'];

if (in_array($_GET['a'],$allowed)){
	$_GET['a']();
}else{
	response()->sendError('Incorrect action');
	exit();
}
	
function signup()
{
	if($_SERVER['REQUEST_METHOD']!='POST')
		exit;
		
	try {
		$data  = request()->getBody();
		
		if ($data == null)
			response()->sendError('Invalid JSON',400);

		$config =  include '../../config/config.php';

		$conn = \Libs\Database::getConnection($config['database']);	
		$u = new \Models\UsersModel($conn);

		//	
		if (count($u->filter(['id'],['email'=>$data['email']]))>0)
			response()->sendError('Email already exists');
				

		$missing = $u::diffWithSchema($data, ['id']);
		if (!empty($missing))
			response()->sendError('Lack some properties in your request: '.implode(',',$missing));

		$data['password'] = sha1($data['password']);

		if (empty($u->create($data)))
			response()->sendError("Error in user registration!");
		
		$time = time();
		$payload = array(
			'iat' => $time, 
			'exp' => $time + 60 * $config['token_expiration_time'],
			'id'  => $u->id,
			'email' => $data['email'],
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

	@param email
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
			
			$email = $data->email ?? null;
			$password = $data->password ?? null;
		break;

		default:
			response()->sendError('Incorrect verb',405);
		break;	
	}	
	
	if (empty($email)){
		response()->sendError('email is required',400);
	}else if (empty($password)){
		response()->sendError('password is required',400);
	}
	
	$config =  include '../../config/config.php';
	
	$conn = \Libs\Database::getConnection($config['database']);
	
	$u = new \Models\UsersModel($conn);
	$u->email = $email;
	$u->password = $password;
	
	if ($u->checkUserAndPass()){
		$time = time();
		$payload = array(
			'iat' => $time, 
			'exp' => $time + 60 * $config['token_expiration_time'],
			'id'  => $u->id,
			'email' => $u->email,
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
					'email' => $data->email,
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