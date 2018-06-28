<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
require_once '../config/config.php';
require_once '../libs/database.php';
require_once '../models/sale.php';
 
$db = new Database($db_name);
$conn = $db->conn;

$sale = new Sale($conn);
 
$rows = $sale->readAll();
print_r(json_encode($rows));
?>