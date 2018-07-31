<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../system/db/db_conf.php';

require_once '../../system/system/classes.php';
$system = new system();

?>
<script src="../../assets/plugins/keyboard2/lib/js/jkeyboard.js" type="text/javascript"></script>
<link href="../../assets/plugins/keyboard2/lib/css/jkeyboard.css" rel="stylesheet" type="text/css"/>
    <script>
        $('#keyboard').jkeyboard({
          layout: "english",
          input: $('#search_field')
        });
    </script>
 <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Customer</h4>
      
      </div>
     
      <div class="modal-body">
                <form method="POST">

          <input type="text" class="form-control input-group-lg" name="CustomerName" placeholder="Customer Name" value="" id="search_field">
          <?php $tid = $_GET['tid']; $orderno = $system->sqlsorgu("order_no where Table_Id = $tid and status = 1", "id")?>
    <div id="keyboard"></div>
    <input type="hidden" name="function" value="customer" />
     <input type="hidden" name="id" value="<?php echo $orderno ?>" />
    <button type="submit" class="btn btn-primary">Save changes</button>
          </form>
      </div>
      <div class="modal-footer">
       
        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>


        
      </div>
