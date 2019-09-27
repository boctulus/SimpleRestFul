<?php

namespace simplerest\controllers;

use Exception;
use simplerest\core\Controller;
use simplerest\core\interfaces\IAuth;
use simplerest\libs\Factory;
use simplerest\libs\Database;
use simplerest\models\UsersModel;
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
        // No estoy enviando los headers ya en ApiController ?
        header('access-control-allow-credentials: true');
        header('access-control-allow-headers: AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2'); 
        header('access-control-allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS'); 
        header('access-control-allow-Origin: *');
        header('content-type: application/json; charset=UTF-8');
        header("Set-Cookie: hidden=value; httpOnly");  /// ???

        parent::__construct();
    }
       
    function addmust_have(array $conditions, $http_code, $msg) {
        $this->must_have[] = [ $conditions , $http_code, $msg ];
    }

    function addmust_not (array $conditions, $http_code, $msg) {
        $this->must_not[]  = [ $conditions , $http_code, $msg ];
    }

    /*
        Refresh token generator
    */
    protected function pass_gen(){
        $key = hex2bin($this->config['refresh_secret_key']);    
        
        $refresh='';
        for ($i=0;$i<6;$i++){
            $refresh .= chr(rand(32,47));
            $refresh .= chr(rand(58,64));
            $refresh .= chr(rand(65,90));
            $refresh .= chr(rand(91,96));
            $refresh .= chr(rand(97,122));	
            $refresh .= chr(rand(123,126));
        }	
    
        $encrypted = SaferCrypto::encrypt($refresh, $key, true);

        return [ $refresh, $encrypted ];
    }

    protected function pass_dec($encrypted){
        $key = hex2bin($this->config['refresh_secret_key']); 
        return SaferCrypto::decrypt($encrypted, $key, true);
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
              
        $conn = Database::getConnection($this->config['database']);
        
        $u = new UsersModel($conn);
        $u->email = $email;
        $u->password = $password;
        
        if ($u->checkUserAndPass()){
         
            $time = time();
            $payload = array(
                'iat' => $time, 
                'exp' => $time + $this->config['token_expiration_time'],
                'id'  => $u->id,
                'ip' => [
                    'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? '',
                    'HTTP_CLIENT_IP' => $_SERVER['HTTP_CLIENT_IP'] ?? '',
                    'HTTP_FORWARDED' => $_SERVER['HTTP_FORWARDED'] ?? '',
                    'HTTP_FORWARDED_FOR' => $_SERVER['HTTP_FORWARDED_FOR'] ?? '',
                    'HTTP_X_FORWARDED' => $_SERVER['HTTP_X_FORWARDED'] ?? '',
                    'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''
                ]
            );
        
            $token = \Firebase\JWT\JWT::encode($payload, $this->config['jwt_secret_key'],  $this->config['encryption']);            
           
            if (empty($u->refresh_token)){
                list($refresh, $encrypted) = $this->pass_gen();
                $u->update(['refresh_token' => $encrypted]);                         
            } else {
                $refresh = $this->pass_dec($u->refresh_token);
            }           

            Factory::response()->send([ 
                                        'id' => $u->id,
                                        'access_token'=> $token,
                                        'token_type' => 'bearer', 
                                        'refresh_token' => $refresh,
                                        'expires_in' => $this->config['token_expiration_time'] 
                                        // 'scope' => 'read write'
                                      ]);
            
        }else
            Factory::response()->sendError("User or password are incorrect", 401);
    }


    /*
        Token refresh
        
        Only by POST*
    */	
    function token_renew()
    {
        if ($_SERVER['REQUEST_METHOD']=='OPTIONS'){
            // passs
            Factory::response()->send('OK',200);
        }elseif ($_SERVER['REQUEST_METHOD']!='POST')
            Factory::response()->sendError('Incorrect verb',405);


        $request = Factory::request();

        $id = $request->getBodyParam('id');
        $headers = $request->headers();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (empty($auth)){
            Factory::response()->sendError('Authorization not found',400);
        }

        if (empty($id)){
            Factory::response()->sendError('id is needed !',400);
        }

        try {                                      
            // refresh token
            list($refresh) = sscanf($auth, 'Basic %s');

            if(empty($refresh))
                Factory::response()->sendError('Token not found',400);
            
            // Checking for refresh token            
            $conn = Database::getConnection($this->config['database']);

            $u = new UsersModel($conn);
            $u->id = $id;
            $ok = $u->fetch(['refresh_token']); // encrypted

            if (!$ok)
                Factory::response()->sendError('User not found',400);

            if (empty($u->refresh_token))
                Factory::response()->sendError('Refresh token is empty',400);

            if($this->pass_dec($u->refresh_token) != $refresh) 
                Factory::response()->sendError('Refresh token is invalid',400);
                
            $time = time();
            $payload = array(
                'iat' => $time, 
                'exp' => $time + $this->config['token_expiration_time'],
                'id'  => $u->id,
                'ip' => [
                    'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? '',
                    'HTTP_CLIENT_IP' => $_SERVER['HTTP_CLIENT_IP'] ?? '',
                    'HTTP_FORWARDED' => $_SERVER['HTTP_FORWARDED'] ?? '',
                    'HTTP_FORWARDED_FOR' => $_SERVER['HTTP_FORWARDED_FOR'] ?? '',
                    'HTTP_X_FORWARDED' => $_SERVER['HTTP_X_FORWARDED'] ?? '',
                    'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''
                ]
            );
        
            $token = \Firebase\JWT\JWT::encode($payload, $this->config['jwt_secret_key'],  $this->config['encryption']);
            
            ///////////
            Factory::response()->send([ 
                                        'id' => $id,
                                        'access_token'=> $token,
                                        'token_type' => 'bearer', 
                                        'expires_in' => $this->config['token_expiration_time'] 
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

            $conn = Database::getConnection($this->config['database']);	
            $u = new UsersModel($conn);

            //	
            if (count($u->filter(['id'],['email'=>$data['email']]))>0)
                Factory::response()->sendError('Email already exists');
                    

            $missing = $u::diffWithSchema($data, ['id','enabled','quota','refresh_token']);
            if (!empty($missing))
                Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing));

            $data['password'] = sha1($data['password']);

            list($refresh, $encrypted) = $this->pass_gen();
            $data['refresh_token'] = $encrypted;

            $id = $u->create($data);
            if (empty($id))
                Factory::response()->sendError("Error in user registration!");
            
            $time = time();
            $payload = array(
                'iat' => $time, 
                'exp' => $time + $this->config['token_expiration_time'],
                'id'  => $id,
                'ip' => [
                    'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? '',
                    'HTTP_CLIENT_IP' => $_SERVER['HTTP_CLIENT_IP'] ?? '',
                    'HTTP_FORWARDED' => $_SERVER['HTTP_FORWARDED'] ?? '',
                    'HTTP_FORWARDED_FOR' => $_SERVER['HTTP_FORWARDED_FOR'] ?? '',
                    'HTTP_X_FORWARDED' => $_SERVER['HTTP_X_FORWARDED'] ?? '',
                    'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''
                ]
            );

            $token = \Firebase\JWT\JWT::encode($payload, $this->config['jwt_secret_key'],  $this->config['encryption']);
                 
            //////////////
            Factory::response()->send([ 
                                        'id' => $id,
                                        'access_token'=> $token,
                                        'token_type' => 'bearer', 
                                        'refresh_token' => $refresh,
                                        'expires_in' => $this->config['token_expiration_time'] 
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
    function check_auth() {
        $headers = Factory::request()->headers();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        
        if (empty($auth)){
            Factory::response()->sendError('Authorization not found',400);
        }
            
        list($jwt) = sscanf($auth, 'Bearer %s');

        //var_dump($auth);
        //var_dump($jwt);

        if($jwt != null)
        {
            try{
                // Checking for token invalidation or outdated token
                
                $data = \Firebase\JWT\JWT::decode($jwt, $this->config['jwt_secret_key'], [ $this->config['encryption'] ]);
                
                //var_dump($data);

                if (empty($data))
                    Factory::response()->sendError('Unauthorized!',401);                     

                if ($data->exp<time())
                    Factory::response()->sendError('Token expired',401);

                if (count($this->must_have)>0 || count($this->must_not)>0) {
                    $conn    = Database::getConnection($this->config['database']);

                    $u = new UsersModel($conn);
                    $u->id = $data->id;
                    $u->fetch();

                    foreach ($this->must_have as $must){
                        $conditions = $must[0];
                        $code = $must[1];
                        $msg  = $must[2];
        
                        foreach ($conditions as $k => $val){
                            if ($u->$k != $val){
                                Factory::response()->sendError($msg, $code);
                            }
                        }
                    }    

                    foreach ($this->must_not as $not){
                        $conditions = $not[0];
                        $code = $not[1];
                        $msg  = $not[2];
        
                        foreach ($conditions as $k => $val){
                            if ($u->$k == $val){
                                Factory::response()->sendError($msg, $code);
                            }
                        }
                    }    
                }
                
                return ($data);

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
    }



}