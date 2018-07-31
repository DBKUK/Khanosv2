 <?php
session_start();
$id = $_GET['id'];
$sid = "s".$id; ?>

     <?php echo "£ ".number_format($_SESSION[$sid],2);

?>