<?php
declare(strict_types=1);

include_once 'core/api_controller.php';

class UsersController extends \Core\ApiController
{     
    private $hidden = ['password'];
    
    function __construct()
    {
        parent::__construct();
    }
        
} // end class
