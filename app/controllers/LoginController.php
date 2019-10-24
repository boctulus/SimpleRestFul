<?php

namespace simplerest\controllers;

use simplerest\libs\Debug;

class LoginController extends MyController
{
	function index(){
		$this->login();
	}
	
	function login()
	{
		$google_ctrl = new GoogleController();

        $client = $google_ctrl->getClient();
		$this->view('login.php', ['title'=>'Ingreso', 'hidenav'=> true, 'client' => $client]);
	}
	
	function signup(){
		$this->view('signup.php', ['title'=>'Registro', 'hidenav'=> true]);
	}

	function google_login()
	{
		$google_ctrl = new GoogleController();
		$res = $google_ctrl->login_or_register();

		if ($res['code'] == 200){
			$this->view('google_login.php', [
				'title'=>'Google login', 
				'hidenav'=> false,
				'access_token' => $res['data']['access_token'],
				'expires_in' => $res['data']['expires_in'],
				'refresh_token' => $res['data']['refresh_token']
			]);
		}else {
			$this->view('google_login.php', [
				'title'=>'Google login', 
				'hidenav'=> false,
				'error' => $res['error']
			]);
		}
		
	}    

}


