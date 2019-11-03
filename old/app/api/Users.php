<?php

namespace simplerest\api;

use simplerest\controllers\MyApiController; 

class Users extends MyApiController
{     
    protected $scope = [
        'guest'      => [], 
        'registered' => ['read', 'write'],
        'basic'      => ['read'],
        'regular'    => ['read', 'write']
    ];

    function __construct()
    {
        parent::__construct();
    }
        
} // end class
