<?php

require_once 'my_controller.php';

class ProductsController extends MyController
{
	function index(){
		$this->view('products.php');
	}
}
	