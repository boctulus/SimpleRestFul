<?php

namespace simplerest\core;

use simplerest\core\ResourceController;
use simplerest\core\Request;
use simplerest\libs\Factory;
use simplerest\models\RolesModel;
use simplerest\controllers\AuthController;

abstract class ResourceController extends Controller
{
    protected $auth_payload;
    protected $roles;
    protected $uid;
    protected $is_admin;

    function __construct()
    {
        if (Factory::request()->header('Authorization') == NULL){
            $this->uid = null;
            $this->is_admin = false;
            $this->roles = ['guest'];
        }

        $this->auth_payload = (new AuthController())->check();
            
        if (!empty($this->auth_payload)){
            $this->uid = $this->auth_payload->uid; 

            $r = new RolesModel();
            $this->roles  = $this->auth_payload->roles;              

            $this->is_admin = false;
            foreach ($this->roles as $role){
                if ($r->is_admin($role)){
                    $this->is_admin = true;
                    break;
                }
            }                
        }else{
            $this->uid = null;
            $this->is_admin = false;
            $this->roles = ['guest'];
        }
                    
        parent::__construct();
    }

    public function isGuest(){
        return $this->roles == ['guest'];
    }

    public function isAdmin(){
        return $this->is_admin;
    }

    public function hasRole(string $role){
        return in_array($role, $this->roles);
    }

    public function hasAnyRole(array $authorized_roles){
        $authorized = false;
        foreach ((array) $this->roles as $role)
            if (in_array($role, $authorized_roles))
                $authorized = true;

        return $authorized;        
    }
    
    
}  