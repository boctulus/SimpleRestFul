<?php

namespace simplerest\controllers;

use simplerest\core\Request;
use simplerest\libs\Database;
use simplerest\models\UsersModel;
use simplerest\libs\Factory;
use simplerest\libs\Debug;
use simplerest\core\Controller;

define('GOOGLE_CLIENT_ID', '228180780767-4p8t6nvocukmu44ti57o60n1ck6sokpd.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'JByioBo6mRiVBkhW3ldylYKD');
define('GOOGLE_REDIRECT_URL', '/google');

// fusionar con LoginController
class GoogleController extends Controller
{
    protected $client;

    function __construct()
    {
        parent::__construct();

        $client = new \Google_Client();
        $client->setApplicationName('App Name');
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URL);

        $client->setScopes('https://www.googleapis.com/auth/userinfo.email');
        #$client->addScope("https://www.googleapis.com/auth/drive");
        ## these two lines is important to get refresh token from google api
        $client->setAccessType('offline');
        // Set this to force to consent form to display.
        $client->setApprovalPrompt('force');
        $client->setIncludeGrantedScopes(true);

        $this->client = $client;
    }    

    function index(){        
		$this->view('google_login.php', ['client'=>$this->client, 'redirect_uri' => GOOGLE_REDIRECT_URL]);
	}
	
}