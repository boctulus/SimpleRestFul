<?php

namespace simplerest\controllers;

use Exception;
use simplerest\core\Controller;
use simplerest\libs\DB;
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
            'exp' => $time + $this->config[$token_type]['expiration_time'],
            'ip'  => $_SERVER['REMOTE_ADDR']
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
            
            DB::beginTransaction();

            try 
            {        
                $conn = $this->getConnection();	
                $u = (new UsersModel($conn))->assoc();
    
                // exits	
                $rows = $u->where(['email', $email])->get(['id']);

                if (count($rows)>0){
                    
                    // Email already exists
                    $uid = $rows[0]['id'];
    
                    $ur = (new UserRolesModel($conn))->assoc();
                    $rows = $ur->where(['belongs_to', $uid])->get(['role_id']);
    
                    $roles = [];
                    if (count($rows) > 0){         
                        $r = new RolesModel();           
                        foreach ($rows as $row){
                            $roles[] = $r->get_role_name($row['role_id']);
                        }
                    }

                    $_permissions = DB::table('user_tb_permissions')->assoc()->select(['tb', 'can_create as c', 'can_show as r', 'can_update as u', 'can_delete as d', 'can_list as l'])->where(['user_id' => $uid])->get();

                    $perms = [];
                    foreach ($_permissions as $p){
                        $tb = $p['tb'];
                        $perms[$tb] = $p['la'] * 64 + $p['ra'] * 32 +  $p['l'] * 16 + $p['r'] * 8 + $p['c'] * 4 + $p['u'] * 2 + $p['d'];
                    }

                    $active = $rows[0]['active'];

                }else{
                    $data['email'] = $email;
                    $data['firstname'] = $firstname ?? NULL;
                    $data['lastname'] = $lastname ?? NULL;
                    
                    ///
                    preg_match('/[^@]+/', $payload['email'], $matches);
                    $username = substr($matches[0], 0, 12);
            
                    $existe = DB::table('users')->where(['username', $username])->exists();
                    
                    if ($existe){
                        $_username = $username;
                        $append = 1;

                        while($existe){
                            $_username = $username . $append;
                            $existe = DB::table('users')->where(['username', $_username])->exists();
                            $append++;
                        }

                        $username = $_username;
                    }         
            
                    $data['username'] = $username;
                    ///
                
                    $uid = $u->create($data);
                    if (empty($uid))
                        throw new Exception('Error in user registration!');
        
                    if ($u->inSchema(['belongs_to'])){
                        DB::table('users')
                        ->where(['id', $uid])
                        ->update(['belongs_to' => $uid]);
                    }

                    /*
                    $r = new RolesModel();
                    
                    if (!empty($this->config['registration_role'])){
                        $role = $this->config['registration_role'];

                        $r  = new RolesModel();
                        $ur = DB::table('userRoles');

                        $role_id = $r->get_role_id($role);

                        if ($role_id == null){
                            throw new Exception('Invalid default registration role');
                        }

                        $id = $ur->create([ 
                                            'belongs_to' => $uid, 
                                            'role_id' => $role_id 
                                            ]);  

                        if (empty($id))
                            throw new Exception('Error registrating user role');          

                        $roles = [$role];        

                    } else {
                        $roles = [];
                    }  
                    */

                    $active = $this->config['pre_activated'] ? true : null;                    
                }  

                $roles = []; //
                $perms = [];
    
                $access  = $this->gen_jwt([
                                            'uid' => $uid, 
                                            'roles' => $roles,
                                            'tb_permissions' => $perms,
                                            'active' => $active
                ], 'access_token');

                $refresh = $this->gen_jwt([
                                            'uid' => $uid
                ], 'refresh_token');


                DB::commit(); 

                return ['code' => 200,  
                        'data' => [ 
                                    'uid' => $uid,
                                    'access_token'=> $access,
                                    'token_type' => 'bearer', 
                                    'expires_in' => $this->config['access_token']['expiration_time'],
                                    'refresh_token' => $refresh
                        ],
                        'error' => ''
                ];
            }catch(\Exception $e){
                DB::rollback();

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
		$auth_url = $helper->getLoginUrl($this->config['facebook_auth']['callback_url'], $permissions);
        
        header("Location: $auth_url");
    }
	
}