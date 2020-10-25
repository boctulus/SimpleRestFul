<?php

namespace simplerest\controllers\api;

use simplerest\controllers\MyApiController;
use simplerest\libs\Factory;


class UserRoles extends MyApiController
{    
    protected $table_name = 'user_roles';

    function __construct()
    {
        if (Factory::request()->hasAuth() && $this->isRegistered()){
            $this->callable = ['get'];

            $this->is_retrievable   = true;
            $this->is_listable      = true;
        }

        parent::__construct();
    }

    function get($id = null){
        parent::get($id);
    } 

    function onGettingAfterCheck($id){
        if (!Factory::acl()->hasSpecialPermission('grant', $this->roles) && 
            !Factory::acl()->hasSpecialPermission('read_all', $this->roles)) {
            $this->instance->where(['user_id' => $this->auth['uid']]); 
        }
    }

    function post($id = NULL)
    { 
        if (!Factory::acl()->hasSpecialPermission('grant', $this->roles)){
            Factory::response()->sendError('You are no allowed to change roles', 403);
        }

        parent::post($id);
    } //
    
    function put($id = NULL)
    { 
        if (!Factory::acl()->hasSpecialPermission('grant', $this->roles)){
            Factory::response()->sendError('You are no allowed to change roles', 403);
        }

        parent::put($id);
    } //

    function patch($id = NULL)
    { 
        if (!Factory::acl()->hasSpecialPermission('grant', $this->roles)){
            Factory::response()->sendError('You are no allowed to change roles', 403);
        }

        parent::patch($id);
    } //


    function delete($id = NULL)
    { 
        if (!Factory::acl()->hasSpecialPermission('grant', $this->roles)){
            Factory::response()->sendError('You are no allowed to change roles', 403);
        }

        parent::delete($id);
    } //
        
} // end class
