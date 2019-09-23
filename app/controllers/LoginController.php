<?php

namespace simplerest\controllers;

class LoginController extends MyController
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


