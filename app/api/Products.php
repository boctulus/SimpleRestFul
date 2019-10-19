<?php

namespace simplerest\api;

use simplerest\controllers\MyApiController; 

class Products extends MyApiController
{ 
    protected $folder_field = 'workspace';
    protected $guest_root_access = false;

    function __construct()
    {       
        $this->scope['guest'] = ['read'];
        $this->scope['registered'] = ['read'];  
        parent::__construct();
    }

        
} // end class
