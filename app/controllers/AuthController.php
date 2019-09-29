<?php

namespace simplerest\controllers;

use Exception;
use simplerest\core\Controller;
use simplerest\core\interfaces\IAuth;
use simplerest\libs\Factory;
use simplerest\models\UsersModel;
use simplerest\models\SessionsModel;
use simplerest\libs\Debug;
use simplerest\libs\crypto\SaferCrypto;

/*
    Debería ser un Singletón
*/
class AuthController extends Controller implements IAuth
{
    protected $must_have = [];
    protected $must_not  = [];
    protected $user_role = 'default';

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

    function addmust_not(array $conditions, $http_code, $msg) {
        $this->must_not[]  = [ $conditions , $http_code, $msg ];
    }
    
    function get_role(){ 
        return $this->user_role;
    }

    /*
        Refresh token generator
    */
    protected function pass_gen(){
        $key = hex2bin($this->config['refresh_secret_key']);    
        
        $refresh='';
        for ($i=0;$i<10;$i++){
            //$refresh .= chr(rand(32,47));
            $refresh .= chr(rand(58,64));
            $refresh .= chr(rand(65,90));
            //$refresh .= chr(rand(91,96));
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

    protected function session_encrypt($sid){
        $key = hex2bin($this->config['session_secret_key']); 
        return SaferCrypto::encrypt($sid, $key, true);
    }

    protected function session_decrypt($encrypted){
        $key = hex2bin($this->config['session_secret_key']); 
        return SaferCrypto::decrypt($encrypted, $key, true);
    }

    protected function gen_jwt($encoded_sid){
        $time = time();
        $payload = [
            'alg' => $this->config['encryption'],
            'typ' => 'JWT',
            'iat' => $time, 
            'exp' => $time + $this->config['token_expiration_time'],
            'ip' => [
                'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? '',
                'HTTP_CLIENT_IP' => $_SERVER['HTTP_CLIENT_IP'] ?? '',
                'HTTP_FORWARDED' => $_SERVER['HTTP_FORWARDED'] ?? '',
                'HTTP_FORWARDED_FOR' => $_SERVER['HTTP_FORWARDED_FOR'] ?? '',
                'HTTP_X_FORWARDED' => $_SERVER['HTTP_X_FORWARDED'] ?? '',
                'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''
            ],
            'sid' => $encoded_sid
        ];
        
        return \Firebase\JWT\JWT::encode($payload, $this->config['jwt_secret_key'],  $this->config['encryption']);
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
        
        if ($u->checkUserAndPass()){

            list($refresh, $encrypted) = $this->pass_gen();

            $session = new SessionsModel($conn);
            $sid = $session->create([ 'refresh_token' => $encrypted, 'login_date' => time(), 'user_id' => $u->id ]);
                
            if (!$sid)
                Factory::response()->sendError("Authentication fails", 401); 

            $sid_enc = $this->session_encrypt($sid);                  
            $jwt = $this->gen_jwt($sid_enc);

            Factory::response()->send([ 
                                        'sid' => $sid_enc,
                                        'access_token'=> $jwt,
                                        'token_type' => 'bearer', 
                                        'refresh_token' => $refresh,
                                        'expires_in' => $this->config['token_expiration_time'] 
                                        // 'scope' => 'read write'
                                      ]);
            
        }else
            Factory::response()->sendError("User or password are incorrect", 401);
    }


    /*
        Access Token renewal
        
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

        $sid_enc = $request->getBodyParam('sid');
        $headers = $request->headers();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (empty($auth)){
            Factory::response()->sendError('Authorization not found',400);
        }

        if (empty($sid_enc)){
            Factory::response()->sendError('sid is needed !',400);
        }

        try {                                      
            // refresh token
            list($refresh) = sscanf($auth, 'Basic %s');

            if(empty($refresh))
                Factory::response()->sendError('Token not found',400);
            
            $sid = $this->session_decrypt($sid_enc);
           
            $conn = $this->getConnection();            
            $s = new SessionsModel($conn);
            $rows = $s->filter(null, ['id' => $sid]);

            if(empty($rows))
                Factory::response()->sendError('Session not found', 400);

            if($this->pass_dec($rows[0]['refresh_token']) != $refresh) 
                Factory::response()->sendError('Refresh token is invalid',400);

            $jwt = $this->gen_jwt($sid_enc);
            
            ///////////
            Factory::response()->send([ 
                                        'access_token'=> $jwt,
                                        'token_type' => 'bearer', 
                                        'expires_in' => $this->config['token_expiration_time'] 
                                        // 'scope' => 'read write'
            ]);
            
        } catch (\Exception $e) {
            Factory::response()->sendError($e->getMessage(), 400);
        }	
    }

    /*
        'refresh token' destructor 
    */
    function logout()
    {  
        if ($_SERVER['REQUEST_METHOD']=='OPTIONS'){
            // passs
            Factory::response()->send('OK',200);
        }elseif ($_SERVER['REQUEST_METHOD']!='POST')
            Factory::response()->sendError('Incorrect verb',405);

        $sid_enc = Factory::request()->getBodyParam('sid');    

        if (empty($sid_enc)){
            Factory::response()->sendError('sid is needed !',400);
        }

        $sid = $this->session_decrypt($sid_enc);
           
        $conn = $this->getConnection();            
        $s = new SessionsModel($conn);
        $s->id = $sid;
        $ok = $s->delete();

        if ($ok)
            Factory::response()->send('OK - session was deleted',200);
        else
            Factory::response()->sendCode(500);    
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
            if (count($u->filter(['id'],['email'=>$data['email']]))>0)
                Factory::response()->sendError('Email already exists');
                    

            $missing = $u::diffWithSchema($data, ['id','enabled','quota']);
            if (!empty($missing))
                Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing));

            $data['password'] = sha1($data['password']);

            list($refresh, $encrypted) = $this->pass_gen();

            $uid = $u->create($data);
            if (empty($uid))
                Factory::response()->sendError("Error in user registration!");
            
            $session = new SessionsModel($conn);
            $sid = $session->create([ 'refresh_token' => $encrypted, 'login_date' => time(), 'user_id' => $uid ]);
            $sid_enc = $this->session_encrypt($sid);

            $jwt = $this->gen_jwt($sid_enc);

            //////////////
            Factory::response()->send([ 
                                        'sid' => $sid_enc,
                                        'access_token'=> $jwt,
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

        $req = Factory::request();        
        $headers = $req->headers();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        
        if (empty($auth)){
            Factory::response()->sendError('Authorization not found',400);
        }
            
        list($jwt) = sscanf($auth, 'Bearer %s');

        if($jwt != null)
        {
            try{
                // Checking for token invalidation or outdated token
                
                $payload = \Firebase\JWT\JWT::decode($jwt, $this->config['jwt_secret_key'], [ $this->config['encryption'] ]);
                
                if (empty($payload))
                    Factory::response()->sendError('Unauthorized!',401);                     

                if (empty($payload->sid)){
                    Factory::response()->sendError('sid is needed',400);
                }

                if ($payload->exp < time())
                    Factory::response()->sendError('Token expired',401);
                    

                $conn = $this->getConnection();

                $s = new SessionsModel($conn);
                $rows = $s->filter(null, ['id' => $this->session_decrypt($payload->sid)]);

                if(empty($rows))
                    Factory::response()->sendError('Session not found', 400);

                $u = new UsersModel($conn);
                $u->id = $rows[0]['user_id'];
                $u->fetchWithRole();

                $this->user_role = $u->role_name;


                if (count($this->must_have) > 0 || count($this->must_not) > 0) {
          
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
    }
}