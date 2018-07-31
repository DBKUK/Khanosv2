<?php
require $_SERVER['DOCUMENT_ROOT'] . '/modules/system/login/userchk.php';
require $_SERVER['DOCUMENT_ROOT'] . '/modules/system/config.php';
require $_SERVER['DOCUMENT_ROOT'] . '/modules/core/print_op/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
try {
    $table = $_GET['table'];
    $result1 = mysqli_query($conn, "Select Printer from order_print where Table_id = $table group by Printer ");
    while (($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)) != null) {
        $printers = $row1['Printer'];

        if ($printers != null) {
            $prn_type = $system->sqlsorgu("printers where System = '$printers' ", "Type");
            switch ($prn_type) {
                case '0':
                    $connector = new FilePrintConnector("$lp");
                    break;

                case '1':
                    $ip = $system->sqlsorgu("printers where System = '$printers'", "IP");
                    $connector = new NetworkPrintConnector($ip, 9100);
                    break;
            }

            $printer = new Printer($connector);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("----------------  ORDER  ----------------\n");
            $printer->setFont(Printer::FONT_B);
            $printer->setTextSize(2, 2);
            $printer->text("\n");
            $printer->text(date("d/m/Y - H:i") . "\n");
            $printer->text("\n");
            $printer->setFont(Printer::FONT_A);

            $printer->setTextSize(1, 1);

            $sg = null;

            $printer->text("-----------------------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setDoubleStrike(1 == 1);
            $result = mysqli_query($conn, "Select * from order_print where Table_id = $table and Printer = '$printers' ");

            while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != null) {
                if (empty($row['Setgroup'])) {
                    if ($row['Menu_Cat_Name'] != "Extras") {
                        $printer->setFont(Printer::FONT_A);
                        $printer->setTextSize(1, 1);
                        $menu = $row['Menu_name'] . " - " . $row['Menu_Cat_Name'];
                        $printer->text($menu . "\n");
                    }

                } else {
                    if ($sg != $row['Setgroup']) {
                        $printer->setFont(Printer::FONT_A);
                        $printer->setTextSize(1, 1);
                        $menu = $row['Menu_name'] . " - " . $row['Menu_Cat_Name'];
                        $printer->text($menu . "\n");
                    }
                    $sg = $row['Setgroup'];
                }

                $printer->setFont(Printer::FONT_B);

                $printer->setTextSize(2, 2);

                if ($sg > 0) {
                    $group = "- ";
                } else {
                    if ($row['Menu_Cat_Name'] == "Extras") {
                        $group = "- ";
                    } else {
                        $group = null;
                    }}

                $printer->text($group . $row['pcs'] . " " . $row['Menu_Item_Name'] . " " . $row['Option_Name'] . "\n");

                if (!empty($row['Notes'])) {
                    $printer->text($row['Notes'] . "\n");
                }

                $printer->text("\n");

                $tableno = $row['Table_No'];
            }
            mysqli_free_result($result);

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

                $custel = $system->sqlsorgu("order_no where Table_id = $id and status = 1", "Phone");

                $printer->text($cusname . "\n");

                $printer->setFont(Printer::FONT_A);

                $printer->setTextSize(1, 1);

                $printer->text("\n");

                $printer->text($custel . "\n");
            }

            $printer->text("\n");

            $printer->text("\n");

            $printer->text("\n");

            $printer->cut();

            /* Close printer */

            $printer->close();
        } // Null Printer check

    }
    mysqli_free_result($result1);

    /* veriler order_temp den order_item a tasiniyor */

    $result = mysqli_query($conn, "SELECT * FROM order_temp where Table_id = $table");

    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != null) {

        $post = $row;

        $sil = $post['id'];

        unset($post['id']);

        unset($post['Print']);

        $post['Date'] = date("Y-m-d H:i:s");

        $post['Status'] = 1;

        if (empty($post['Setgroup'])) {

            unset($post['Setgroup']);

            if (empty($post['CustomerId'])) {

                unset($post['CustomerId']);
            }
        }

        $system->islem($post, "order_items", "");

        unset($post);

        $post['sil'] = $sil;

        $system->islem($post, "order_temp", "");
    }
    mysqli_free_result($result);

    if ($table > 0) {

        header("location: invoice.php?id=$table");
    } else {

        header('location: main.php');
    }
} catch (Exception $e) {

    echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
}
