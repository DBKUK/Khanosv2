<?php require_once 'modules/system/login/userchk.php'; ?>
<?php require_once 'modules/system/config.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../../../favicon.ico">
        <title>Khanos Dashboard</title>
        <link href="assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
        <!-- Bootstrap core CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/khanos.css" rel="stylesheet">
        <!-- -Icons CSS -->
        <link href="assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    </head>
    <body onload="startTime()">
        <header>
            <!-- Fixed navbar -->
            <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
                <a class="navbar-brand" href="main.php"><i class="fa fa-desktop" aria-hidden="true"></i>
                    Khanos</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#"><i class="fa fa-cog" aria-hidden="true"></i>
                                Control Panel </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-moon-o" aria-hidden="true"></i>
                                End Of Day Report</a>
                        </li>


                    </ul>
                    <a class="nav-link" href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> Log Out </a>
                    <div class="btn-outline-warning" id="clock"></div>
                </div>
            </nav>
        </header>
        <div class="content">
            <div class="row">
                <div class="col-md-10 panels">
                    <?php 
            $result = mysqli_query($conn, 'SELECT * FROM res_tables where Table_No < 200 order by  Table_No ASC');
            $totalRows_db = mysqli_num_rows($result);
            $say = 1;
            while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
                $tid = $row['id']; 
                       
                ?>
                     <?php
                     $timee = $system->timeago($system->sqlsorgu("order_no where Table_Id = $tid and status = 1 ", "datetime"));
                     if(!empty($timee)) { $class = "tred" ; $clas = "redy" ;}else {$class = NULL ; $clas = NULL ;} ?>
                    <a href="table.php?id=<?php echo $row['id']?>" class="table-button" id="table"> 
                    <div class="rtable <?php echo $class ?>">
                        <div class="table-header  <?php echo $clas ?>"><?php echo $row['Table_No']; ?></div>
                        <div class="tables-price">£123.12</div>
                        <div class="table-time"><?php echo $timee  ?></div>
                    </div>
                    </a>
          <?php  }mysqli_free_result($result); ?>

                </div>
                <div class="col-md-2 panels">
                    <div class="rtake">
                        <div class="rtake-header"><i class="fa fa-shopping-bag" aria-hidden="true"></i> ONUR UNLU</div>
                        <div class="rtake-price">£123.12</div>
                        <div class="rtake-time">£123.12</div>
                    </div>
                    <div class="rtake">
                        <div class="rtake-header"><i class="fa fa-shopping-bag" aria-hidden="true"></i> ONUR UNLU</div>
                        <div class="rtake-price">£123.12</div>
                        <div class="rtake-time">£123.12</div>
                    </div>

                </div>
            </div>
        </div>




        <!--take away button-->
        <?php
        $ta_no = $system->sqlsorgu("order_no where Table_Id >= 200 and Status = 1 order by Table_Id DESC", "Table_Id");
        if (empty($ta_no)) { $ta_no = 200; } else { $ta_no = $ta_no + 1; } ?>
        <div class="takeaway">
            <a href="table.php?id=<?php echo $ta_no?>" ><i class="fa fa-plus" aria-hidden="true"></i></a>
        </div>


        <footer class="footer">  

            <div class="nav nav-pills flex-row flex-sm-row tab-khanos" id="nav-tab" role="tablist">

                <a  href="main.php" class="nav-item flex-sm-fill text-sm-center btn btn-lg btn-outline-primary"><i class="fa fa-money" aria-hidden="true"></i> Open Drawer</a>
                <a class="nav-item flex-sm-fill text-sm-center btn btn-lg btn-outline-info " href="order_cancel.php?table=<?php echo $_GET['id'] ?>"> <i class="fa fa-trash-o" aria-hidden="true"></i> Finished Orders</a>




            </div>


        </footer>

        <script src="assets/js/jquery-3.3.1.slim.min.js" type="text/javascript" async: false></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js" ></script>
        <script src="assets/plugins/toastr/toastr.min.js" type="text/javascript"></script>
        <script src="assets/js/khanos.js" type="text/javascript"></script>

        <script type="text/javascript">
        toastr["success"]("Alert", "Khanos");
        
        $('#table').longpress(function() {
    // longpress callback
    alert('You just longpress-ed a button.');
});
        </script>

    </body>
</html>

<?php

require __DIR__ . '/modules/core/print_op/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
if($CustomerScreen == 0){
$connector = new FilePrintConnector("/dev/ttyS0");
 $printer = new Printer($connector);
$name = $system->sqlsorgu("restaurant", "Name");
$say = strlen($name);
$ekle = ((21 - $say)/2)-1;
$ekle = round($ekle,0);
$x = 0 ;
while ($x < $ekle){
 $printer -> text (" ");
    $x++;
}
$printer -> text ($name);
$say = strlen($name);
$say = $say;

while ( $say<22 ){
 $printer -> text (" ");

$say++;
}

$printer -> text ("WELCOME");

$printer -> close();
}
?>