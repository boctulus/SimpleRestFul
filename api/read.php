<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
$config =  include '../config/config.php';
require_once '../helpers/tokens.php';
require_once '../libs/database.php';
require_once '../models/product.php';


// Get db connection  
$db = new Database($config);
$conn = $db->conn;

$product = new Product($conn);
 
if (!isset($_GET['id']))
	exit();
 
$product->id = $_GET['id'];
 
$product->read();

// Send enconded response
print_r(json_encode($product));
?>