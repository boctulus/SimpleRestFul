<?php

namespace simplerest\models;

use simplerest\core\Model;

class RolesModel {

	protected $roles = [
		0 => ['name'  => 'guest',      'is_admin' => false],
		1 => ['name'  => 'registered', 'is_admin' => false],

		/*
			Edit from here -->
		*/

		2 => ['name'  => 'basic',     'is_admin' => false],
		3 =>  ['name' => 'regular',   'is_admin' => false],
		100 => ['name'=> 'admin',     'is_admin' => true]
	];

	function is_admin($role_id){
		return $this->roles[$role_id]['is_admin'];
	}
	
	function getRoleName($role_id){
		return $this->roles[$role_id]['name'];
	}
}