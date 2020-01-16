<?php

namespace simplerest\controllers\api;

use simplerest\controllers\MyApiController;

class UserRoles extends MyApiController
{     
    protected $scope = [
        'guest'      => [], 
        'registered' => ['read'],
        'basic'      => ['read'],
        'regular'    => ['read']
    ];

    function __construct()
    {
        parent::__construct();
    }
        
} // end class
