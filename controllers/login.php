<?php

namespace Controllers;

class LoginController extends \Controllers\MyController
{
	function index(){
		$this->login();
	}
	
	function login(){
		$this->view('login.php', ['title'=>'Ingreso', 'hidenav'=> true]);
	}
	
	function signup(){
		$this->view('signup.php', ['title'=>'Registro', 'hidenav'=> true]);
	}
}


