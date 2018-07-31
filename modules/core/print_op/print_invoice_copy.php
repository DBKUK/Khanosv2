<?php

require_once 'modules/login/userchk.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'modules/db/db.php';
require_once 'system/system/classes.php';
$system = new system();
$tableid = $_GET['id'];
$tableno = $system->sqlsorgu("res_tables where id = $tableid", "Table_No");
if ($tableno >= 200) {
    $tablename = "TAKEAWAY";
} ELSE {
    $tablename = "TABLE " . $tableno;
}
$id = $_GET['id'];
$orderid = $_GET['order'];


require __DIR__ . '/autoload.php';

use Mike42\Escpos\Printer;

use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
    $connector = new CupsPrintConnector("barr");
    $printer = new Printer($connector);

try {

    $restaurant = "";
    $name = $system->sqlsorgu("restaurant", "Name");
    $address = $system->sqlsorgu("restaurant", "Address");
    $tel = $system->sqlsorgu("restaurant", "Tel");
    $vat = $system->sqlsorgu("restaurant", "Vat_No");
    $message = $system->sqlsorgu("restaurant", "Message");
    $discount = $system->sqlsorgu("pay where Order_id = $orderid and Pay_type = 'discount'", "Value");
    $deposit = $system->sqlsorgu("pay where Order_id = $orderid and Pay_type = 'deposit'", "Value");
    $voucher = $system->sqlsorgu("pay where Order_id = $orderid and Pay_type = 'voucher'", "Value");
    ///header.
    $printer->setFont(Printer::FONT_B);

    $printer->setJustification(Printer::JUSTIFY_CENTER);

    $printer -> feed();
    $printer->setTextSize(2, 2);
    $printer->setDoubleStrike(1 == 1);
    $printer->text($name . "\n");
    $printer -> feed();
$printer->setTextSize(1, 2);
    $printer->setFont(Printer::FONT_B);

    $printer->text($address . ". Tel: ". $tel . "\n");
    $printer->text("\n");
    $printer->text("VAT NO: " . $vat . "\n");


    $printer->text("\n");
    $printer->text("Date/Time :" . date("d/m/Y - H:i") . "\n");
    $printer->text("\n");
    $printer->text("---- ". $tablename . " ----\n");
    $printer->text("\n");


    
//menu yazdiriliyor
    $orderprice = 0;
    $result3 = mysqli_query($conn, "SELECT * FROM v_old_items where Table_id = $id and Order_id = $orderid and Setgroup is not null group by Setgroup   ");
    while (($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) != NULL) {

    $article = $row3['Menu_name'];
    $price = $row3['MenuPrice'];
    $texttoprint = sprintf('%-30.30s %33.2f', $article, $price);
    $printer->text($texttoprint . "\n");
        $orderprice = $orderprice + $row3['MenuPrice'];
        
        $setid = $row3['Setgroup'];
        $result4 = mysqli_query($conn, "SELECT * FROM v_old_items where Table_id = $id and Order_id = $orderid and Setgroup = $setid ");
        while (($row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC)) != NULL) {
            $ItemP = $row4['ItemPrice'];
            if ($ItemP > 0) {
                $ip = " + £" . $ItemP;
            } else {
                $ip = "";
            }
            $printer->setJustification(Printer::JUSTIFY_LEFT);

            if ($row4['Menu_Item_Name'] == "General Item") {
                $notes = $row4['Menu_Item_Name'] . " " . $row4['Notes'];
            } else {
                $notes = $row4['Menu_Item_Name'];
            }
            $printer->text("--" . $notes . " " . $row4['Option_Name'] . $ip . "\n");
            $orderprice = $orderprice + $ItemP;
        }mysqli_free_result($result4);
        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->text("------------------------------\n");
    }mysqli_free_result($result3);
// Set menu olmayan urunler listeleniyor ;
    $result5 = mysqli_query($conn, "SELECT id, count(Item_id) as Count, Menu_Item_Name, Option_Name, Notes, sum(ItemPrice) as Price FROM v_old_items where Table_id = $id and Order_id = $orderid and Setgroup is null group by Item_id, option_id, Notes order by id ASC ");
    while (($row5 = mysqli_fetch_array($result5, MYSQLI_ASSOC)) != NULL) {

        $printer->setJustification(Printer::JUSTIFY_LEFT);

        if ($row5['Menu_Item_Name'] == "General Item") {
            $notes = " " . $row5['Notes'];
        } else {
            $notes = "";
        }
        
    $articlex = $row5['Count'] . "  " . $row5['Menu_Item_Name'] . " " . $row5['Option_Name'] . $notes;
    $pricex = $row5['Price'];
    $texttoprint = sprintf('%-30.30s %33.2f', $articlex, $pricex);
    $printer->text($texttoprint . "\n");
        $orderprice = $orderprice + $row5['Price'];
        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->text("------------------------------\n");
    }mysqli_free_result($result5);
    $total = $orderprice;
    $printer -> setEmphasis(false);
    $printer->setFont(Printer::FONT_B);
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->setTextSize(2, 2);

    $printer->setJustification(Printer::JUSTIFY_CENTER);

    $printer->text("\n");
    $printer->text("TOTAL : £" . $total . "\n");
    $printer->setTextSize(1, 1);
    $printer->text("-----------------------------------------------\n");

    if (isset($discount)) {
        $printer->setTextSize(2, 2);
        $printer->text("TOTAL : £" . $total . "\n");
        $price2 = round((($total / 100) * ($discount)), 2);
        $printer->text("\n");
        $Price3 = $total - $price2;
        $printer->text("Discount" . $discount . "%" . ": £" . $price2 . "\n");
        $printer->text("\n");


        $total = $Price3;
    }

    if (isset($voucher)) {
        $printer->text("Voucher" . ": £" . $voucher . "\n");
        $printer->text("\n");
        $Pricev = $total - $voucher;


        $total = $Pricev;
    }
    if (isset($deposit)) {
        $printer->text("Deposit" . ": &pound;" . $deposit . "\n");
        $printer->text("\n");
        $Priced = $total - $deposit;


        $total = $Priced;
    }
    $printer->setTextSize(2, 2);
    $printer->text("TOTAL TO PAY:" . $total . "\n");

    $printer->setTextSize(1, 1);
    $printer->text("-----------------------------------------------\n");
    $printer->text($message . "\n");

    $printer->text("\n");
    $printer->text("\n");
    $printer->text("\n");





    header("location: panel.php?func=orders");
} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
}
    $printer->cut();


    /* Close printer */
    $printer->close();
    /* Order kapatiliyor d */
