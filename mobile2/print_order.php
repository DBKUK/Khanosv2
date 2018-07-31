<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../modules/login/userchk.php';
require_once '../modules/db/db.php';
require_once '../system/system/classes.php';
$system = new system();
$islem = $_GET['func'];
require '../autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;

switch ($islem) {

    case "done":
        try {
 $table = $_GET['table'];
 $deposit = $system->check("order_print where Table_id = $table and Menu_Item_Name = 'Deposit' ");
 if ( $deposit == 0 ) {
         $result1 = mysqli_query($conn, "Select Printer from order_print where Table_id = $table group by Printer ");
         while (($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)) != NULL) {
        $printers = $row1['Printer'];
        switch ($printers) {
            case 'bar':
                $connector = new CupsPrintConnector("barr");
                break;
                case 'kitchen':
                $connector = new CupsPrintConnector("kitchen");
                break;
        }
             
              
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("----------------  ORDER  ----------------\n");
            $printer -> setFont(Printer::FONT_B);
            $printer->setTextSize(2, 2);
            $printer->text("\n");
            $printer->text(date("d/m/Y - H:i") . "\n");
            $printer->text("\n");
            $printer -> setFont(Printer::FONT_A);
$printer -> setTextSize(1, 1);
            $printer->text("-----------------------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setDoubleStrike(1 == 1);
           
            $result = mysqli_query($conn, "Select * from order_print where Table_id = $table and Printer = '$printers' order by Priority ASC ");
            while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
                if($row['Priority'] == 1 && !isset($line) ){
                    $line = 1;
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> setFont(Printer::FONT_B);
                $printer->setTextSize(2, 2);
                    $printer->text("---- STARTERS ---- \n");
                    $printer->text("\n");
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> setFont(Printer::FONT_A);
                } else if($row['Priority'] == 2 && $line == 1){
                     $printer->setTextSize(1, 1);
                 $printer -> setFont(Printer::FONT_B);
                $printer->setTextSize(2, 2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("=================================\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $line = 2;
                }
                
                $printer -> setFont(Printer::FONT_A);
                $printer->setTextSize(1, 1);
                $menu = $row['Menu_name']. " - ". $row['Menu_Cat_Name'];
                $printer->text($menu."\n");
                $printer -> setFont(Printer::FONT_B);
                $printer->setTextSize(2, 2);
                $printer->text($row['pcs'] . " ".$row['Menu_Item_Name'] . " ". $row['Option_Name'] . "\n" );
            
                if (!empty($row['Notes'])) {$printer->text($row['Notes'] . "\n");                }
                $printer->text("\n");


                $tableno = $row['Table_No'];
            }mysqli_free_result($result);
            $printer->setTextSize(1, 1);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("-----------------------------------------------\n");
            $printer->text("\n");
            if (!empty($tableno)) {
                $printer->setTextSize(2, 2);
                $printer->text("TABLE " . $tableno . "\n");
            } else {
                $printer->setTextSize(2, 2);
                $printer->text("TAKE AWAY\n");
                $printer->text("\n");
                $id = $_GET['table'];
                $cusname = $system->sqlsorgu("order_no where Table_id = $id and status = 1", "CustomerName");
                $custel =  $system->sqlsorgu("order_no where Table_id = $id and status = 1", "Phone");
                $printer->text( $cusname. "\n");
                $printer -> setFont(Printer::FONT_A);
                 $printer->setTextSize(1, 1);
                  $printer->text("\n");
                $printer->text( $custel. "\n");
            }
            $printer->text("\n");
            $printer->text("\n");
            $printer->text("\n");

            $printer->cut();
        

            /* Close printer */
            $printer->close();
 }mysqli_free_result($result1);
 }
/* veriler order_temp den order_item a tasiniyor */
            
            $result = mysqli_query($conn, "SELECT * FROM order_temp where Table_id = $table");
            while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
                $post = $row;
                $sil = $post['id'];
                unset($post['id']);
                unset($post['Print']);
                $post['Date'] = date("Y-m-d H:i:s");
                $post['Status'] = 1;
                if(empty($post['Setgroup'])){
                    unset($post['Setgroup']);
                    if(empty($post['CustomerId'])){
                        unset($post['CustomerId']);
                    }
                }               

                $system->islem($post, "order_items", "");
                unset($post);
                $post['sil'] = $sil;
                $system->islem($post, "order_temp", "");
            }mysqli_free_result($result);
 if ( $deposit == 0 ) {
           header('location: main.php');
 } else {
      header("location: invoice.php?id=$table");
 }
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
        }

        break;
}