<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../system/login/userchk.php';
require_once '../../system/db/db.php';
require_once '../../system/classes/oums.php';

$system = new system();
   $chks = 0;

if (isset($_POST['copy'])) {
    $post = $_POST;
    $copy = $post['copy'];
    $count = $post['count'];
    $post = $system->sqlarray("order_temp", "WHERE id = $copy");
    unset($post['id']);
    unset($post['count']);
    unset($post['copy']);
    unset($post['Notes']);
    unset($post['Setgroup']);
    unset($post['Print']);
    if (empty($post['option_id'])) {
        unset($post['option_id']);
    }
    $x = 1;
    while ($x <= $count) {
        $system->islem($post, "order_temp", "");
        $x++;
            $chks = 1;
           unset($_POST['copy']);
    }
} else if ($_POST) {
    $post = $_POST;
    $id = $post['table'];
    unset($post['table']);
    //order_items bolumunden kayit siliyor

    if (empty($post['Price'])) {
        unset($post['Price']);
    }

    if (isset($post['function'])) {
        if ($post['function'] == "customer") {
            $post['Table_Id'] = $id;
            $post['datetime'] = date("Y-m-d H:i:s");
            $post['status'] = 1;
            unset($post['function']);
            $system->islem($post, "order_no", "");
            $chks = 1;
        }
    } else {
        if (empty($post['Priority'])) {
            $post['Priority'] = 2;
        }

        if (isset($post['item'])) {
            $system->islem($post, "order_items", "");
            $chks = 1;
            unset($_POST);
        } else {
            // ORder_temp e yapilacak olan tum islemleri yapiyor
            //order icin olusturulmus id olup olmadigi sorgulaniyor varsa deger ataniyor yoksa yeni kayit olusturuluyorve ve degiskene veriliyor
            if ($system->check("order_no where Table_Id = $id and status = 1") == 1) {
                $order = $system->sqlsorgu("order_no where Table_Id = $id and status = 1", "id");
                $post['Order_id'] = $order;
            } else {
                $kayit['Table_Id'] = $id;
                $kayit['datetime'] = date("Y-m-d H:i:s");
                $kayit['status'] = 1;
                $system->islem($kayit, "order_no", "");
                $order = $system->sqlsorgu("order_no where Table_Id = $id and status = 1", "id");
                $post['Order_id'] = $order;
            }
            if (isset($post['menu_id'])) {
                $iid = $post['menu_id'];

                $checkmenu = $system->sqlsorgu("menu_types where id = $iid", "SetMenu");
                $post['Setmenu'] = $checkmenu;

                if ($checkmenu == 1) {

                    $menuid = $post['menu_id'];
                    $catid = $post['Menu_Cat'];
                    $count = $system->check("order_temp where Table_id = $id and Menu_Cat = $catid");
                    $countitem = $system->check("order_items where Table_id = $id and Menu_Cat = $catid");
                    $limits = $system->sqlsorgu("menu_cat where id = $catid", "itemlimit");

                    if ($count < $limits) {
                        if ($countitem > 0) {
                            $oldid = $system->sqlsorgu("order_items where Table_id = $id and Menu_Cat = $catid order by Setgroup DESC ", "Setgroup");
                            $setgroup = $oldid + 1;
                        } else {
                            $iid = $post['menu_id'] * 100;
                            $setgroup = $iid + 1;
                            $post['Setgroup'] = $setgroup;
                        }
                    } else {
                        if ($count == $limits) {
                            $newid = $system->sqlsorgu("order_temp where Table_id = $id and Menu_Cat = $catid order by Setgroup DESC ", "Setgroup");
                            $setgroup = $newid + 1;
                        } else {
                            $groupcalc = $system->sqlsorgu("order_temp where Table_id = $id and Menu_Cat = $catid order by Setgroup DESC ", "Setgroup");
                            echo 'gc:' . $groupcalc;
                            $setgroup = $groupcalc + 1;
                        }
                    }

                    $post['Setgroup'] = $setgroup;
                }

                $post['Table_id'] = $id;
                $post['User'] = $_SESSION['user'];
            }
        }
        $system->islem($post, "order_temp", "");
      $chks = 1;
    }
}
if ($chks == 1){
    echo "tamam";
}

//Panel menusu cagiriliyor.

$function = "";
if (isset($_GET['func'])) {
    $function = $_GET['func'];
}
switch ($function) {
    case "table":
        $adres = "modules/restaurant/table.php";
        break;


    default:
        $table = "";
        $function = "";
        break;
}
?>