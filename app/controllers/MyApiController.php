<?php

namespace simplerest\controllers;

use simplerest\core\ApiController; 

class MyApiController extends ApiController
{
    protected $folder_field;
    protected $guest_root_access;
    
    // ALC   
    protected $scope = [
        'guest'   => ['read'],  
        'basic'   => ['read'],
        'regular' => ['read', 'write'],
        'admin'   => ['read', 'write']
    ];

    function __construct()
    {
        parent::__construct();
    }

}