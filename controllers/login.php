<?php

namespace Controllers;

class LoginController extends \Controllers\MyController
{
	function index(){
		$this->login();
	}
	
	function login(){
		$this->view('login.php', ['title'=>'Ingreso']);
	}
	
	function signin(){
		$this->view('signin.php');
	}
}


