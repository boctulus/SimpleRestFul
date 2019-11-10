<?php

namespace simplerest\controllers;

use Exception;
use simplerest\core\Controller;
use simplerest\core\interfaces\IAuth;
use simplerest\libs\Factory;
use simplerest\libs\Database;
use simplerest\models\UsersModel;
use simplerest\models\RolesModel;
use simplerest\models\UserRolesModel;
use simplerest\libs\Debug;


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
       
    protected function afterRegister(){}

    function addMustHave(array $conditions, $http_code, $msg) {
        $this->must_have[] = [ $conditions , $http_code, $msg ];
    }

    function addMustNotHave(array $conditions, $http_code, $msg) {
        $this->must_not[]  = [ $conditions , $http_code, $msg ];
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
                Factory::response()->sendError('Incorrect verb ('.$_SERVER['REQUEST_METHOD'].'), expecting POST',405);
            break;	
        }	
        
        if (empty($email)){
            Factory::response()->sendError('email is required',400);
        }else if (empty($password)){
            Factory::response()->sendError('password is required',400);
        }
              
        $conn = $this->getConnection();
        
        $u = new UsersModel($conn);
        $rows = $u->unhide(['password'])->where(['email'=> $email])->get();

        if (count($rows) ==0)    
            Factory::response()->sendError('Incorrect email or password', 401);
 
        $hash = $rows[0]['password'];

        if (!password_verify($password, $hash))
            Factory::response()->sendError('Incorrect email or password', 401);

        $confirmed_email = $rows[0]['confirmed_email'];

        // Fetch roles
        $uid = $rows[0]['id'];
        $rows = Database::table('user_roles')->where(['user_id', $uid])->get(['role_id as role']);	
        
        $r = new RolesModel();

        $roles = [];
        if (count($rows) != 0){            
            foreach ($rows as $row){
                $roles[] = $r->getRoleName($row['role']);
            }
        }else
            $roles[] = 'registered';

        $access  = $this->gen_jwt(['uid' => $uid, 'roles' => $roles, 'confirmed_email' => $confirmed_email], 'access_token');
        $refresh = $this->gen_jwt(['uid' => $uid, 'roles' => $roles, 'confirmed_email' => $confirmed_email], 'refresh_token');

        Factory::response()->send([ 
                                    'access_token'=> $access,
                                    'token_type' => 'bearer', 
                                    'expires_in' => $this->config['access_token']['expiration_time'],
                                    'refresh_token' => $refresh                                         
                                    // 'scope' => 'read write'
                                    ]);
            
        
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

            $access  = $this->gen_jwt(['uid' => $payload->uid, 'roles' => $payload->roles, 'confirmed_email' => $payload->confirmed_email], 'access_token');

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

    function register()
    {
        if($_SERVER['REQUEST_METHOD']!='POST')
            exit;
            
        try {
            $data  = Factory::request()->getBody();
            
            if ($data == null)
                Factory::response()->sendError('Invalid JSON',400);

            if (!isset($data['email']) || empty($data['email']))
                Factory::response()->sendError('Email must be provided', 400);

            $conn = $this->getConnection();	
            $u = new UsersModel($conn);

            // exits
            if (count($u->where(['email', $data['email']])->get(['id']))>0)
                Factory::response()->sendError('Email already exists');                    

            $missing = $u->getMissing($data);
            if (!empty($missing))
                Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing), 400);

            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            $uid = $u->create($data);
            if (empty($uid))
                Factory::response()->sendError("Error in user registration!", 500, 'Error creating user');

            $u = Database::table('users');    
            if ($u->inSchema(['belongs_to'])){
                $affected = $u->where(['id', $uid])->update(['belongs_to' => $uid]);
            }

            if (!empty($this->config['registration_role'])){
                $role = $this->config['registration_role'];

                $r  = new RolesModel();
                $ur = new UserRolesModel($conn);

                $ur_id = $ur->create([ 'user_id' => $uid, 'role_id' => $r->get_role_id($role) ]);  

                if (empty($ur_id))
                    Factory::response()->sendError("Error in user registration!", 500, 'Error registrating user role');  
            }
        
            $access  = $this->gen_jwt(['uid' => $uid, 'roles' => [$role], 'confirmed_email' => 0 ], 'access_token');
            $refresh = $this->gen_jwt(['uid' => $uid, 'roles' => [$role], 'confirmed_email' => 0 ], 'refresh_token');

            // Email confirmation
            $exp = time() + $this->config['email']['expires_in'];	

            $base_url =  HTTP_PROTOCOL . '://' . $_SERVER['HTTP_HOST'];
    
            $token = $this->gen_jwt2($data['email'], $this->config['email']['secret_key'], $this->config['email']['encryption'], $this->config['email']['expires_in'] );
            $url = $base_url . '/login/confirm_email/' . $token . '/' . $exp; 
    
            // Queue email
            $ok = (bool) Database::table('messages')->create([
                'from_email' => $this->config['email']['mailer']['from'][0],
                'from_name' => $this->config['email']['mailer']['from'][1],
                'to_email' => $data['email'], 
                'to_name' => $data['firstname'].' '.$data['lastname'], 
                'subject' => 'Confirmación de correo', 
                'body' => "Por favor confirme su correo siguiendo el enlace:<br/><a href='$url'>$url</a>"
            ]);

            if (!$ok)
                Factory::response()->sendError("Error in user registration!", 500, 'Error during registration of email confirmation');
            

            Factory::response()->setQuit(false)->send([ 
                                        'access_token'=> $access,
                                        'token_type' => 'bearer', 
                                        'expires_in' => $this->config['access_token']['expiration_time'],
                                        'refresh_token' => $refresh,
                                        'email_confirmation' => ['url' => $url]
                                        // 'scope' => 'read write'
                                      ]);

            $this->afterRegister();

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

                if (empty($payload->uid))
                    Factory::response()->sendError('uid is needed',400);                

                if (empty($payload->roles)){
                    Factory::response()->sendError('Undefined roles',400);
                }

                if ($payload->exp < time())
                    Factory::response()->sendError('Token expired',401);

                // Overwrite
                if (!$payload->confirmed_email)
                    $payload->roles = ['registered'];
                
                return ($payload);

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

        return false;
    }

    
}