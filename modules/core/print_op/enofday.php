<?php

require_once 'modules/login/userchk.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'modules/db/db.php';
require_once 'system/system/classes.php';
$system = new system();
$today = $_GET['date'];
echo $today;
$number = $system->sqlsorgu("day_no where Date = '$today'", "Number");
$name = $system->sqlsorgu("restaurant", "Name");
$address = $system->sqlsorgu("restaurant", "Address");
$tel = $system->sqlsorgu("restaurant", "Tel");
$vat = $system->sqlsorgu("restaurant", "Vat_No");
$message = $system->sqlsorgu("restaurant", "Message");
$printers = $system->sqlsorgu("printers where Master = '1'", "System");

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
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->setTextSize(2, 2);
$printer->text($name . "\n");
$printer->setTextSize(1, 1);
$printer->text($address . "\n");
$printer->text($tel . "\n");
$printer->text("VAT NO: " . $vat . "\n");


$printer->text("\n");
$printer->text("Date:" . $today . "\n");
$printer->text("\n");
$printer->text("Report No:" . $number . "\n");
$printer->text("\n");
$printer->text("-----------------------------------------------\n");
//menu yazdiriliyor
$orderprice = 0;
$result3 = mysqli_query($conn, "SELECT * from endofday_yesterday where `Date` = '$today'");
while (($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) != NULL) {
    $printer->setTextSize(2, 2);
    $printer->text($row3['Cus_Type'] . " Sales \n");
    $row3['Cus_Type'];
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->setTextSize(1, 1);
    $printer->setDoubleStrike(1 == 1);
    $printer->text("Cash: £" . $row3['Cash'] . "\n");
    $printer->text("Card: £" . $row3['Card'] . "\n");
    $printer->text("Voucher: £" . $row3['Voucher'] . "\n");
    $printer->text("Deposit: £" . $row3['Deposit'] . "\n");
    if ($row3['Cus_Type'] == "Takeaway") {
        $printer->text("Cold Sales : £" . $row3['Cold_Items'] . "\n");
    }

    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->setDoubleStrike(0 == 0);
    $printer->text("------------------------------\n");
    $printer->text("Prepayed  " . $row3['Cus_Type'] . " Sales: £" . ($row3['Deposit']+$row3['Voucher']) . "\n");
    $printer->text("Total " . $row3['Cus_Type'] . " Sales: £" . ($row3['Cash']+$row3['Card'])  . "\n");
    $printer->setDoubleStrike(0 == 0);
    $printer->text("-----------------------------------------------\n");
}mysqli_free_result($result3);
// Set menu olmayan urunler listeleniyor ;
$query_db = "SELECT sum(Total) as Total, sum(Amout) as Amount, sum(Cash) as Cash, sum(Card) as Card, sum(Voucher) as Voucher, sum(Deposit) as Deposit from endofday_yesterday where Date = '$today'";
$db = mysqli_query($conn, $query_db) or die(mysqli_error());
$row_db = mysqli_fetch_assoc($db);
$totalRows_db = mysqli_num_rows($db);
$printer->setTextSize(2, 2);
$printer->text("Day Total Sale: \n");
$printer->text("£" . $row_db['Total'] . "\n");
$printer->text("----------------------\n");
$printer->text("Day Total Income: \n");
$income = ($row_db['Cash'] + $row_db['Card'] );
$printer->text("£" . $income . "\n");
$printer->setTextSize(1, 1);
$printer->text("-----------------------------------------------\n");


$printer->text("\n");
$printer->text("\n");
$printer->text("\n");

$printer->cut();
$printer->pulse();
$printer->pulse(1);

/* Close printer */
$printer->close();
/* Order kapatiliyor d */



header("location: main.php");

