<?php

namespace simplerest\controllers\api;

use simplerest\controllers\MyApiController; 

class Products extends MyApiController
{ 
    static protected $folder_field = 'workspace';
    static protected $owned = false;
    //static protected $guest_access = true;

    function __construct()
    {       
        $this->scope['guest']      = ['read'];
        $this->scope['registered'] = ['read'];
        parent::__construct();
    }

        
} // end class
