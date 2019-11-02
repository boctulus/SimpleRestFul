<?php

namespace simplerest\controllers;

use Exception;
use simplerest\core\Controller;
use simplerest\models\UsersModel;
use simplerest\models\UserRoleModel;
use simplerest\models\RolesModel;
use simplerest\libs\Debug;

class FacebookController extends Controller
{
    protected $client;

    function __construct()
    {
        parent::__construct();

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
    
    function login_or_register()
    {
       // ..
    }        

	
}