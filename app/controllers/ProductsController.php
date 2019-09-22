<?php

namespace SimpleRest\controllers;

class ProductsController extends MyController
{
	function index(){
		$this->view('products.php');
	}
}
	