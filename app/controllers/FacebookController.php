<?php

namespace simplerest\controllers;

use Exception;
use simplerest\core\Controller;
use simplerest\models\UsersModel;
use simplerest\models\UserRoleModel;
use simplerest\models\RolesModel;
use simplerest\libs\Debug;

define('APP_ID', '533640957216135');
define('APP_SECRET', '234a9cf42e8710ed813d45ed9e0fb212');
define('CALLBACK', 'https://simplerest.co/login/fb_callback');

class FacebookController extends Controller
{
    protected $client;

    function __construct()
    {
        parent::__construct();

        $fb = new \Facebook\Facebook([
            'app_id' => APP_ID, 
            'app_secret' => APP_SECRET,
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
    
    function login_or_register()
    {
       // ..
    }        

	
}