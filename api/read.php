<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
require_once '../config/config.php';
require_once '../libs/database.php';
require_once '../models/sale.php';
 
// Get db connection  
$db = new Database($db_name,$host,$user,$pass);
$conn = $db->conn;

$sale = new Sale($conn);
 
if (!isset($_GET['id']))
	exit();
 
$sale->id = $_GET['id'];
 
$sale->read();

// Send enconded response
print_r(json_encode($sale));
?>