<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require $_SERVER['DOCUMENT_ROOT'].'/modules/system/db/db.php';
if (!empty($_POST)) {
    $pass = $_POST['Pass'];
    $query_db = "SELECT * FROM users where Pass = '$pass' ";
    $db = mysqli_query($conn, $query_db) or die(mysqli_error());
    $row_db = mysqli_fetch_assoc($db);
    $totalRows_db = mysqli_num_rows($db);
    $userid = $row_db['id'];
// database sorgulaması bitti
// kayıt bulundu ise yapılacak işlemler başlıyor.
// kayıt bulunamadı ise sayfa hata sayfasına yönlendiriyor.
    if ($totalRows_db == 0) {
        header('location: index.php?login=false');
        } else{ 
        session_start();
        $_SESSION['user'] = $row_db['id'];
        $_SESSION['Group'] = $row_db['Group'];
        $_SESSION['Name'] = $row_db['Name'];
        header('location: main.php');

    }



}




end:

if (isset($_GET['login'])){

    $chk = 0;

}

?>