<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../system/db/db.php';
require_once '../system/classes/oums.php';
$system = new system();


        $table = $_GET['table'];
       
/* veriler order_temp den order_item a tasiniyor */
            
            $result = mysqli_query($conn, "SELECT id FROM order_temp where Table_id = $table");
            while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
                $post = $row;
                $sil = $post['id'];
                unset($post);
                $post['sil'] = $sil;
               $system->islem($post, "order_temp", "");
               
            }mysqli_free_result($result);
            
            if( $system->check("order_items where Table_id = $table and Status = 1") == 0){
                
                $or_id = $system->sqlsorgu("order_no where Table_Id = $table and status = 1 ", "id");
                $post['sil'] = $or_id;
                $system->islem($post, "order_no", "");
             
            }

          header('location: ../../main.php');
     