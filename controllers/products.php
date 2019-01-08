<?php

require_once 'my_controller.php';

class ProductsController extends My_Controller
{
	function index(){
		$this->loadView('products.php',['title'=>'Products']);
	}
}
	