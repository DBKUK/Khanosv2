<?php

require_once 'modules/db/db.php';

$id = $_GET['id'];

$sqlinsert = "UPDATE order_no SET status='1' WHERE id = $id ;";
        if ($conn->query($sqlinsert) === TRUE) {

       $sqlinsert2 = " UPDATE order_items SET Status = 1 WHERE Order_id= $id;";
        if ($conn->query($sqlinsert2) === TRUE) {     
            
            $sqlinsert3 = " DELETE FROM `khanos`.`closed_tables` WHERE `Order_id`= $id;";
        if ($conn->query($sqlinsert3) === TRUE) {    
              header('location: main.php');
        }else {
            echo "Error: " . $sqlinsert3 . "<br>" . $conn->error;
            echo $mysqli->error;
        }
           
        }else {
            echo "Error: " . $sqlinsert2 . "<br>" . $conn->error;
            echo $mysqli->error;
        }
            
            
        } else {
            echo "Error: " . $sqlinsert . "<br>" . $conn->error;
            echo $mysqli->error;
        }