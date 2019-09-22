<?php

namespace SimpleRest\controllers;

class UsersController extends MyController
{
	function index(){
		$this->view('users.php');
	}
}
	