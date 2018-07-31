<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../system/login/userchk.php';
require_once '../../system/db/db.php';
require_once '../../system/classes/oums.php';
$system = new system();
$id = $_GET['id'];
?>

<ul class="list-group">

    <?php
    $orderprice = 0;
    $result3 = mysqli_query($conn, "SELECT * FROM v_order_items where Table_id = $id and Setgroup is not null group by Setgroup");
    while (($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) != NULL) {
        ?>                                 
        <li class="list-group-item d-flex justify-content-between lh-condensed">
            <div>
                <h6 class="my-0"><?php echo $row3['Menu_name'] ?></h6>      
                <small class="text-muted">Brief description</small>
                <span class="text-muted"> <?php
                    echo $row3['MenuPrice'];
                    $orderprice = $orderprice + $row3['MenuPrice'];
                    $setid = $row3['Setgroup'];
                    ?> </span>

                <ul class="list-group mb-3">
                    <?php
                    // Set menu icerigindeki urunler listeleniyor ;
                    $result4 = mysqli_query($conn, "SELECT * FROM v_order_items where Table_id = $id and Setgroup = $setid ");
                    while (($row4 = mysqli_fetch_array($result4, MYSQLI_ASSOC)) != NULL) {
                        ?>


                        <li style="list-style-type:none">
                            <button class="price_btn" id='button' value="modules/khanos/table/edit_old.php?item=<?php echo $row4['id'] ?>&id=<?php echo $_GET['id']; ?>" onclick='f1(this)'>
                                <?php
                                echo substr($row4['Menu_Item_Name'], 0, 15);
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
            <button class="price_btn" id='button' class="btn btn-outline-primary" value="modules/khanos/table/edit_old.php?item=<?php echo $row5['id'] ?>&id=<?php echo $_GET['id']; ?>" onclick='f1(this)'>
                <?php echo $row5['Count'] ?> x <?php
                echo substr($row5['Menu_Item_Name'], 0, 20);
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
        <li class="list-group-item d-flex justify-content-between lh-condensed">        
            <div>         
                <h6 class="my-0">   <?php echo ucwords(strtolower($row['Menu_name'])) ?>   </h6>      
            </div>
            <span class="text-muted">£ <?php
                echo $row['MenuPrice'];
                $menuprice = $menuprice + $row['MenuPrice'];
                $setid = $row['Setgroup'];
                ?> </span>

        </li>
        <?php
// Set menu icerigindeki urunler listeleniyor ;


        $result1 = mysqli_query($conn, "SELECT * FROM v_ordertemp where Table_id = $id and Setgroup = $setid ");
        while (($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)) != NULL) {
            ?>
            <li class="list-group-item sub_item d-flex justify-content-between lh-condensed">

                <button class="sub_btn" id='button' class="btn btn-default btn-block" value="modules/khanos/table/edit.php?item=<?php echo $row1['id'] ?>&id=<?php echo $_GET['id']; ?>" onclick='f1(this)'>
                    <small class="text-muted">                  
                        <?php
                        echo ucwords(strtolower(substr($row1['Menu_Item_Name'], 0, 15)));
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
                    </small>
                </button>

            </li>


        <?php }mysqli_free_result($result1); ?>
    </ul>
<?php }mysqli_free_result($result); ?>
</li>
<?php
// Set menu olmayan urunler listeleniyor ;
$result2 = mysqli_query($conn, "SELECT id, count(Item_id) as Count, Menu_Item_Name, Option_Name, Notes, sum(ItemPrice) as Price    FROM v_ordertemp where Table_id = $id and Setgroup is null group by Item_id, option_id, Notes order by id ASC ");
while (($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) != NULL) {
    ?>


    <li class="list-group-item d-flex justify-content-between lh-condensed">


        <div>
            <button id="button" value="modules/khanos/table/edit.php?item=<?php echo $row2['id'] ?>&id=<?php echo $_GET['id']; ?>" onclick='f1(this)'>
                <h6 class="my-0">
                    <?php echo $row2['Count'] ?> x <?php
                    echo ucwords(strtolower(substr($row2['Menu_Item_Name'], 0, 15)));
                    ?>       </h6>
                <small class="text-muted"><?php
                    if (!empty($row2['Option_Name'])) {
                        echo " - " . $row2['Option_Name'];
                    }
                    ?>
                    <?php
                    if (!empty($row2['Notes'])) {
                        echo $row2['Notes'];
                    }
                    ?></small>
            </button> 
        </div>
        <span class="text-muted">£ <?php
            echo $row2['Price'];
            $menuprice = $menuprice + $row2['Price']
            ?></span>






    </a>
    </li>
<?php }mysqli_free_result($result2); ?>
</ul>
</div>

<div id="source" hidden><?php echo "£ " . ($orderprice + $menuprice); ?></div>
<?php
$sid = "s" . $id;
$_SESSION[$sid] = ($orderprice + $menuprice);
?>


