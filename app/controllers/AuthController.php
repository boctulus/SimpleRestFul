<?php

namespace simplerest\controllers;

use Exception;
use simplerest\core\Controller;
use simplerest\core\interfaces\IAuth;
use simplerest\libs\Factory;
use simplerest\models\UsersModel;
use simplerest\models\SessionsModel;
use simplerest\models\RolesModel;
use simplerest\models\UserRoleModel;
use simplerest\libs\Debug;
use simplerest\libs\crypto\SaferCrypto;

/*
    Debería ser un Singletón
*/
class AuthController extends Controller implements IAuth
{
    protected $must_have = [];
    protected $must_not  = [];

    function __construct()
    { 
        header('access-control-allow-credentials: true');
        header('access-control-allow-headers: AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2'); 
        header('access-control-allow-Methods: POST,OPTIONS'); 
        header('access-control-allow-Origin: *');
        header('content-type: application/json; charset=UTF-8');

        parent::__construct();
    }
       
    function addMustHave(array $conditions, $http_code, $msg) {
        $this->must_have[] = [ $conditions , $http_code, $msg ];
    }

    function addMustNotHave(array $conditions, $http_code, $msg) {
        $this->must_not[]  = [ $conditions , $http_code, $msg ];
    }

    protected function pass_dec($encrypted){
        $key = hex2bin($this->config['refresh_token']['secret_key']); 
        return SaferCrypto::decrypt($encrypted, $key, true);
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
                $data  = Factory::request()->getBody(false);

                if ($data == null)
                    Factory::response()->sendError('Invalid JSON',400);
                
                $email = $data->email ?? null;
                $password = $data->password ?? null;
            break;

            default:
                Factory::response()->sendError('Incorrect verb',405);
            break;	
        }	
        
        if (empty($email)){
            Factory::response()->sendError('email is required',400);
        }else if (empty($password)){
            Factory::response()->sendError('password is required',400);
        }
              
        $conn = $this->getConnection();
        
        $u = new UsersModel($conn);
        $u->email = $email;
        $u->password = $password;
        
        if ($u->checkCredentials()){

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

            // 'expires_in' no iría más por fuera de los tokens
            Factory::response()->send([ 
                                        'access_token'=> $access,
                                        'token_type' => 'bearer', 
                                        'expires_in' => $this->config['access_token']['expiration_time'],
                                        'refresh_token' => $refresh                                         
                                        // 'scope' => 'read write'
                                      ]);
            
        }else
            Factory::response()->sendError("User or password are incorrect", 401);
    }


    /*
        Access Token renewal
        
        Only by POST*
    */	
    function token()
    {
        if ($_SERVER['REQUEST_METHOD']=='OPTIONS'){
            // passs
            Factory::response()->send('OK',200);
        }elseif ($_SERVER['REQUEST_METHOD']!='POST')
            Factory::response()->sendError('Incorrect verb',405);

        $request = Factory::request();

        $headers = $request->headers();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (empty($auth)){
            Factory::response()->sendError('Authorization not found',400);
        }

        try {                                      
            // refresh token
            list($refresh) = sscanf($auth, 'Bearer %s');

            $payload = \Firebase\JWT\JWT::decode($refresh, $this->config['refresh_token']['secret_key'], [ $this->config['refresh_token']['encryption'] ]);
            
            if (empty($payload))
                Factory::response()->sendError('Unauthorized!',401);                     

            if (empty($payload->uid)){
                Factory::response()->sendError('uid is needed',400);
            }

            if (empty($payload->roles)){
                Factory::response()->sendError('Undefined roles',400);
            }

            if ($payload->exp < time())
                Factory::response()->sendError('Token expired, please log in',401);

            $access  = $this->gen_jwt(['uid' => $payload->uid, 'roles' => $payload->roles], 'access_token');

            ///////////
            Factory::response()->send([ 
                                        'access_token'=> $access,
                                        'token_type' => 'bearer', 
                                        'expires_in' => $this->config['access_token']['expiration_time'] 
                                        // 'scope' => 'read write'
            ]);
            
        } catch (\Exception $e) {
            Factory::response()->sendError($e->getMessage(), 400);
        }	
    }

    function signup()
    {
        if($_SERVER['REQUEST_METHOD']!='POST')
            exit;
            
        try {
            $data  = Factory::request()->getBody();
            
            if ($data == null)
                Factory::response()->sendError('Invalid JSON',400);

            $conn = $this->getConnection();	
            $u = new UsersModel($conn);

            //	
            if (count($u->filter(['id'],['email', $data['email']]))>0)
                Factory::response()->sendError('Email already exists');
                    

            $missing = $u->getMissing($data);
            if (!empty($missing))
                Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing), 400);

            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            $uid = $u->create($data);
            if (empty($uid))
                Factory::response()->sendError("Error in user registration!");

            if ($u->inSchema(['belongs_to'])){
                $u->update(['belongs_to' => $uid]);
            }

            $ur = new UserRoleModel($conn);
            $id = $ur->create([ 'user_id' => $uid, 'role_id' => 1 ]);  // registered
            
            $r = new RolesModel();
            $registered = $r->getRoleName(1);

            $access  = $this->gen_jwt(['uid' => $u->id, 'roles' => [$registered] ], 'access_token');
            $refresh = $this->gen_jwt(['uid'=> $u->id, 'roles' => [$registered] ], 'refresh_token');

            Factory::response()->send([ 
                                        'access_token'=> $access,
                                        'token_type' => 'bearer', 
                                        'expires_in' => $this->config['access_token']['expiration_time'],
                                        'refresh_token' => $refresh                                         
                                        // 'scope' => 'read write'
                                      ]);
        }catch(\Exception $e){
            Factory::response()->sendError($e->getMessage());
        }	
            
    }

    /* 
    Authorization checkin
    
    @return mixed object | null
    */
    function check() {
      
        $headers = Factory::request()->headers();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        
        if (empty($auth))
            return false;
            
        list($jwt) = sscanf($auth, 'Bearer %s');

        if($jwt != null)
        {
            try{
                // Checking for token invalidation or outdated token
                
                $payload = \Firebase\JWT\JWT::decode($jwt, $this->config['access_token']['secret_key'], [ $this->config['access_token']['encryption'] ]);
                
                if (empty($payload))
                    Factory::response()->sendError('Unauthorized!',401);                     

                if (empty($payload->uid)){
                    Factory::response()->sendError('uid is needed',400);
                }

                if (empty($payload->roles)){
                    Factory::response()->sendError('Undefined roles',400);
                }

                if ($payload->exp < time())
                    Factory::response()->sendError('Token expired',401);
                
                return ($payload);

            } catch (Exception $e) {
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

        return false;
    }
}