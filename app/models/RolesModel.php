<?php

namespace simplerest\models;

use Exception;
use simplerest\core\Model;

class RolesModel {

	protected $roles = [
		2 => 	['name' => 'basic',	 'is_admin' => false],
		3 =>  	['name' => 'regular','is_admin' => false],
		100 => 	['name' => 'admin',  'is_admin' => true]
	];

	function get_roles(){
		return $this->roles;
	}

	function get_role_id($name){
		foreach ($this->roles as $ix => $r){
			if ($r['name'] == $name)
				return $ix;
		}

		return null;
	}

	/**
	 * is_admin
	 *
	 * @param  mixed $role_name
	 *
	 * @return bool
	 */
	function is_admin(string $role_name){
		if ($role_name == 'admin')
			return true;
		elseif ($role_name == 'guest' || $role_name == 'registered')
			return false;

		foreach ($this->roles as $r){
			if ($r['name'] == $role_name)
				return $r['is_admin'];
		}

		throw new \Exception("Role is not in model");
	}
	
	function getRoleName($role_id){
		return $this->roles[$role_id]['name'];
	}
}