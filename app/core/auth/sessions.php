<?php
declare(strict_types=1);

header('access-control-allow-credentials: true');
header('access-control-allow-headers: AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2'); 
header('access-control-allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS'); 
header('access-control-allow-Origin: *');
header('content-type: application/json; charset=UTF-8');


/*
	Mini-router
*/
$allowed = ['signup', 'login', 'renew', 'revoke'];

if (in_array($_GET['a'],$allowed)){
	$_GET['a']();
}else{
	\simplerest\libs\Factory::response()->sendError('Incorrect action');
	exit();
}
	
function signup()
{
	if($_SERVER['REQUEST_METHOD']!='POST')
		exit;
		
	try {
		$data  = \simplerest\libs\Factory::request()->getBody();
		
		if ($data == null)
			\simplerest\libs\Factory::response()->sendError('Invalid JSON',400);

		$config =  include '../../config/config.php';

		$conn = \simplerest\libs\Database::getConnection($config['database']);	
		$u = new UsersModel($conn);

		//	
		if (count($u->filter(['id'],['email'=>$data['email']]))>0)
			\simplerest\libs\Factory::response()->sendError('Email already exists');
				

		$missing = $u::diffWithSchema($data, ['id']);
		if (!empty($missing))
			\simplerest\libs\Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing));

		$data['password'] = sha1($data['password']);

		if (empty($u->create($data)))
			\simplerest\libs\Factory::response()->sendError("Error in user registration!");
		
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
		
		\simplerest\libs\Factory::response()->send(['token'=>$token, 'exp' => $payload['exp'] ]);

	}catch(\Exception $e){
		\simplerest\libs\Factory::response()->sendError($e->getMessage());
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
			$data  = \simplerest\libs\Factory::request()->getBody(false);

			if ($data == null)
				\simplerest\libs\Factory::response()->sendError('Invalid JSON',400);
			
			$email = $data->email ?? null;
			$password = $data->password ?? null;
		break;

		default:
			\simplerest\libs\Factory::response()->sendError('Incorrect verb',405);
		break;	
	}	
	
	if (empty($email)){
		\simplerest\libs\Factory::response()->sendError('email is required',400);
	}else if (empty($password)){
		\simplerest\libs\Factory::response()->sendError('password is required',400);
	}
	
	$config =  include '../../config/config.php';
	
	$conn = \simplerest\libs\Database::getConnection($config['database']);
	
	$u = new UsersModel($conn);
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
		
		\simplerest\libs\Factory::response()->send(['token'=>$token, 'exp' => $payload['exp']]);
		
	}else
		\simplerest\libs\Factory::response()->sendError("User or password are incorrect", 401);
}


/*
	Token refresh
	
	Only by POST*
*/	
function renew()
{
	if ($_SERVER['REQUEST_METHOD']=='OPTIONS'){
		// passs
		\simplerest\libs\Factory::response()->send('OK',200);
	}elseif ($_SERVER['REQUEST_METHOD']!='POST')
		\simplerest\libs\Factory::response()->sendError('Incorrect verb',405);
	
	$config =  include '../../config/config.php';
	
	$headers = \simplerest\libs\Factory::request()->headers();
	$auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;

	try {
		if (empty($auth)){
			\simplerest\libs\Factory::response()->sendError('Authorization not found',400);
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
				
				\simplerest\libs\Factory::response()->send(['token'=>$token, 'exp' => $payload['exp'] ]);
				
			} catch (\Exception $e) {
				/*
				 * the token was not able to be decoded.
				 * this is likely because the signature was not able to be verified (tampered token)
				 */
				\simplerest\libs\Factory::response()->sendError('Unauthorized',401);
			}	
		}else{
			\simplerest\libs\Factory::response()->sendError('Token not found',400);
		}
	} catch (\Exception $e) {
		\simplerest\libs\Factory::response()->sendError($e->getMessage(), 400);
	}	
}