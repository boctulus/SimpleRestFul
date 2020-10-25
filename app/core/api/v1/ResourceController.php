<?php

namespace simplerest\core\api\v1;

use simplerest\libs\Debug;
use simplerest\core\Request;
use simplerest\libs\Factory;
use simplerest\core\Controller;
use simplerest\core\api\v1\AuthController;
use simplerest\models\FolderPermissionsModel;
use simplerest\models\FolderOtherPermissionsModel;


abstract class ResourceController extends Controller
{
    protected $acl;
    protected $auth;
    protected $uid;
    protected $roles = [];
    protected $permissions = [];


    protected $headers = [
        'Access-Control-Allow-Headers' => 'Authorization,Content-Type', 
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET,POST,DELETE,PUT,PATCH,HEAD,OPTIONS',
        'Access-Control-Allow-Credentials' => 'true',
        'Content-Type' => 'application/json; charset=UTF-8'
    ];

    function __construct($auth = null)
    {   
        foreach ($this->headers as $key => $header){
            header("$key: $header");
        } 
        
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            Factory::response()->sendOK(); // no tocar !
        }

        $this->acl = Factory::acl();

        if (!Factory::request()->hasAuth()){;
            $this->roles = [$this->acl->getGuest()];
            $this->permissions = [];
        } else {

            $this->auth = $auth != null ? $auth : (new AuthController())->check();

            $this->uid          = $this->auth['uid']; 
            $this->roles        = $this->auth['roles'];
            $this->permissions  = $this->auth['permissions'] ?? NULL;   
        }

        //Debug::dd($this->uid, 'uid');
        //Debug::dd($this->acl->getRoleName(), 'possible roles');  ///// 
        //Debug::dd($this->roles, 'active roles');
        //Debug::dd($this->permissions, 'permissions');

        Factory::response()->asObject();

        parent::__construct();
    }

    protected function getRoles(){
        return $this->roles;
    }
    
    protected function getPermissions(string $table = NULL){
        if (empty($this->permissions)){
            return NULL;
        }

        $tb_perms = $this->permissions['tb'];

        if ($table == NULL)
            return $tb_perms;

        if (!isset($tb_perms[$table]))
            return NULL;

        return $tb_perms[$table];
    }

    protected function isGuest(){
        return $this->roles == [Factory::acl()->getGuest()];
    }

    protected function isRegistered(){
        return !$this->isGuest();
    }

    protected function hasRole(string $role){
        return in_array($role, $this->roles);
    }

    protected function hasAnyRole(array $authorized_roles){
        $authorized = false;
        foreach ((array) $this->roles as $role)
            if (in_array($role, $authorized_roles))
                $authorized = true;

        return $authorized;        
    }

    /**
     * hasFolderPermission
     *
     * @param  int    $folder
     * @param  string $operation
     *
     * @return bool
     */
    protected function hasFolderPermission(int $folder, string $operation)
    {
        if ($operation != 'r' && $operation != 'w')
            throw new \InvalidArgumentException("Invalid operation '$operation'. It should be 'r' or 'w'.");

        $o = (new FolderOtherPermissionsModel($this->conn))->assoc();

        $rows = $o->where(['folder_id', $folder])->get();

        $r = $rows[0]['r'] ?? null;
        $w = $rows[0]['w'] ?? null;

        if ($this->isGuest()){
            $guest_role = $this->acl->getGuest();
            $r = $r && $rows[0][$guest_role];
            $w = $w && $rows[0][$guest_role];
        }

        if (($operation == 'r' && $r) || ($operation == 'w' && $w)) {
            return true;
        }
        
        $g = (new FolderPermissionsModel($this->conn))->assoc();
        $rows = $g->where([
                                    ['folder_id', $folder], 
                                    ['access_to', $this->uid]
        ])->get();

        $r = $rows[0]['r'] ?? null;
        $w = $rows[0]['w'] ?? null;

        if (($operation == 'r' && $r) || ($operation == 'w' && $w)) {
            return true;
        }

        return false;
    } 
    
    
    
}  