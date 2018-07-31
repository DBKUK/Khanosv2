<?php

require __DIR__ . '/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
$connector = new WindowsPrintConnector("bar");
            $printer = new Printer($connector);

       
/* Line spacing */
/*
$printer -> setEmphasis(true);
$printer -> text("Line spacing\n");
$printer -> setEmphasis(false);
foreach(array(16, 32, 64, 128, 255) as $spacing) {
    $printer -> setLineSpacing($spacing);
    $printer -> text("Spacing $spacing: The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog.\n");
}
$printer -> setLineSpacing(); // Back to default
*/
/* Stuff around with left margin */
$printer -> setEmphasis(true);
$printer -> text("1 x");

    $printer -> setPrintLeftMargin(64);
    $printer -> text("deneme");
    
    $printer -> setPrintLeftMargin(500);
    $printer -> text("£150.00\n");
    
    $printer -> setEmphasis(true);
$printer -> text("1 x");

    $printer -> setPrintLeftMargin(64);
    $printer -> text("deneme");
    
    $printer -> setPrintLeftMargin(500);
    $printer -> text("£150.00\n");

    
    $printer -> setEmphasis(true);
$printer -> text("1 x");

    $printer -> setPrintLeftMargin(64);
    $printer -> text("deneme");
    
    $printer -> setPrintLeftMargin(500);
    $printer -> text("£150.00\n");


/* Printer shutdown */
$printer -> cut();
$printer -> close();
