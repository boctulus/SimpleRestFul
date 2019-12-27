<?php

namespace simplerest\controllers\api;

use simplerest\controllers\MyApiController; 

class Users extends MyApiController
{
    //static protected $owned = false;

    protected $scope = [
        'guest'      => [], 
        'registered' => ['read', 'update', 'delete', 'create'],
        'basic'      => ['read'],
        'regular'    => ['read', 'update', 'delete', 'create']
    ];

    function __construct()
    {
        parent::__construct();
    }
        
} // end class
