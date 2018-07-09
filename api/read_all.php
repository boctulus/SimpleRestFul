<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
$config =  include '../config/config.php';
require_once '../helpers/auth_check.php';
require_once '../libs/database.php';
require_once '../models/product.php';


// Get db connection  
$conn = Database::getConnection($config);

$product = new Product($conn);
 
$rows = $product->readAll();
print_r(json_encode($rows)); // Send enconded response
?>