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


	
}