<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
$config =  include '../config/config.php';
require_once '../helpers/tokens.php';
require_once '../libs/database.php';
require_once '../models/product.php';


// Get db connection  
$db = new Database($config);
$conn = $db->conn;

$product = new Product($conn);
 
$rows = $product->readAll();
print_r(json_encode($rows)); // Send enconded response
?>