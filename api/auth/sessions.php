<?php
declare(strict_types=1);

header('access-control-allow-credentials: true');
header('access-control-allow-headers: AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2'); 
header('access-control-allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS'); 
header('access-control-allow-Origin: *');
header('content-type: application/json; charset=UTF-8');

include '../../config/constants.php';
include_once  HELPERS_PATH.'http.php';
require_once '../../vendor/autoload.php';
require_once '../../libs/database.php'; 
require_once '../../models/user.php';
include_once '../../helpers/debug.php';
include_once '../../vendor/validator/data-validator/validator.php';


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
		$input = file_get_contents("php://input");	
		$data  = json_decode($input,true);
		
		if ($data == null)
			sendError('Invalid JSON',400);

		$config =  include '../../config/config.php';

		$conn = Database::getConnection($config['database']);
		$u = new UserModel($conn);

		// debo usar mi clase FormValitador !!
		if (!$u->has_properties($data, ['id'])){
			sendError('Lack some properties in your request: '.implode(',',$u->getMissingProperties()));
		}
				
		if ($data['password'] != $data['passwordconfirmation'])
			sendError('Password confimation fails');
		
		$data['password'] = sha1($data['password']);

		unset($data['passwordconfirmation']);
		
		if (empty($u->create($data)))
			sendError("Error in user registration!");
		
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
		
		echo json_encode(['token'=>$token, 'exp' => $payload['exp'] ]);

	}catch(Exception $e){
		SendError($e->getMessage());
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
			$input = file_get_contents("php://input");	
			$data  = json_decode($input);
			
			if ($data == null)
				sendError('Invalid JSON',400);
			
			$username = $data->username ?? null;
			$password = $data->password ?? null;
		break;

		default:
			sendError('Incorrect verb',405);
		break;	
	}	
	
	if (empty($username) || empty($password)){
		sendError('Username and password are required',400);
	}
	
	$config =  include '../../config/config.php';
	
	$conn = Database::getConnection($config['database']);
	
	$u = new UserModel($conn);
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
		
		echo json_encode(['token'=>$token, 'exp' => $payload['exp'] ]);
		
	}else
		echo json_encode(['error'=>"User or password are incorrect"]);
}


/*
	Token refresh
	
	Only by POST*
*/	
function renew()
{
	if ($_SERVER['REQUEST_METHOD']=='OPTIONS'){
		// passs
		sendData('OK',200);
	}elseif ($_SERVER['REQUEST_METHOD']!='POST')
		sendError('Incorrect verb',405);
	
	$config =  include '../../config/config.php';
	
	$headers = apache_request_headers();
	$auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;

	try {
		if (empty($auth)){
			SendError('Authorization not found',400);
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
				 SendError('Unauthorized',401);
			}	
		}else{
			endError('Token not found',400);
		}
	} catch (Exception $e) {
		sendError($e->getMessage(), 400);
	}	
}