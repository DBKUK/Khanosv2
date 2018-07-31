<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../modules/login/userchk.php';
require_once '../modules/db/db.php';
require_once '../system/system/classes.php';
$system = new system();

$today = date('Y-m-d');
$check = $system->sqlsorgu("day_no where Date = '$today' ", "Number");
$number = $system->sqlsorgu("day_no order by Number DESC ", "Number");
if($check == 0){
    $post['Date'] = $today;
    $post['Number'] = $number + 1;
    $system->islem($post,"day_no","");
}

//Panel menusu cagiriliyor.
 $function = "";
if (isset($_GET['func'])) {
    $function = $_GET['func'];
    }
    switch ($function) {
        case "table":
            $adres = "modules/restaurant/table.php";
            break;
        default:
            $adres = "modules/restaurant/dashboard.php";
            $table = "";
            break;
    }    
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
                <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <title></title>
        
            <link href="assets/css/bootstrap.min.css" rel="stylesheet">
            <link href="assets/css/khanos.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="container-fluid">
 <?php   require $adres;  ?>
        </div>
        
        <script src="assets/js/jquery-3.3.1.slim.min.js" type="text/javascript"></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    </body>
</html>
