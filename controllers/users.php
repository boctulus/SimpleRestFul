<?php

namespace Controllers;

class UsersController extends MyController
{
	function index(){
		$this->view('users.php');
	}
}
	