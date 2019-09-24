<?php

namespace simplerest\controllers;

use simplerest\core\Controller;
use simplerest\libs\Factory;
use simplerest\libs\Database;
use simplerest\models\UsersModel;
use simplerest\libs\Debug;

/*
    Debería ser un Singletón
*/
class AuthController extends Controller
{

    function __construct()
    { 
        // No estoy enviando los headers ya en ApiController ?
        header('access-control-allow-credentials: true');
        header('access-control-allow-headers: AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2'); 
        header('access-control-allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS'); 
        header('access-control-allow-Origin: *');
        header('content-type: application/json; charset=UTF-8');

        parent::__construct();
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
                'exp' => $time + 60 * $this->config['token_expiration_time'],
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
        
            $token = \Firebase\JWT\JWT::encode($payload, $this->config['jwt_secret_key'],  $this->config['encryption']);
            
            Factory::response()->send(['token'=>$token, 'exp' => $payload['exp']]);
            
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

        
        $headers = Factory::request()->headers();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        try {
            if (empty($auth)){
                Factory::response()->sendError('Authorization not found',400);
            }
                
            list($jwt) = sscanf($auth, 'Bearer %s');
            
            if($jwt)
            {
                try{
                    // Checking for token invalidation or outdated token
                    $data = \Firebase\JWT\JWT::decode($jwt, $this->config['jwt_secret_key'],  [ $this->config['encryption'] ]);
            
                    $time = time();
                    $payload = array(
                        'iat' => $time, 
                        'exp' => $time + 60*$this->config['extended_token_expiration_time'], 
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
                    
                    $token = \Firebase\JWT\JWT::encode($payload, $this->config['jwt_secret_key'],  $this->config['encryption']);
                    
                    Factory::response()->send(['token'=>$token, 'exp' => $payload['exp'] ]);
                    
                } catch (\Exception $e) {
                    /*
                    * the token was not able to be decoded.
                    * this is likely because the signature was not able to be verified (tampered token)
                    */
                    Factory::response()->sendError('Unauthorized',401);
                }	
            }else{
                Factory::response()->sendError('Token not found',400);
            }
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
                    

            $missing = $u::diffWithSchema($data, ['id']);
            if (!empty($missing))
                Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing));

            $data['password'] = sha1($data['password']);

            if (empty($u->create($data)))
                Factory::response()->sendError("Error in user registration!");
            
            $time = time();
            $payload = array(
                'iat' => $time, 
                'exp' => $time + 60 * $this->config['token_expiration_time'],
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
            
            $token = \Firebase\JWT\JWT::encode($payload, $this->config['jwt_secret_key'],  $this->config['encryption']);
            
            Factory::response()->send(['token'=>$token, 'exp' => $payload['exp'] ]);

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

        if($jwt)
        {
            try{
                // Checking for token invalidation or outdated token
                
                $data = \Firebase\JWT\JWT::decode($jwt, $this->config['jwt_secret_key'], [ $this->config['encryption'] ]);
                
                if (empty($data))
                    Factory::response()->sendError('Unauthorized',401);
                
                if ($data->exp<time())
                    Factory::response()->sendError('Token expired',401);
                
                return ($data);

            } catch (Exception $e) {
                /*
                * the token was not able to be decoded.
                * this is likely because the signature was not able to be verified (tampered token)
                *
                * reach this point if token is empty or invalid
                */
                Factory::response()->sendError('Unauthorized',401);
            }	
        }else{
            Factory::response()->sendError('Authorization not found',400);
        }
    }



}