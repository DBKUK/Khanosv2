<?php
require_once 'modules/login/userchk.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'modules/db/db.php';
require_once 'system/system/classes.php';
$system = new system();
$id = $_GET['id'];
$order_id = $system->sqlsorgu("order_items where Table_id = $id and Status = 1", "Order_id");

$sum = $system->sqlsum("pay where Order_id = $order_id and Pay_type != 'discount'", "Value");
$sum == 0.00;
  


if ($_POST) {
    $post = $_POST;
    if (isset($post['sil'])){
       $system->islem($post, "pay", ""); 
    }
    
    

    //order_items bolumunden kayit siliyor
    
    if (isset($post['Pay_type'])) {
        $post['Table_id'] = $id;
        $post['Order_id'] = $order_id;
 

$system->islem($post, "pay", "");
$sum = $system->sqlsum("pay where Order_id = $order_id and Pay_type != 'discount'", "Value");
if (empty($sum)){
   $sum = 0; 
}
        unset($_POST);
   
    }
    
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

require 'modules/invoice/main_menu.php';



$discount = $system->sqlsorgu("pay where Order_id = $order_id and Pay_type = 'discount'", "Value");
if ($discount > 0){
    $price2 = ($price / 100) * ($discount);
    $price2 = round($price2, 2);
    $message = $price - $price2 ;
      }

?>       


<div class="main-panel">
    <nav class="navbar navbar-transparent navbar-absolute hidden-md hidden-lg">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>

        </div>
    </nav>


    
    <div class="content2">
        <div class="container-fluid">
            <div class="card2">
                <div class="card-content">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Total Amount</label>
                            <input type="text" name="pricero" class="form-control form-large" value="<?php echo "£ " . $price ?>" readonly="readonly" disabled="" />


     
<?php 
            $result = mysqli_query($conn, "SELECT * FROM pay where Order_id = $order_id and Pay_type != 'discount'");
            while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
                ?>
                            <div class="row">
                            <div class="col-md-11">
     <p class="khanos_pay"><?php echo "£". $row['Value']."  -  ". $row['Pay_type'] ?></p>
                            </div>
                            <div class="col-md-1">
     <form method="POST">
         <input type="hidden" name="sil" value="<?php echo $row['id'] ?>" />
         <button type="submit" class="cancel"  name="submit"><i class="fa fa-ban" aria-hidden="true"></i></button>
     </form>
                            </div>
                            </div>

            <?php }mysqli_free_result($result); ?>
     
     <?php 
            $result = mysqli_query($conn, "SELECT * FROM pay where Order_id = $order_id and Pay_type = 'discount'");
            while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
                ?>

     <p class="khanos_pay"><?php echo "£". $price2."  -  ". $row['Pay_type']." ".$row['Value']. "%"  ?></p>

            <?php }mysqli_free_result($result); ?>
   
      <form method="POST">
                            <label>Pay</label>
                            <input type="text" name="Value" class="form-control form-large" id="value"  value="<?php if (isset($message)){ $price = $message ;} echo $totall = $price2 = round($price - $sum, 2); ?>" />
                        </div>
                        <div class="col-md-6">
                            <div id="keyboard" ></div>
                        </div>
                    </div>
                    <p> <a class="btn btn-lg btn-khanos2 btn-info" href="print_invoice.php?id=<?php echo $id?>&order=<?php echo $order_id?>"><i class="fa fa-print" aria-hidden="true"></i>
 Print</a>
                      <?php  if($discount == 0){ ?>
                        <a class="btn btn-lg btn-khanos2 btn-info" href="modules/table/discount.php?order=<?php echo $order_id?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-percent" aria-hidden="true"></i>
 Add Discount</a>
                      <?php } ?>
                    <?php if($totall <= 0 ) { ?>

                   
                    <a class="btn btn-lg btn-khanos2 btn-success" href="closetable.php?id=<?php echo $id?>&order=<?php echo $order_id?>"><i class="fa fa-print" aria-hidden="true"></i>
 Done</a>
                    <?php } ?></p>
                        <button type="submit" class="btn btn-lg btn-khanos2 btn-primary" name="Pay_type" value="cash"><i class="fa fa-money" aria-hidden="true"></i> Cash</button>
              
                        <button type="submit" class="btn btn-lg btn-khanos2 " name="Pay_type" value="card"><i class="fa fa-money" aria-hidden="true"></i>
 Card</button>
              
                        <button type="submit" class="btn btn-lg btn-khanos2" name="Pay_type" value="voucher"><i class="fa fa-gift" aria-hidden="true"></i>
 Voucher</button>
                  
                        <button type="submit" class="btn btn-lg btn-khanos2" name="Pay_type" value="deposit"><i class="fa fa-calendar" aria-hidden="true"></i>
 Deposit</button>
                    </form>





                </div>    

            </div>
        </div>
        <footer class="footer">    
            <ul class="nav nav-tabs navbar-right">   
                <li><a href="pay_cancel.php?id=<?php echo $id?>&order=<?php echo $order_id?>" class="btn btn-lg btn-khanos"><i class="fa fa-ban" aria-hidden="true"></i> Cancel</a></li>

            </ul>

        </footer>
    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>

</div>
<script src="../../assets/plugins/keyboard2/lib/js/jkeyboard.js" type="text/javascript"></script>
<link href="../../assets/plugins/keyboard2/lib/css/jkeyboard.css" rel="stylesheet" type="text/css"/>
<script>
    $('#keyboard').jkeyboard({
        layout: "numbers_only",
        input: $('#value')
    });

    
    
 
</script>
</head>

</body>


</html>