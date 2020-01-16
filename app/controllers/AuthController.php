<?php

namespace simplerest\controllers;

use Exception;
use simplerest\core\Controller;
use simplerest\core\interfaces\IAuth;
use simplerest\libs\Factory;
use simplerest\libs\DB;
use simplerest\libs\Utils;
use simplerest\models\UsersModel;
use simplerest\models\RolesModel;
use simplerest\models\UserRolesModel;
use simplerest\libs\Debug;
use simplerest\libs\Validator;
use simplerest\core\exceptions\InvalidValidationException;


class AuthController extends Controller implements IAuth
{
    function __construct()
    { 
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-headers: AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2'); 
        header('Access-Control-Allow-Methods: POST,OPTIONS'); 
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=UTF-8');

        parent::__construct();
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

    function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            Factory::response()->sendError('Incorrect verb ('.$_SERVER['REQUEST_METHOD'].'), expecting POST',405);

        $data  = Factory::request()->getBody(false);

        if ($data == null)
            Factory::response()->sendError('Invalid JSON',400);
        
        $email = $data->email ?? null;
        $username = $data->username ?? null;  
        $password = $data->password ?? null;            
        
        if (empty($email) && empty($username) ){
            Factory::response()->sendError('email or username are required',400);
        }else if (empty($password)){
            Factory::response()->sendError('password is required',400);
        }

        try {              

            $row = DB::table('users')->setFetchMode('ASSOC')->unhide(['password'])
            ->where([ 'email'=> $email, 'username' => $username ], 'OR')
            ->setValidator((new Validator())->setRequired(false))  
            ->first();

            $hash = $row['password'];

            if (!password_verify($password, $hash))
                Factory::response()->sendError('Incorrect username / email or password', 401);

            if ($row['active'] == 0)
                Factory::response()->sendError('Non authorized', 403, 'Unactivated or deactivate account');

            $confirmed_email = $row['confirmed_email']; 
            $username = $row['username'];   
    
            // Fetch roles
            $uid = (int) $row['id'];
            $rows = DB::table('user_roles')->setFetchMode('ASSOC')->where(['user_id', $uid])->get(['role_id as role']);	
            
            $r = new RolesModel();

            $roles = [];
            if (count($rows) != 0){            
                foreach ($rows as $row){
                    $roles[] = $r->getRoleName($row['role']);
                }
            }else
                $roles[] = 'registered';


            $_permissions = DB::table('permissions')->setFetchMode('ASSOC')->select(['tb', 'can_create as c', 'can_read as r', 'can_update as u', 'can_delete as d'])->where(['user_id' => $uid])->get();

            $perms = [];
            foreach ($_permissions as $p){
                $tb = $p['tb'];
                $perms[$tb] = $p['c'] * 8 + $p['r'] * 4 + $p['u'] * 2 + $p['d'];
            }

            //var_export($perms);

            $access  = $this->gen_jwt([ 'uid' => $uid, 
                                        'roles' => $roles, 
                                        'confirmed_email' => $confirmed_email,
                                        'permissions' => $perms //
            ], 'access_token');

            $refresh = $this->gen_jwt([ 'uid' => $uid, 
                                        'roles' => $roles, 
                                        'confirmed_email' => $confirmed_email,
                                        'permissions' => $perms //
            ], 'refresh_token');

            Factory::response()->send([ 
                                        'access_token'=> $access,
                                        'token_type' => 'bearer', 
                                        'expires_in' => $this->config['access_token']['expiration_time'],
                                        'refresh_token' => $refresh    
                                        // 'scope' => 'read write'
                                        ]);
          
        } catch (InvalidValidationException $e) { 
            Factory::response()->sendError('Validation Error', 400, json_decode($e->getMessage()));
        } catch(\Exception $e){
            Factory::response()->sendError($e->getMessage());
        }	
        
    }


    /*
        Access Token renewal
    */	
    function token()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            Factory::response()->sendError('Incorrect verb ('.$_SERVER['REQUEST_METHOD'].'), expecting POST',405);

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

            $permissions = $payload->permissions ?? [];

            if ($payload->exp < time())
                Factory::response()->sendError('Token expired, please log in',401);

            $access  = $this->gen_jwt(['uid' => $payload->uid, 'roles' => $payload->roles, 'confirmed_email' => $payload->confirmed_email, 'permissions' => $permissions], 'access_token');

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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            Factory::response()->sendError('Incorrect verb ('.$_SERVER['REQUEST_METHOD'].'), expecting POST',405);
            
        try {
            $data  = Factory::request()->getBody();
            
            if ($data == null)
                Factory::response()->sendError('Invalid JSON',400);

            $u = new UsersModel();

            $missing = $u->getMissing($data);
            if (!empty($missing))
                Factory::response()->sendError('There are missing properties in your request: '.implode(',',$missing), 400);

            // exists
            if (DB::table('users')->where(['email', $data['email']])->exists())
                Factory::response()->sendError('Email already exists');  

            if (DB::table('users')->where(['username', $data['username']])->exists())
                Factory::response()->sendError('Username already exists');

            $uid = DB::table('users')->setValidator(new Validator())->create($data);
            if (empty($uid))
                Factory::response()->sendError("Error in user registration", 500, 'Error creating user');

            $u = DB::table('users');    
            if ($u->inSchema(['belongs_to'])){
                $affected = $u->where(['id', $uid])->update(['belongs_to' => $uid]);
            }

            if (!empty($this->config['registration_role'])){
                $role = $this->config['registration_role'];

                $r  = new RolesModel();
                $ur = DB::table('userRoles');

                $ur_id = $ur->create([ 'user_id' => $uid, 'role_id' => $r->get_role_id($role) ]);  

                if (empty($ur_id))
                    Factory::response()->sendError("Error in user registration", 500, 'Error registrating user role');  
            }else{
                $role = ['registered'];
            }
        
            $permissions = [];

            $access  = $this->gen_jwt(['uid' => $uid, 'roles' => [$role], 'confirmed_email' => 0, 'permissions' => $permissions ], 'access_token');
            $refresh = $this->gen_jwt(['uid' => $uid, 'roles' => [$role], 'confirmed_email' => 0, 'permissions' => $permissions ], 'refresh_token');

            // Email confirmation
            $exp = time() + $this->config['email']['expires_in'];	

            $base_url =  HTTP_PROTOCOL . '://' . $_SERVER['HTTP_HOST'];
    
            $token = $this->gen_jwt2($data['email'], $this->config['email']['secret_key'], $this->config['email']['encryption'], $this->config['email']['expires_in'] );
            $url = $base_url . '/login/confirm_email/' . $token . '/' . $exp; 
    
           
            $firstname = $data['firstname'] ?? null;
            $lastname  = $data['lastname']  ?? null;
            $email  = $data['email']  ?? null;

            $ok = (bool) DB::table('messages')->create([
                'from_email' => $this->config['email']['mailer']['from'][0],
                'from_name' => $this->config['email']['mailer']['from'][1],
                'to_email' => $email, 
                'to_name' => $firstname.' '.$lastname, 
                'subject' => 'Email confirmation', 
                'body' => "Please confirm your account by following the link bellow:<br/><a href='$url'>$url</a>"
            ]);
            

            if (!$ok)
                Factory::response()->sendError("Error in user registration!", 500, 'Error during registration of email confirmation');
            

            Factory::response()->send([ 
                                        'access_token'=> $access,
                                        'token_type' => 'bearer', 
                                        'expires_in' => $this->config['access_token']['expiration_time'],
                                        'refresh_token' => $refresh
                                        // 'scope' => 'read write'
                                      ]);

        } catch (InvalidValidationException $e) { 
            Factory::response()->sendError('Validation Error', 400, json_decode($e->getMessage()));
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
            return NULL;  // prev was false (!)
            
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

                //if (empty($payload->permissions)){
                //    Factory::response()->sendError('Undefined permissions',400);
                //}

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