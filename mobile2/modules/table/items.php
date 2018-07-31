<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../../modules/login/userchk.php';
require_once '../../../modules/db/db.php';
require_once '../../../system/system/classes.php';
$system = new system();  
$id =  $_GET['id'];
?>
<div class="logo">
    <a href="main.php" class="simple-text">
        <?php if ($id >= 200) { ?>
           <i class="fa fa-home" aria-hidden="true"></i>

           <?php if (!empty($system->sqlsorgu("order_no where Table_id = $id and status = 1", "CustomerName"))) {
            echo  $system->sqlsorgu("order_no where Table_id = $id and status = 1", "CustomerName");
            echo "<br>" . $system->sqlsorgu("order_no where Table_id = $id and status = 1", "Phone");
        }
        ?>
        <?php echo $system->sqlsorgu("res_tables where id = $id ", "Table_No"); ?>
        <a href="modules/table/customer.php?tid=<?php echo $_GET['id'] ?>" class="btn btn-info btn-block" data-toggle="modal" data-target="#myModal"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Customer Name</a>
        <?PHP } ELSE { ?>
         <i class="fa fa-home" aria-hidden="true"></i> Table <?php echo $system->sqlsorgu("res_tables where id = $id ", "Table_No"); ?>
     <?php } ?>
 </a>
</div>
<div class="sidebar-wrapper">
    <div class="order-list">
        <ul class="nav">

            <?php
            $orderprice = 0;
            $result3 = mysqli_query($conn, "SELECT * FROM v_order_items where Table_id = $id and Setgroup is not null group by Setgroup");
            while (($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) != NULL) {
                ?>                                 
                <li> <?php echo $row3['Menu_name'] ?>                                      
                <span class="text-right">£ <?php
                echo $row3['MenuPrice'];
                $orderprice = $orderprice + $row3['MenuPrice'];
                $setid = $row3['Setgroup'];
                ?> </span>

                <ul>
                    <?php
                                        // Set menu icerigindeki urunler listeleniyor ;
                    $result4 = mysqli_query($conn, "SELECT * FROM v_order_items where Table_id = $id and Setgroup = $setid ");
                    while (($row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC)) != NULL) {
                        ?>


                            <li style="list-style-type:none">
                                 <button class="price_btn" id='button' value="modules/table/edit_old.php?item=<?php echo $row4['id'] ?>&id=<?php echo $_GET['id']; ?>" onclick='f1(this)'>
                                <?php
                                echo substr($row4['Menu_Item_Name'],0,15);
                                if ($row4['ItemPrice'] > 0) {
                                    echo "<span class='text-right'>" . $row4['ItemPrice'] . "</span>";
                                }
                                if (!empty($row4['option_id'])) {
                                    echo " - " . $row4['Option_Name'];
                                } if (!empty($row4['Notes'])) {
                                    ?>                                              
                                    <br> - <?php
                                    echo $row4['Notes'];
                                }
                                $orderprice = $orderprice + $row4['ItemPrice'];
                                ?>


                            </button>  

                        </li>
                    <?php }mysqli_free_result($result4); ?>
                </ul>
            <?php }mysqli_free_result($result3); ?>
        </li>
        <?php
                        // Set menu olmayan urunler listeleniyor ;
        $result5 = mysqli_query($conn, "SELECT id, count(Item_id) as Count, Menu_Item_Name, Option_Name, Notes, sum(ItemPrice) as Price FROM v_order_items where Table_id = $id and Setgroup is null group by Item_id, option_id, Notes order by id ASC ");
        while (($row5 = mysqli_fetch_array($result5, MYSQLI_ASSOC)) != NULL) {
            ?>
        
                <li style="list-style-type:none">
                    <button class="price_btn" id='button' class="btn btn-default btn-block" value="modules/table/edit_old.php?item=<?php echo $row5['id'] ?>&id=<?php echo $_GET['id']; ?>" onclick='f1(this)'>
                    <?php echo $row5['Count'] ?> x <?php
                    echo substr($row5['Menu_Item_Name'],0 ,20);
                    if (!empty($row5['Option_Name'])) {
                        echo " - " . $row5['Option_Name'];
                    }
                    ?>                                        
                    </button>
                    <span class="text-right">£ <?php
                    echo $row5['Price'];
                    $orderprice = $orderprice + $row5['Price']
                    ?></span>

                    <?php if (!empty($row5['Notes'])) { ?>
                        <br> - <?php
                        echo $row5['Notes'];
                    }
                    ?>



                </li>
             

        <?php }mysqli_free_result($result5); ?>

        <?php
                        // Order temp de bulunan Set menu icerigindeki urunler listeleniyor ;
        $menuprice = 0;
        $result = mysqli_query($conn, "SELECT * FROM v_ordertemp where Table_id = $id and Setgroup is not null group by Setgroup  , menu_id   ");
        while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
            ?>                                 
            <li> <?php echo $row['Menu_name'] ?>                                      
            <span class="text-right">£ <?php
            echo $row['MenuPrice'];
            $menuprice = $menuprice + $row['MenuPrice'];
            $setid = $row['Setgroup'];
            ?> </span>

            <ul>
                <?php
// Set menu icerigindeki urunler listeleniyor ;


                $result1 = mysqli_query($conn, "SELECT * FROM v_ordertemp where Table_id = $id and Setgroup = $setid ");
                while (($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)) != NULL) {
                    ?>
                  
                <button class="price_btn" id='button' class="btn btn-default btn-block" value="modules/table/edit.php?item=<?php echo $row1['id'] ?>&id=<?php echo $_GET['id']; ?>" onclick='f1(this)'>
                        <li style="list-style-type:none">
                            <?php
                            echo substr($row1['Menu_Item_Name'],0,15);
                            if ($row1['ItemPrice'] > 0) {
                                echo "<span class='text-right'>" . $row1['ItemPrice'] . "</span>";
                            }
                            if (!empty($row1['option_id'])) {
                                echo " - " . $row1['Option_Name'];
                            } if (!empty($row1['Notes'])) {
                                ?>                                              
                                <br> - <?php
                                echo $row1['Notes'];
                            }
                            $menuprice = $menuprice + $row1['ItemPrice'];
                            ?>


                        </li>
                  </button>
                
                <?php }mysqli_free_result($result1); ?>
            </ul>
        <?php }mysqli_free_result($result); ?>
    </li>
    <?php
// Set menu olmayan urunler listeleniyor ;




    $result2 = mysqli_query($conn, "SELECT id, count(Item_id) as Count, Menu_Item_Name, Option_Name, Notes, sum(ItemPrice) as Price    FROM v_ordertemp where Table_id = $id and Setgroup is null group by Item_id, option_id, Notes order by id ASC ");
    while (($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) != NULL) {
        ?>
    

            <li style="list-style-type:none">
                 <button class="price_btn" id='button' value="modules/table/edit.php?item=<?php echo $row2['id']?>&id=<?php echo $_GET['id']; ?>" onclick='f1(this)'>
                <span class="float-md-left">

               <?php echo $row2['Count'] ?> x <?php
               echo substr($row2['Menu_Item_Name'],0,15);
               if (!empty($row2['Option_Name'])) {
                echo " - " . $row2['Option_Name'];
            }
            ?>       </span>
                                 
 </button>  
            <span class="float-md-right">£ <?php
            echo $row2['Price'];
            $menuprice = $menuprice + $row2['Price']
            ?></span>

            <?php if (!empty($row2['Notes'])) { ?>
                <br>
                - <?php
                echo $row2['Notes'];
            }
            ?>

       
    </a>

</li>


<?php }mysqli_free_result($result2); ?>



</ul>
</div>
<?php
if ($orderprice > 0) {
    ?>

    <a href="invoice.php?id=<?php echo $id ?>" class="btn btn-block btn-info btn-pay"><?php echo "£ " . ($orderprice + $menuprice); ?></a>
<?php } else { ?>

    <a href="#" class="btn btn-block btn-default btn-pay disabled "><?php echo "£ " . ($orderprice + $menuprice) ?></a>
    <?php
}
?>
    <div id="source" hidden><?php echo "£ " . ($orderprice + $menuprice); ?></div>
    <?php 
    $sid = "s".$id;
    $_SESSION[$sid] = ($orderprice + $menuprice); ?>
</div>

