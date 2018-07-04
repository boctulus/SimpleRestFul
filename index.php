<?php
	include "vendor/autoload.php";
	
	
	$c = $_GET['c'] ?? 'products';
	include "controllers/$c.php";

