<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>Khanos Restaurant Management</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap core CSS     -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/userui.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/simple-sidebar.css" rel="stylesheet" type="text/css"/>
        <link rel="icon" type="image/png" href="favicon.ico" />

    </head>

    <body>
        <div id="wrapper">

            <!-- Sidebar -->
            <div id="sidebar-wrapper">
                <ul class="sidebar-nav">
                    <li class="sidebar-brand">
                        <a href="main.php" class="simple-text">
                            <?php if ($id >= 200) { ?>
                                <i class="fas fa-arrow-circle-left"></i> Takeaway 
                                <?php
                                if (!empty($system->sqlsorgu("order_no where Table_id = $id and status = 1", "CustomerName"))) {
                                    echo "- " . $system->sqlsorgu("order_no where Table_id = $id and status = 1", "CustomerName");
                                }
                                ?>
                                <?php echo $system->sqlsorgu("res_tables where id = $id ", "Table_No"); ?>
                                <a href="modules/table/customer.php?tid=<?php echo $_GET['id'] ?>" class="btn btn-info btn-block" data-toggle="modal" data-target="#myModal"><i class="material-icons">contact_phone</i> Add Customer Name</a>
                            <?PHP } ELSE { ?>
                                <i class="fas fa-arrow-circle-left"></i> Table <?php echo $system->sqlsorgu("res_tables where id = $id ", "Table_No"); ?>
<?php } ?>
                        </a>
                    </li>

                    <?php
                    $orderprice = 0;
                    $result3 = mysqli_query($conn, "SELECT * FROM v_order_items where Table_id = $id and Setgroup is not null group by Setgroup   ");
                    while (($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) != NULL) {
                        ?>                                 
                        <li> <?php echo $row3['Menu_name'] ?>                                      
                            <span class="pull-right">£ <?php
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

                                    <button id='button' value="modules/table/edit_old.php?item=<?php echo $row4['id'] ?>" onclick='f1(this)'>

                                        <li style="list-style-type:none">
                                            <?php
                                            echo $row4['Menu_Item_Name'];
                                            if (!empty($row4['option_id'])) {
                                                echo " - " . $row4['Option_Name'];
                                            } if (!empty($row4['Notes'])) {
                                                ?>                                              
                                                <br> <i class="material-icons">speaker_notes</i> - <?php
                                                echo $row4['Notes'];
                                            }
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
                    <button id='button' value="modules/table/edit_old.php?item=<?php echo $row5['id'] ?>" onclick='f1(this)'>

                        <li style="list-style-type:none">
                            <?php echo $row5['Count'] ?> x <?php
                            echo $row5['Menu_Item_Name'];
                            if (!empty($row5['Option_Name'])) {
                                echo " - " . $row5['Option_Name'];
                            }
                            ?>                                        

                            <span class="pull-right">£ <?php
                                echo $row5['Price'];
                                $orderprice = $orderprice + $row5['Price']
                                ?></span>

                            <?php if (!empty($row5['Notes'])) { ?>
                                <br> <i class="material-icons">speaker_notes</i> - <?php
                                echo $row5['Notes'];
                            }
                            ?>
                        </li>
                    </button>  

                <?php }mysqli_free_result($result5); ?>

                <?php
                // Order temp de bulunan Set menu icerigindeki urunler listeleniyor ;
                $menuprice = 0;
                $result = mysqli_query($conn, "SELECT * FROM v_ordertemp where Table_id = $id and Setgroup is not null group by Setgroup   ");
                while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
                    ?>                                 
                    <li> <?php echo $row['Menu_name'] ?>                                      
                        <span class="pull-right">£ <?php
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
                                <button id='button' value="modules/table/edit.php?item=<?php echo $row1['id'] ?>" onclick='f1(this)'>
                                    <li style="list-style-type:none">
                                        <?php
                                        echo $row1['Menu_Item_Name'];
                                        if (!empty($row1['option_id'])) {
                                            echo " - " . $row1['Option_Name'];
                                        } if (!empty($row1['Notes'])) {
                                            ?>                                              
                                            <br> <i class="material-icons">speaker_notes</i> - <?php
                                            echo $row1['Notes'];
                                        }
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
                    <button id='button' value="modules/table/edit.php?item=<?php echo $row2['id'] ?>" onclick='f1(this)'>
                        <li style="list-style-type:none">
                            <?php echo $row2['Count'] ?> x <?php
                            echo $row2['Menu_Item_Name'];
                            if (!empty($row2['Option_Name'])) {
                                echo " - " . $row2['Option_Name'];
                            }
                            ?>                                        

                            <span class="pull-right">£ <?php
                                echo $row2['Price'];
                                $menuprice = $menuprice + $row2['Price']
                                ?></span>

                            <?php if (!empty($row2['Notes'])) { ?>
                                <br> <i class="material-icons">speaker_notes</i> - <?php
                                echo $row2['Notes'];
                            }
                            ?>

                    </button>  

                    </li>


<?php }mysqli_free_result($result2); ?>



                </ul>

                <?php
                $tabletotal = $orderprice + $menuprice;
                ?>
            </div>






