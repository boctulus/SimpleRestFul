<?php
	include "vendor/autoload.php";

	$c = $_GET['c'] ?? 'products';
	$a = $_GET['a'] ?? 'index';
	
	include "controllers/$c.php";

