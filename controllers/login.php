<?php

require_once "my_controller.php";

class LoginController extends My_Controller
{
	function index(){
		$this->login();
	}
	
	function login(){
		$this->view('login.php');
	}
	
	function signin(){
		$this->view('signin.php');
	}
}


