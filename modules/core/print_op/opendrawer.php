<?php

require_once 'modules/login/userchk.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'modules/db/db.php';
require_once 'system/system/classes.php';
$system = new system();

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
          
            $printer->pulse();
            $printer->pulse(1);


            /* Close printer */
            $printer->close();


    

            header('location: main.php');
