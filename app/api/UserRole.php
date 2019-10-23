<?php

namespace simplerest\api;

use simplerest\controllers\MyApiController;

class UserRole extends MyApiController
{     
    protected $scope = [
        'guest'      => [], 
        'registered' => [],
        'basic'      => [],
        'regular'    => []
    ];

    function __construct()
    {
        parent::__construct();
    }
        
} // end class
