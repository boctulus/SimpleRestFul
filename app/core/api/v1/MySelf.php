<?php

namespace simplerest\core\api\v1;

use simplerest\controllers\MyApiController; 
use simplerest\core\interfaces\IAuth;
use simplerest\libs\Factory;
use simplerest\libs\Arrays;
use simplerest\libs\DB;
use simplerest\libs\Debug;
use simplerest\libs\Url;
use simplerest\libs\Validator;
use simplerest\core\exceptions\InvalidValidationException;

class MySelf extends MyApiController 
{  
    protected $model_name = 'UsersModel';

    function __construct() 
    { 
        if (Factory::request()->hasAuth()){
            $this->callable = ['get', 'put', 'patch', 'delete'];

            $this->is_listable = true;
            $this->is_retrievable = true;
        } else {
            Factory::response()->sendError("Forbidden", 403, "You need to be authenticated");
        }
        
        parent::__construct();
    }

    function get($id = null){
        $id = $this->auth['uid'];
        parent::get($id);
    } 

    function put($id = NULL)
    { 
        $id = $this->auth['uid'];
        parent::put($id);
    } //

    function patch($id = NULL)
    { 
        $id = $this->auth['uid'];
        parent::patch($id);
    } //
        
    function delete($id = null){
        $id = $this->auth['uid'];

        $ok = (bool) DB::table('users')->where([['id', $id], ['active', 1]])
            ->fill(['active'])
        ->update(['active' => 0]);

        if ($ok) {
            Factory::response()->send("Your account was succesfully disabled");
        } else {
            Factory::response()->send("An error has ocurred trying to disable your account.");
        }        
    } // 
       
    
}  