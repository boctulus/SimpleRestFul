<?php

namespace simplerest\controllers;

class ProductsController extends MyController
{
	function index(){
		$this->view('products.php');
	}
}
	