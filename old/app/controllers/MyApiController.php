<?php

namespace simplerest\controllers;

use simplerest\core\ApiController; 

class MyApiController extends ApiController
{
    protected $folder_field;
    protected $guest_root_access;
    protected $soft_delete = true;
    
    // ACL   
    protected $scope = [
        'guest'      => [],  
        'registered' => [],
        'basic'      => ['read'],
        'regular'    => ['read', 'write']
    ];

    function __construct()
    {
        // CORS
        $headers = [
            'access-control-allow-Origin' => '*'
        ];
   
        $auth = new \simplerest\controllers\AuthController();
        parent::__construct($headers, $auth);
    }

}