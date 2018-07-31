<?php
require_once 'modules/login/userchk.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'modules/db/db.php';
require_once 'system/system/classes.php';
$system = new system();
$tableid = $_POST['Table_id'];


$rid = $_POST['oid'];
unset($_POST['oid']);
$post = $_POST;
$post['Value'] =  $system->sqlsorgu("order_items where Order_id = $rid", "Price");
$post['Pay_type'] = "deposit";
$post['status'] = 1;

$system->islem($post, "pay", "");

header("location: table.php?id=$tableid");
