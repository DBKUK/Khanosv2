  
<?php
$result = mysqli_query($conn, 'SELECT * FROM res_tables where Table_No < 200 order by Room_id ASC, Table_No ASC');
$totalRows_db = mysqli_num_rows($result);
$say = 1;
while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
   
            
        $id = $row['id'];
        ?>

    <?php $tn = $row['id'];
    $test = $system->sqlsorgu("order_no where Table_Id = $id and status = 1", "id");
    if ($test == 0) {
        $class = "btn-outline-success";
    } else {
        $class = "btn-outline-danger";
    } if ($say==1) {?>

<div class="row">
    <?php } ?>
<div class="col col-sm-4">
        <a href="table.php?id=<?php echo $row['id'] ?>" class="btn btn-block btn-khanos <?php echo $class ?>"> 
            

            <h4><i class="fas fa-utensils"></i> Table <?php echo $row['Table_No']; ?> </h4>


           </a>
         </div>
<?php $say++;
    if ($say == 4) {
        echo "</div>";
   
        $say = 1;
    }

?>

        <?php 
    }mysqli_free_result($result); ?>
    


