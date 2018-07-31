<?php

require_once 'modules/login/userchk.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'modules/db/db.php';
require_once 'system/system/classes.php';
$system = new system();
$tableid = $_GET['id'];
$id = $_GET['id'];
$orderid = $_GET['order'];
$restaurant = "";
$name = $system->sqlsorgu("restaurant", "Name");
$address = $system->sqlsorgu("restaurant", "Address");
$tel = $system->sqlsorgu("restaurant", "Tel");
$vat = $system->sqlsorgu("restaurant", "Vat_No");
$message = $system->sqlsorgu("restaurant", "Message");
$printers = $system->sqlsorgu("printers where Master = '1'", "System");
$discount = $system->sqlsorgu("pay where Order_id = $orderid and Pay_type = 'discount'", "Value");

require __DIR__ . '/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

$filename = '/dev/usb/lp0';
$filename1 = '/dev/usb/lp1';
if (file_exists($filename)) {
    $connector = new FilePrintConnector("/dev/usb/lp0");
} else if (file_exists($filename1)) {
    $connector = new FilePrintConnector("/dev/usb/lp1");
}
            $printer = new Printer($connector);

try {
  $orderprice = 0;
    $result3 = mysqli_query($conn, "SELECT * FROM v_order_items where Table_id = $id and Setgroup is not null group by Setgroup   ");
    while (($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) != NULL) {
        
        $orderprice = $orderprice + $row3['MenuPrice'];
    }mysqli_free_result($result3);
    $result5 = mysqli_query($conn, "SELECT id, count(Item_id) as Count, Menu_Item_Name, Option_Name, Notes, sum(ItemPrice) as Price FROM v_order_items where Table_id = $id and Setgroup is null group by Item_id, option_id, Notes order by id ASC ");
    while (($row5 = mysqli_fetch_array($result5, MYSQLI_ASSOC)) != NULL) {

        $orderprice = $orderprice + $row5['Price'];

    }mysqli_free_result($result5);
    $total = $orderprice;

  
    /* Order kapatiliyor d */

    $post['id'] = $orderid;
    $post['status'] = 0;
$system->islem($post, "order_no", "");
    unset($post);
    
    
    /* Order Items disable yapiliyor */
    $result = mysqli_query($conn, "SELECT * FROM order_items where Table_id = $id and Status = 1");
    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
        $post['id'] = $row['id'];
        $post['Status'] = 0;

  $system->islem($post, "order_items", "");
        unset($post);
    }mysqli_free_result($result);
    /* Hesap sisteme kaydediliyor. */
    $post['Cash'] = 0;
    $post['Card'] = 0;
    $post['Voucher'] = 0;
    $post['Deposit'] = 0;
    $post['Discount'] = 0;

    $resultf = mysqli_query($conn, "SELECT * FROM pay where Table_id = $id and Order_id = $orderid");
    while (($rowf = mysqli_fetch_array($resultf, MYSQLI_ASSOC)) != NULL) {

        $Pay_type = $rowf['Pay_type'];
        switch ($Pay_type) {
            case "cash":
                if ($post['Cash'] > 0) {
                    $post['Cash'] = $post['Cash'] + $rowf['Value'];
                } else {
                    $post['Cash'] = $rowf['Value'];
                }

    
                break;
            case "card":
                if ($post['Card'] > 0) {
                    $post['Card'] = $post['Card'] + $rowf['Value'];
                } else {
                    $post['Card'] = $rowf['Value'];
                }
                break;
            case "deposit":
                $post['Deposit'] = $rowf['Value'];
                break;

            case "voucher":

                $post['Voucher'] = $rowf['Value'];

                break;
            case "discount":
                $post['Discount'] = $rowf['Value'];
                break;
        }


                }mysqli_free_result($resultf);

        if ($post['Discount'] > 0 ){
           $d1 = $post['Discount'];
           $discount = ($total * $d1) / 100 ;
           $discounted = $total - $discount;
           $discounted = number_format($discounted, 2, '.', '');
       
            $post['Total'] = $discounted;
            $post['Amount'] = $total;
        
        }else {
             $post['Total']= $total;
            $post['Amount'] = $total;
        }
        $post['Table_id'] = $id;
        if($id >= 200){
            $post['Cus_Type'] = "Takeaway"; 
          $b = 0;
          $a = 0;
            $resultc = mysqli_query($conn, "SELECT Item_id FROM order_items where Order_id = $orderid and Table_id >= 200");
            while (($rowc = mysqli_fetch_array($resultc, MYSQLI_ASSOC)) != NULL) {
                $iid = $rowc['Item_id'];
            $a = $system->sqlsorgu("menu_items where id = $iid and Type = 1", "Price");
            $b = $a + $b;

           }mysqli_free_result($resultc); 
        $post['Cold_items'] = $b;
        }
        else{
            $post['Cus_Type'] = "Restaurant";
        }
        $post['Date'] = date("Y-m-d H:i:s");
        $post['Order_id'] = $orderid; 
$chk = ($post['Cash']  + $post['Card'] + $post['Voucher']  +  $post['Deposit']) ;

   if ( $chk > $post['Total']){

$sonuc = $chk - $post['Total']  ;
$post['Cash'] = $post['Cash'] - $sonuc ;


}





$system->islem($post, "closed_tables", "");
   $printer->pulse();
   $printer->pulse(1);
   $printer -> pulse(0, 100, 100);
    $printer -> pulse(1, 100, 100);
    $printer -> pulse(0, 300, 300);
    $printer -> pulse(1, 300, 300);

     /* Close printer */
$printer->close(); 

header('location: main.php');
} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
}

