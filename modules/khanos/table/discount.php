<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../system/db/db_conf.php';
$orderid = $_GET['order'];
?>

  
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Discount </h4>
      </div>
      <div class="modal-body">
          
          <form method="POST">

       <?php
            $result = mysqli_query($conn, "SELECT * FROM discounts");
            while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
       ?>
              <input type="hidden" name="Value" value="<?php echo $row['Percentage'] ?>" />
              <button type="submit" class="btn btn-lg btn-khanos2 " name="Pay_type" value="discount"><i class="material-icons">loyalty</i> <?php echo $row['Name'] ?></button>
          
                    </form>
          

            <?php }mysqli_free_result($result); ?>
                    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

      </div>




