<?php
	
	call_user_func($_REQUEST['a'] ?? 'index');
	
	
	function index(){
		include "views/products.php";
	}