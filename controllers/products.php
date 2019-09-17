<?php

namespace Controllers;

class ProductsController extends MyController
{
	function index(){
		$this->view('products.php');
	}
}
	