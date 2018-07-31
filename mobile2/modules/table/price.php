<?php
session_start();
$id = $_GET['id'];
$sid = "s".$id;
echo "<span class='khanos-price' >£ ".$_SESSION[$sid]. "</span>"
 

?>
