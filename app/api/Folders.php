<?php

namespace simplerest\api;

use simplerest\controllers\MyApiController;

class Folders extends MyApiController
{     
    protected $scope = [
        'guest'   => [ ],  
        'basic'   => ['read'],
        'regular' => ['read', 'write'],
        'admin'   => ['read', 'write']
    ];

    function __construct()
    {
        parent::__construct();
    }
        
} // end class
