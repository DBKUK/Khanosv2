<?php
require $_SERVER['DOCUMENT_ROOT'] . '/modules/system/login/userchk.php';
require $_SERVER['DOCUMENT_ROOT'] . '/modules/system/config.php';
require $_SERVER['DOCUMENT_ROOT'] . '/modules/core/print_op/autoload.php';

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
$tableid = $_GET['id'];
$orderid = $_GET['order'];
$restaurant = "";
$name = $system->sqlsorgu("restaurant", "Name");
$address = $system->sqlsorgu("restaurant", "Address");
$tel = $system->sqlsorgu("restaurant", "Tel");
$vat = $system->sqlsorgu("restaurant", "Vat_No");
$message = $system->sqlsorgu("restaurant", "Message");
$printers = $system->sqlsorgu("printers where Master = '1'", "System");
$total = $system->sqlsum("invoice_print where Order_id = $orderid", "Price");
$discount = $system->sqlsorgu("Pay where Order_id = $orderid and Pay_type = 'discount'", "Value");
if ($discount > 0) {
    $price2 = ($total / 100) * ($discount);
    $price2 = round($price2, 2);
    $message = $total - $price2;

}

$prn_type = $system->sqlsorgu("printers where System = '$master_prt' ", "Type");
switch ($prn_type) {
    case '0':
        $connector = new FilePrintConnector("$lp");
        break;

    case '1':
        $ip = $system->sqlsorgu("printers where System = '$master_prt'", "IP");
        $connector = new NetworkPrintConnector($ip, 9100);
        break;
}
?>





try {



    $connector = new WindowsPrintConnector($printers);
    $printer = new Printer($connector);
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->setTextSize(2, 2);
    $printer->text($name . "\n");
    $printer->setTextSize(1, 1);
    $printer->text($address . "\n");
    $printer->text($tel . "\n");
    $printer->text("VAT NO: " . $vat . "\n");


    $printer->text("\n");
    $printer->text("Date/Time :" . date("d/m/Y - H:i") . "\n");
    $printer->text("\n");
    $printer->text("-----------------------------------------------\n");


    $result = mysqli_query($conn, "SELECT * FROM invoice_print where Order_id = $orderid");
    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setDoubleStrike(1 == 1);
        $printer->text($row['pcs'] . "    " . $row['Menu_Item_Name'] . $row['Option_Name'] . "\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("£" . $row['Price'] . "\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setDoubleStrike(0 == 0);
        $printer->text("------------------------------\n");

        sleep(0.15);
    }mysqli_free_result($result);



    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("-----------------------------------------------\n");
    $printer->text("\n");

    $printer->setTextSize(1, 1);
    $printer->setEmphasis(true);
    if (isset($discount)) {
        $printer->text("TOTAL : £" . $total . "\n");
        $printer->text("Discount" . $discount . "%" . ": £" . $price2 . "\n");
        $printer->setTextSize(2, 1);
        $printer->text("TOTAL TO PAY : £" . $message . "\n");
    } else {
        $printer->setTextSize(2, 1);
        $printer->text("TOTAL TO PAY : £" . $total . "\n");
    }

    $printer->setTextSize(1, 1);
    $printer->text("-----------------------------------------------\n");
    $printer->text($message . "\n");

    $printer->text("\n");
    $printer->text("\n");
    $printer->text("\n");

    $printer->cut();


    /* Close printer */
    $printer->close();

    /* veriler order_temp den order_item a tasiniyor */



    header('location: main.php');
} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
}
