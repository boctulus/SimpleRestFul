<?php

namespace simplerest\controllers;

use Exception;
use simplerest\core\Controller;
use simplerest\models\UsersModel;
use simplerest\models\UserRoleModel;
use simplerest\models\RolesModel;
use simplerest\libs\Debug;

class GoogleController extends Controller
{
    protected $client;

    function __construct()
    {
        parent::__construct();

        $client = new \Google_Client();
        $client->setApplicationName('App Name');
        $client->setClientId($this->config['google_auth']['client_id']);
        $client->setClientSecret($this->config['google_auth']['client_secret']);
        $client->setRedirectUri($this->config['google_auth']['callback']);

        $client->setScopes('https://www.googleapis.com/auth/userinfo.email');
        #$client->addScope("https://www.googleapis.com/auth/drive");
        ## these two lines is important to get refresh token from google api
        $client->setAccessType('offline');
        // Set this to force to consent form to display.
        $client->setApprovalPrompt('force');
        $client->setIncludeGrantedScopes(true);

        $this->client = $client;
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
        if (isset($_GET['code'])) {
            $auth = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);

            if (isset($auth['error']))
                return ['error' => $auth['error_description'], 'code' => 400];

            $this->client->setAccessToken($auth);
        }else
            return ['error' => 'Invalid', 'code' => 400];

        /*
        if ($this->client->isAccessTokenExpired() )    
            $auth = $this->client->getAccessToken();

            $access_token = $auth['access_token'];
            //
            ...
        }
        */

        //Debug::debug($auth);        
        /*
            Array
            (
                [access_token] => ya29.Il-iB22898tm...........

                [expires_in] => 3599

                [refresh_token] => 1//0hMcct5o95Tf-Cg.........
                
                [scope] => https://www.googleapis.com/auth/userinfo.email openid https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/drive
                
                [token_type] => Bearer
                
                [id_token] => eyJhbGciOiJSUzI1NiIsImtpZCI6IjhhNjNmZTcxZTUzMDY3NTI0Y2JiYzZhM2E1ODQ2M2IzODY0YzA3ODciLCJ0eXAiOiJKV1QifQ............
                
                [created] => 1571713360)
        */

        $payload = $this->client->verifyIdToken($auth['id_token'], GOOGLE_CLIENT_ID);
        /*
        array(14) { 
            ["iss"]=> string(27) "https://accounts.google.com" 
            ["azp"]=> string(72) "228180780767-4p8t6nvocukmu44ti57o60n1ck6sokpd.apps.googleusercontent.com" 
            ["aud"]=> string(72) "228180780767-4p8t6nvocukmu44ti57o60n1ck6sokpd.apps.googleusercontent.com" 
            ["sub"]=> string(21) "100334249456746149636" 
            ["email"]=> string(18) "boctulus@gmail.com" 
            ["email_verified"]=> bool(true) 
            ["at_hash"]=> string(22) "3nV4aKuMu_FNft90PLjXjg" ["name"]=> string(13) "Pablo Bozzolo" ["picture"]=> string(89) "https://lh3.googleusercontent.com/a-/AAuE7mATeEoHq5modzihrb_z1O-3_BhxhJ4Q9dXSKGtxNw=s96-c" 
            ["given_name"]=> string(5) "Pablo" 
            ["family_name"]=> string(7) "Bozzolo" 
            ["locale"]=> string(5) "en-GB" 
            ["iat"]=> int(1571714620) 
            ["exp"]=> int(1571718220) } 
        */

        //Debug::debug($payload);
        
        if (!$payload)
            exit;

        try 
        {        
            $conn = $this->getConnection();	
            $u = new UsersModel($conn);

            // exits	
            $rows = $u->where(['email', $payload['email']])->get(['id']);
            if (count($rows)>0){
                // Email already exists
                $uid = $rows[0]['id'];

                $ur = new UserRoleModel($conn);
                $rows = $ur->where(['user_id', $uid])->get(['role_id']);

                $roles = [];
                if (count($rows) > 0){         
                    $r = new RolesModel();           
                    foreach ($rows as $row){
                        $roles[] = $r->getRoleName($row['role_id']);
                    }
                }
            }else{
                $data['email'] = $payload['email'];
                $data['firstname'] = $payload['given_name'] ?? NULL;
                $data['lastname'] = $payload['family_name'] ?? NULL;
                
                $uid = $u->create($data);
                if (empty($uid))
                    return ['error' => 'Error in user registration!', 'code' => 500];
    
                if ($u->inSchema(['belongs_to'])){
                    $u->where(['id', $u->id]);
                    $u->update(['belongs_to' => $uid]);
                }
    
                $ur = new UserRoleModel($conn);
                $id = $ur->create([ 'user_id' => $uid, 'role_id' => 1 ]);  // registered     
                
                $r = new RolesModel();
                $registered = $r->getRoleName(1);
                $roles = [$registered];
            }  
            

            $my_payload = [
                'uid' => $uid, 
                'roles' => $roles
                //'google_auth' => $auth
            ];

            $access  = $this->gen_jwt($my_payload, 'access_token');
            $refresh = $this->gen_jwt($my_payload, 'refresh_token');

            return ['code' => 200,  
                    'data' => [ 
                                'access_token'=> $access,
                                'token_type' => 'bearer', 
                                'expires_in' => $this->config['access_token']['expiration_time'],
                                'refresh_token' => $refresh                                         
                                // 'scope' => 'read write'
                    ],
                    'error' => ''
            ];
        }catch(\Exception $e){
            return ['error' => $e->getMessage(), 'code' => 500];
        }	

    }        

	
}