<?php
require_once 'modules/login/userchk.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'modules/db/db.php';
require_once 'system/system/classes.php';
$system = new system();

$tableid = $_GET['id'];
$orderid = $_GET['order'];

            $result = mysqli_query($conn, "SELECT * FROM pay where Order_id = $orderid");
            while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
                
                $post['sil'] = $row['id'];
        
                $system->islem($post, "pay", "");
echo $row['id'];

  }mysqli_free_result($result);
  
header("location: table.php?id=$tableid");