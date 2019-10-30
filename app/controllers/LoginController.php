<?php

namespace simplerest\controllers;

use simplerest\libs\Debug;
use simplerest\libs\Factory;
use simplerest\libs\Database;
use simplerest\models\UsersModel;
use simplerest\models\RolesModel;
use simplerest\libs\Utils;

class LoginController extends MyController
{
	function index(){
		$this->login();
	}
	
	function login()
	{
		$google_ctrl = new GoogleController();
		$gl_client   = $google_ctrl->getClient();
		$gl_auth_url = $gl_client->createAuthUrl();

		$fb_ctrl = new FacebookController();
		$fb      = $fb_ctrl->getClient();
		$helper  = $fb->getRedirectLoginHelper();	

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl(CALLBACK, $permissions);

		$this->view('login.php', [	'title'=>'Ingreso', 
									'hidenav'=> true, 
									'gl_auth_url' => $gl_auth_url,
									'fb_auth_url' => htmlspecialchars($loginUrl)
		]);
	}
	
	function signup(){
		$this->view('signup.php', ['title'=>'Registro', 'hidenav'=> true]);
	}

	function google_login()
	{
		$google_ctrl = new GoogleController();
		$res = $google_ctrl->login_or_register();

		if (isset($res['data'])){
			$this->view('generic.php', [
				'title'=>'Google login', 
				'hidenav'=> true,
				'access_token' => $res['data']['access_token'],
				'expires_in' => $res['data']['expires_in'],
				'refresh_token' => $res['data']['refresh_token']
			]);
		}else {
			$this->view('generic.php', [
				'title'=>'Google login', 
				'hidenav'=> false,
				'error' => $res['error']
			]);
		}		
	}
	
	/*
		Sin terminar
	*/
	function fb_callback(){

		$fb_ctrl = new FacebookController();
		$fb      = $fb_ctrl->getClient();	
		$helper  = $fb->getRedirectLoginHelper();
		
		try {
			$accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		
		if (! isset($accessToken)) {
			if ($helper->getError()) {
			header('HTTP/1.0 401 Unauthorized');
			echo "Error: " . $helper->getError() . "\n";
			echo "Error Code: " . $helper->getErrorCode() . "\n";
			echo "Error Reason: " . $helper->getErrorReason() . "\n";
			echo "Error Description: " . $helper->getErrorDescription() . "\n";
			} else {
			header('HTTP/1.0 400 Bad Request');
			echo 'Bad request';
			}
			exit;
		}
		
		// Logged in
		echo '<h3>Access Token</h3>';
		var_dump($accessToken->getValue());
		
		// The OAuth 2.0 client handler helps us manage access tokens
		$oAuth2Client = $fb->getOAuth2Client();
		
		// Get the access token metadata from /debug_token
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);
		echo '<h3>Metadata</h3>';
		var_dump($tokenMetadata);
		
		// Validation (these will throw FacebookSDKException's when they fail)
		$tokenMetadata->validateAppId('{app-id}'); // Replace {app-id} with your app id
		// If you know the user ID this access token belongs to, you can validate it here
		//$tokenMetadata->validateUserId('123');
		$tokenMetadata->validateExpiration();
		
		if (! $accessToken->isLongLived()) {
			// Exchanges a short-lived access token for a long-lived one
			try {
			$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
			} catch (Facebook\Exceptions\FacebookSDKException $e) {
			echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
			exit;
			}
		
			echo '<h3>Long-lived</h3>';
			var_dump($accessToken->getValue());
		}
		
	}

	/*
        JWT token for email confirmation & remember me
    */
    protected function gen_jwt2(string $email, string $secret_key, string $encryption, string $exp_time){
        $time = time();

        $payload = [
            'alg' => $encryption,
            'typ' => 'JWT',
            'iat' => $time, 
            'exp' => $time + $exp_time,
            'email' => $email
        ];

        return \Firebase\JWT\JWT::encode($payload, $secret_key,  $encryption);
    }

	protected function gen_jwt(array $props, string $token_type){
        $time = time();

        $payload = [
            'alg' => $this->config[$token_type]['encryption'],
            'typ' => 'JWT',
            'iat' => $time, 
            'exp' => $time + $this->config[$token_type]['expiration_time']
        ];
        
        $payload = array_merge($payload, $props);

        return \Firebase\JWT\JWT::encode($payload, $this->config[$token_type]['secret_key'],  $this->config[$token_type]['encryption']);
    }

	function rememberme(){
		$this->view('rememberme.php', ['title'=>'Recuérdame', 'hidenav'=> true]);
	}

	/*
		Las urls para cambio de correo son muy similares a esta:

		http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MjI2OTE4NiwiZXhwIjoxNTcyODczOTg2LCJlbWFpbCI6InBlcGVAZ21haWwuY29tIn0.fl_jVsAe16ePinDY0QT8GRK_cuk0Ebn3CVNfCgfnM3s/1572873986

		Solo cambia el nombre del "action" por change_email :

		http://simplerest.lan/login/change_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MjI2OTE4NiwiZXhwIjoxNTcyODczOTg2LCJlbWFpbCI6InBlcGVAZ21haWwuY29tIn0.fl_jVsAe16ePinDY0QT8GRK_cuk0Ebn3CVNfCgfnM3s/1572873986

	*/
	function rememberme_process(){
		$data  = Factory::request()->getBody(false);

		if ($data == null)
			Factory::response()->sendError('Invalid JSON',400);
	
		$email = $data->email ?? null;

		if ($email == null)
			Factory::response()->sendError('Empty email', 400);

		$u = Database::table('users');
		$rows = $u->where(['email', $email])->get(['id']);

		if (count($rows) === 0)
			Factory::response()->send([]);

		$exp = time() + $this->config['email']['expires_in'];	

		$base_url =  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'];

		$token = $this->gen_jwt2($email, $this->config['email']['secret_key'], $this->config['email']['encryption'], $this->config['email']['expires_in'] );
		$url = $base_url . '/login/change_email/' . $token . '/' . $exp; 

		/*
			mail -->
				title: Recuperación de contraseña 
				to: $email
				body: $url
		*/

		//Factory::response()->send(['data' => $url ]);

		$ok = (bool) Utils::logger($url);
		Factory::response()->send(['success' => $ok ]);

		/*
			Si se pudo enviar el correo, redirigir a la vista donde le dice que chequee en la carpeta de "correo no deseado"
		*/
	}

	function confirm_email($jwt, $exp)
	{
		// Es menos costoso veririficar así en principio
		if ((int) $exp < time())
			$error = 'Link is outdated';
		else{

			if($jwt != null)
			{
				try {
					$payload = \Firebase\JWT\JWT::decode($jwt, $this->config['email']['secret_key'], [ $this->config['email']['encryption'] ]);
					
					if (empty($payload))
						$error = 'Unauthorized!';                     

					if (empty($payload->email)){
						$error = 'email is needed';
					}

					if ($payload->exp < time())
						$error = 'Token expired';
						
					$u = Database::table('users');
					$ok = (bool) $u->where(['email', $payload->email])
						   		->update(['confirmed_email' => 1]);
										
					//if (!$ok)		
					//	$error = 'Error en activación';				

				} catch (\Exception $e) {
					/*
					* the token was not able to be decoded.
					* this is likely because the signature was not able to be verified (tampered token)
					*
					* reach this point if token is empty or invalid
					*/
					Factory::response()->sendError($e->getMessage(),401);
				}	
			}else{
				Factory::response()->sendError('Authorization jwt token not found',400);
			}     
		}	

		if (!isset($error)){
			$rows = Database::table('users')->where(['email', $payload->email])->get(['id']);
			
			$u = Database::table('users');
			$u->id = $rows[0]['id'];
			$role_ids = $u->fetchRoles();

			$roles = [];

			if (count($role_ids) != 0){
				$r = new RolesModel();
				foreach ($role_ids as $role_id){
					$roles[] = $r->getRoleName($role_id);
				}
			}
			
			$access  = $this->gen_jwt(['uid' => $u->id, 'roles' => $roles], 'access_token');
			$refresh = $this->gen_jwt(['uid'=> $u->id, 'roles' => $roles], 'refresh_token');

			//
			// Cargar vista 
			// donde poder setear una nueva contraseña
			//

			$this->view('generic.php', [
				'title'=>'Confirmación de correo', 
				'hidenav'=> true,
				'access_token' => $access,
				'expires_in' => $this->config['email']['expires_in'],
				'refresh_token' => $refresh
			]);
	
		}else {
			$this->view('generic.php', [
				'title'=>'Confirmación de correo fallida', 
				'hidenav'=> false,
				'error' => $error
			]);
		}	

	}

	function change_email($jwt, $exp)
	{
		// Es menos costoso veririficar así en principio
		if ((int) $exp < time())
			$error = 'Link is outdated';
		else{

			if($jwt != null)
			{
				try {
					// Checking for token invalidation or outdated token
					
					$payload = \Firebase\JWT\JWT::decode($jwt, $this->config['email']['secret_key'], [ $this->config['email']['encryption'] ]);
					
					if (empty($payload))
						$error = 'Unauthorized!';                     

					if (empty($payload->email)){
						$error = 'email is needed';
					}

					if ($payload->exp < time())
						$error = 'Token expired';

				} catch (\Exception $e) {
					/*
					* the token was not able to be decoded.
					* this is likely because the signature was not able to be verified (tampered token)
					*
					* reach this point if token is empty or invalid
					*/
					$error = $e->getMessage();
				}	
			}else{
				$error = 'Authorization jwt token not found';
			}     
		}	

		if (!isset($error)){						
			//
			// Cargar vista 
			// donde poder setear una nueva contraseña
			//

			$this->view('update_pass.php', [
				'title'=>'Recuperación de contraseña', 
				'hidenav'=> true
			]);
	
		}else {
			$this->view('generic.php', [
				'title'=>'Recuperación de contraseña', 
				'hidenav'=> false,
				'error' => $error
			]);
		}
	}

	function change_email_process(){
		if($_SERVER['REQUEST_METHOD']!='PATCH')
			exit;
		
		$headers = Factory::request()->headers();
		$auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;
		
		$data  = Factory::request()->getBody();
            
        if ($data == null)
			Factory::response()->sendError('Invalid JSON',400);
			
		if (!isset($data['password']) || empty($data['password']))
			Factory::response()->sendError('Empty email',400);
		
		if (empty($auth))
			return false;			
			
		list($jwt) = sscanf($auth, 'Bearer %s');

		if($jwt != null)
        {
            try{
                // Checking for token invalidation or outdated token
                
                $payload = \Firebase\JWT\JWT::decode($jwt, $this->config['email']['secret_key'], [ $this->config['email']['encryption'] ]);
                
                if (empty($payload))
					Factory::response()->sendError('Unauthorized!',401);                     
					
                if (empty($payload->email)){
                    Factory::response()->sendError('Undefined email',400);
                }

                if ($payload->exp < time())
                    Factory::response()->sendError('Token expired',401);
		
				
				$rows = Database::table('users')->where(['email', $payload->email])->get(['id']);

				$affected = Database::table('users')->where(['id', $rows[0]['id']])->update(['password' => password_hash($data['password'], PASSWORD_DEFAULT)]);

				//Factory::response()->send(['success' => $ok]);				
				
				$role_ids = Database::table('users')->fetchRoles($rows[0]['id']);

				$roles = [];
				if (count($role_ids) != 0){
					$r = new RolesModel();
					foreach ($role_ids as $role_id){
						$roles[] = $r->getRoleName($role_id);
					}
				}
				
				$access  = $this->gen_jwt(['uid' => $rows[0]['id'], 'roles' => $roles], 'access_token');
				$refresh = $this->gen_jwt(['uid'=> $rows[0]['id'], 'roles' => $roles], 'refresh_token');
 
				Factory::response()->send([
					'access_token' => $access,
					'expires_in' => $this->config['email']['expires_in'],
					'refresh_token' => $refresh
				]);
				
            } catch (\Exception $e) {
                /*
                * the token was not able to be decoded.
                * this is likely because the signature was not able to be verified (tampered token)
                *
                * reach this point if token is empty or invalid
                */
                Factory::response()->sendError($e->getMessage(),401);
            }	
        }else{
            Factory::response()->sendError('Authorization jwt token not found',400);
        }
       
	}
	
}


