<?php

namespace simplerest\controllers;

use simplerest\core\ApiController; 

class MyApiController extends ApiController
{
    protected $folder_field;
    
    // ALC   
    protected $scope = [
        'guest'   => ['read'],  // no envia bearer token
        'basic'   => ['read'],
        'regular' => ['read', 'write'],
        'admin'   => ['read', 'write']
    ];

    function __construct()
    {
        parent::__construct();
    }

}