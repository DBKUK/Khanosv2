<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../../modules/db/db.php';
$item = $_GET['item'];
?>





<div class="modal-khanos">
    <div class="row">
    <?php

                
    $result = mysqli_query($conn, "SELECT * FROM menu_item_options where Item_id = $item");
    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
        $item = $row['Item_id'];
        $query_db = "SELECT * FROM menu_items where id = $item";

        $db = mysqli_query($conn, $query_db) or die(mysqli_error());
        $row_db = mysqli_fetch_assoc($db);
        $itemprice = $row_db['Price'];
      
        ?>


        <div class="col-sm">
                <form method="POST" action="modules/table/operations.php" id="test-form" enctype="multipart/form-data">
                <input type="hidden" name="table" value="<?php echo $_GET['id']; ?>" />
                <input type="hidden" name="Menu_Cat" value="<?php echo $row_db['Menu_Cat'] ?>" />
                <input type="hidden" name="Priority" value="<?php echo $row_db['Priority'] ?>" />
                <input type="hidden" name="menu_id" value="<?php echo $row_db['Menu'] ?>" />
                <input type="hidden" name="Item_id" value="<?php echo $row['Item_id'] ?>" />
                <input type="hidden" name="Price" value="<?php echo $itemprice + $row['Price'] ?>" />
                <input type="hidden" name="option_id" value="<?php echo $row['id'] ?>" />
                <button type="submit" class="btn btn-block btn-lg btn-success"><?php echo $row['Option_Name'] ?></button>
            </form>    
        </div>
 
    <?php }mysqli_free_result($result); ?>
</div>
</div>
 <script>
    $(document).ready(function () {
        $('#show').load('modules/table/items.php?id=<?php echo $_GET['id']?>');

        $('.modal-khanos form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function (data) {
                    $('#show').load('modules/table/items.php?id=<?php echo $_GET['id']?>');
                    $('#fiyat').load('modules/table/price.php?id=<?php echo $_GET['id'] ?>');
                }
            });

        });

    });
    

    var y = document.getElementById("myDIV");
   y.style.display = "none";
   var z = document.getElementById("modalll");
z.style.display = "block";
</script>






