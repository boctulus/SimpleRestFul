<?php

namespace simplerest\controllers\api;

use simplerest\controllers\MyApiController; 

class Permissions extends MyApiController
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
