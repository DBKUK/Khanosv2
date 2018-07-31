<?php

require_once 'modules/db/db.php';

$id = $_GET['id'];
$date = $_GET['date'];


$sqlinsert = "DELETE FROM order_no WHERE id = $id ;";
        if ($conn->query($sqlinsert) === TRUE) {

       $sqlinsert2 = " DELETE FROM order_items WHERE Order_id= $id;";
        if ($conn->query($sqlinsert2) === TRUE) {     
            
        $sqlinsert3 = " DELETE FROM `khanos`.`closed_tables` WHERE `Order_id`= $id;";
        if ($conn->query($sqlinsert3) === TRUE) {    
            
        $sqlinsert4 = " DELETE FROM `khanos`.`pay` WHERE `Order_id`= $id;";
        if ($conn->query($sqlinsert4) === TRUE) {  
            header("location: panel.php?func=orders&date=$date");
            }else {
            echo "Error: " . $sqlinsert4 . "<br>" . $conn->error;
            echo $mysqli->error;
        }        
           
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