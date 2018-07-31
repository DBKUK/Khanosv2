<?php 
$server =       'localhost';
$kullanici =    'ontec';
$sifre =        '98579044';
$database =     'khanos2';
$port =         '3306';
$conn = mysqli_connect($server, $kullanici, $sifre, $database, $port);
if (!$conn) {   die('Could not connect to MySQL: ' . mysqli_connect_error());}
mysqli_query($conn, 'SET NAMES \'utf8\'');