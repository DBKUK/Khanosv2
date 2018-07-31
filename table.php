<?php
require_once 'modules/system/config.php'; 
$id = $_GET['id'];

if ($system->check("order_no where Table_Id = $id and status = 1") == 1) {
    $order = $system->sqlsorgu("order_no where Table_Id = $id and status = 1", "id");
}
?>      
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <title></title>

        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/khanos.css" rel="stylesheet" type="text/css"/>
         <link href="../assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <header >
      <nav class="navbar navbar-expand-md navbar-dark bg-dark">
      <div class="col-6 pt-1">
        <span class="navbar-brand" onclick="openNav()">TABLE <?php echo $system->sqlsorgu("res_tables where id = $id ", "Table_No"); ?></span>
      </div>
        <div class="col-6 pt-1">
      
        </div>
      </nav>
    </header>   
  <!-- menu menusu burada cagiriliyor -->
  <nav>
                <div class="nav nav-pills flex-row flex-sm-row tab-khanos" id="nav-tab" role="tablist">

                    <?php
                    $say = 0;
                    $result = mysqli_query($conn, 'SELECT * FROM menu_types order by Ord ASC;');
                    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {

                        if (isset($_GET['menu']) && $_GET['menu'] == $row['id']) {
                            $class = "active";
                        } else if (!isset($_GET['menu']) && $say == 0) {
                            $class = "active";
                        } else {
                            $class = NULL;
                        }
                        ?>

                        <a class="nav-item flex-sm-fill text-sm-center btn btn-lg btn-outline-warning btn-khanos-nav <?PHP echo $cls ?>" href="#<?php echo $row['id'] ?>" data-toggle="tab">
                            <?php echo $row['Menu_name'] ?>
                            <div class="ripple-container"></div>
                        </a>

                        <?php
                        $say++;
                    }mysqli_free_result($result);
                    ?>    

                </div>
            </nav>
            <!-- menu kategorileri burada yukleniyor-->
    <div class="container-fluid">         
            <div class="row">
                <div class="table-price">
                    <div id="show"></div>




                </div>
                
            <div class="menucat">
            <div class="tab-content tab-khanos-content" id="nav-tabContent">
                <?php
                $say = 0;
                $result = mysqli_query($conn, 'SELECT * FROM menu_types order by Ord ASC;');
                while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
                    ?>
                    <div class="tab-pane tab-pane-khanos  <?php if (isset($_GET['menu']) && $_GET['menu'] == $row['id']) { ?>active <?php } else if (!isset($_GET['menu']) && $say == 0) { ?>active<?php } ?>" id="<?php
                    echo $row['id'];
                    $menu_id = $row['id'];
                    ?>">                      
                             <?php
                             $result2 = mysqli_query($conn, "SELECT * FROM menu_cat where Menu_Cat_id = $menu_id order by ord ASC ;");
                             $totalRows_2 = mysqli_num_rows($result2);
                             while (($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) != NULL) {
                                 ?>
                          
                            <a href="#" data-category-type="<?php echo $row2['id'] ?>"  class="btn btn-lg btn-khanos-cont btn-block <?php
                                    if (isset($_GET['cat']) && $_GET['cat'] == $row2['id']) {
                                        echo "btn-warning";
                                    } else {
                                        if (!empty($row2['classes'])) {
                                            echo $row2['classes'];
                                        } else {
                                            echo "btn-info";
                                        }
                                    }
                                    ?> "><?php echo $row2['Menu_Cat_Name'] ?></a>
                            
                               <?php
                           }mysqli_free_result($result2);
                           ?> 
                   
                    </div> 
                    <?php
                    $say++;
                }mysqli_free_result($result);
                ?>    
            </div>
            <div class="menuitems">
 <!-- Menu items burada yukleniyor -->
       
            <div id="Categories">
            <?php
            $result_cat = mysqli_query($conn, 'SELECT * FROM menu_cat order by Ord ASC');
            while (($row_cat = mysqli_fetch_array($result_cat, MYSQLI_ASSOC)) != NULL) {
                ?>

                <div class="card2 hide"  data-category-type="<?php echo $row_cat['id'] ?>" >

                    <div class="card-content">

                        <?php
                        if (!empty($row_cat['id'])) {
                            $category = $row_cat['id'];

                            $result = mysqli_query($conn, "SELECT * FROM menu_items where Menu_Cat = $category order by Sort ASC  ");
                            while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
                                $id = $row['id'];
                                $menucat = $row['Menu_Cat']; if ($system->check("menu_item_options where Item_id = $id") > 0) {
                                    ?>
                        
<div class="kh-btn-box <?php echo $row['classes']; ?>">

<button id='button' value="modules/table/modal.php?item=<?php echo $row['id']; ?>&id=<?php echo $_GET['id']; ?>" class="<?php echo $row['classes']; ?>" onclick='f1(this)'><?php echo str_replace("==", "", ucfirst(strtolower($row['Menu_Item_Name']))) ;  ?></button> 
</div>
                                <?php } else { ?>

                                    <div class="foo kh-btn-box <?php echo $row['classes']; ?>">
                                        <form method="POST"  action="modules/khanos/table/operations.php" id="test-form" enctype="multipart/form-data">

                                            <input type="hidden" name="table" value="<?php echo $_GET['id']; ?>" />
                                            <input type="hidden" name="Menu_Cat" value="<?php echo $row['Menu_Cat'] ?>" />
                                            <input type="hidden" name="Priority" value="<?php echo $row['Priority'] ?>" />
                                            <input type="hidden" name="menu_id" value="<?php echo $row['Menu'] ?>" />
                                            <input type="hidden" name="Item_id" value="<?php echo $row['id'] ?>" />
                                            <input type="hidden" name="Price" value="<?php echo $row['Price'] ?>" />
                                            <button type="submit" class="kh-btn <?php echo $row['classes']; ?>" ><?php echo str_replace("==", "", ucfirst(strtolower($row['Menu_Item_Name']))) ;  ?></button>
                                        </form>    
                                    </div>
                   
                                    <?php
                                }
                            }mysqli_free_result($result);
                        }
                        ?>
                        <div id="ajax-response"></div>



                    </div>

                </div>     

            <?php }mysqli_free_result($result_cat); ?>


        </div>
  
        </div>
                </div>
   </div>
        
    </div>  
        
<footer class="footer">  
    
 <div class="nav nav-pills flex-row flex-sm-row tab-khanos" id="nav-tab" role="tablist">
<a id="fiyat" href="invoice.php?id=<?php echo $id ?>" class="nav-item flex-sm-fill text-sm-center btn btn-lg btn-outline-warning"><a/>
    
 <a  href="main.php" class="nav-item flex-sm-fill text-sm-center btn btn-lg btn-outline-info"> <i class="fa fa-home" aria-hidden="true"></i> Tables</a>
<a class="nav-item flex-sm-fill text-sm-center btn btn-lg btn-outline-danger " href="modules/core/order_cancel.php?table=<?php echo $_GET['id'] ?>"> <i class="fa fa-trash-o" aria-hidden="true"></i> Cancel</a>

<a class="nav-item flex-sm-fill btn-lg text-sm-center btn btn-outline-success " href="modules/core/print_op/print_order.php?func=done&table=<?php echo $_GET['id'] ?>"> <i class="fa fa-print" aria-hidden="true"></i> Done</a>



 </div>
</footer>

  

<script src="assets/js/jquery-3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>




<div id="mySidenav" class="sidenav">
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

</div>

<!-- Modal -->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div id="modalll"></div>
          <div id="myDIV" style="display:none;" class="loader" >

<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
<span class="sr-only">Loading...</span>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>



<script>

$('body').on('click', '[data-toggle="modal"]', function(){
        $($(this).data("target")+' .modal-body').load($(this).data("remote"));
    });  
    
    $('.tab-content a').on('click', function (e) {
        e.preventDefault();
        var cat = $(this).data('categoryType');
        $('#Categories > div').addClass('hide');
        $('#Categories > div[data-category-type="' + cat + '"]').removeClass('hide');
     
        
    });


</script>
<script>
    $(document).ready(function () {
        $('#show').load('modules/khanos/table/items.php?id=<?php echo $_GET['id'] ?>');
        $('#fiyat').load('modules/khanos/table/price.php?id=<?php echo $_GET['id'] ?>');
        $('.foo form').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function (data) {
                    $('#show').load('modules/khanos/table/items.php?id=<?php echo $_GET['id'] ?>');
     $('#fiyat').load('modules/khanos/table/price.php?id=<?php echo $_GET['id'] ?>');
                }
            });

        });

    });

</script>

<script>
    $(document).ready(function () {
        $('.modal').on('hidden.bs.modal', function (e)
        {
            $('#show').load('modules/khanos/table/items.php?id=<?php echo $_GET['id'] ?>')
            $(this).removeData();
        });
    });
</script>

<script>
function openNav() {
    document.getElementById("mySidenav").style.width = "70%";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}
</script>
<script>

function f1(objButton){
    var z = document.getElementById("modalll");
   z.style.display = "none";
       
    var y = document.getElementById("myDIV");
    y.style.display = "block";
    
    var x = objButton.value;
     $('#modalll').load(x);
 $('#myModal').modal('show');

}


</script>


</body>
</html>