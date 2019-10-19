<?php

namespace simplerest\controllers;

use simplerest\core\ApiController; 

class MyApiController extends ApiController
{
    protected $folder_field;
    protected $guest_root_access;
    protected $soft_delete = true;
    
    // ALC   
    protected $scope = [
        'guest'      => [],  
        'registered' => [],
        'basic'      => ['read'],
        'regular'    => ['read', 'write'],
        'admin'      => ['read', 'write']
    ];

    function __construct()
    {
        // CORS
        $headers = [
            'access-control-allow-Origin' => '*'
        ];
   
        $auth = new \simplerest\controllers\AuthController();
        $auth->addMustHave([ 'enabled' => 1 ], 403, 'Usuario no habilitado');
        $auth->addMustNotHave([ 'quota' => 0 ], 403, 'Quota exceded');      
        
        parent::__construct($headers, $auth);
    }

}