<?php

namespace simplerest\controllers;

use Exception;
use simplerest\core\Controller;
use simplerest\libs\Database;
use simplerest\models\UsersModel;
use simplerest\models\UserRolesModel;
use simplerest\models\RolesModel;
use simplerest\libs\Debug;

class FacebookController extends Controller
{
    protected $client;

    function __construct()
    {
        parent::__construct();

        if (!session_id()) {
            session_start();
        }

        $fb = new \Facebook\Facebook([
            'app_id' => $this->config['facebook_auth']['app_id'], 
            'app_secret' => $this->config['facebook_auth']['app_secret'],
            'default_graph_version' => 'v3.2', 
        ]);

        $this->client = $fb;
    }    

    function getClient(){
        return $this->client;
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
    
    function login_or_register(){
        $fb      = $this->getClient();	
        $helper  = $fb->getRedirectLoginHelper();
		
		try {
			$access_token = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			Factory::response()->SendError('Graph returned an error: ' . $e->getMessage(), 400);
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			Factory::response()->SendError('Facebook SDK returned an error: ' . $e->getMessage(), 400);
			exit;
		}
			
		if (isset($access_token)) {
			$obj = $fb->get('/me?fields=id,first_name,last_name,email', $access_token);
			$me  = $obj->getGraphUser();
			
			//print_r($obj->getGraphUser());

            $fb_id = $me->getId();
            $email = $me->getEmail();
            $firstname  = $me->getFirstName();
            $lastname = $me->getLastName();

            /*
			var_dump($email);
			var_dump($firstname);
            var_dump($lastname);
            */
            
            try 
            {        
                $conn = $this->getConnection();	
                $u = new UsersModel($conn);
    
                // exits	
                $rows = $u->where(['email', $email])->get(['id']);
                if (count($rows)>0){
                    
                    // Email already exists
                    $uid = $rows[0]['id'];
    
                    $ur = new UserRolesModel($conn);
                    $rows = $ur->where(['user_id', $uid])->get(['role_id']);
    
                    $roles = [];
                    if (count($rows) > 0){         
                        $r = new RolesModel();           
                        foreach ($rows as $row){
                            $roles[] = $r->getRoleName($row['role_id']);
                        }
                    }
                }else{
                    $data['email'] = $email;
                    $data['firstname'] = $firstname ?? NULL;
                    $data['lastname'] = $lastname ?? NULL;
                    
                    $uid = $u->create($data);
                    if (empty($uid))
                        return ['error' => 'Error in user registration!', 'code' => 500];
        
                    if ($u->inSchema(['belongs_to'])){
                        Database::table('users')
                        ->where(['id', $uid])
                        ->update(['belongs_to' => $uid]);
                    }

                    $r = new RolesModel();
                    $role = $this->config['registration_role'];

                    $ur = new UserRolesModel($conn);
                    $id = $ur->create([ 'user_id' => $uid, 'role_id' => $r->get_role_id($role) ]);  // registered or other            
            
                    $roles = [$role];
                }  
                
                $my_payload = [
                    'uid' => $uid, 
                    'roles' => $roles,
                    'confirmed_email' => 1
                ];

                //Debug::debug($my_payload); ////
    
                $access  = $this->gen_jwt($my_payload, 'access_token');
                $refresh = $this->gen_jwt($my_payload, 'refresh_token');

                return ['code' => 200,  
                        'data' => [ 
                                    'access_token'=> $access,
                                    'token_type' => 'bearer', 
                                    'expires_in' => $this->config['access_token']['expiration_time'],
                                    'refresh_token' => $refresh                                         
                                    // 'scope' => 'read write'
                        ],
                        'error' => ''
                ];
            }catch(\Exception $e){
                return ['error' => $e->getMessage(), 'code' => 500];
            }	



			
		} else {
			if ($helper->getError()) {
				Factory::response()->SendError('Unauthorized', 401, $helper->getErrorDescription());
				/*				
				echo "Error: " . $helper->getError() . "\n";
				echo "Error Code: " . $helper->getErrorCode() . "\n";
				echo "Error Reason: " . $helper->getErrorReason() . "\n";
				echo "Error Description: " . $helper->getErrorDescription() . "\n";
				*/
			} else {
				Factory::response()->SendError('Bad request', 400);
			}
			exit;
		}
    }

    ///////////////////////////////////////////////

    function login(){
		$fb   = $this->getClient();
		$helper = $fb->getRedirectLoginHelper();
		
		$permissions = ['email'];
		$auth_url = $helper->getLoginUrl($this->config['facebook_auth']['callback'], $permissions);
        
        header("Location: $auth_url");
    }
	
}