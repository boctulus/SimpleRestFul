<?php

namespace simplerest\controllers\api;

use simplerest\controllers\MyApiController; 

class Foo extends MyApiController
{ 
    protected $scope = [
        'guest'      => [],  
        'registered' => ['read'],
        'basic'      => [],
        'regular'    => []
    ];

    function __construct()
    {       
        parent::__construct();
    }

        
} // end class
