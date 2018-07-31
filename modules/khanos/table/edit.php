<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../system/db/db.php';
$item = $_GET['item'];
?>
<script src="../../assets/plugins/keyboard2/lib/js/jkeyboard.js" type="text/javascript"></script>
<link href="../../assets/plugins/keyboard2/lib/css/jkeyboard.css" rel="stylesheet" type="text/css"/>

<script>
    function changeid1()
    {
        $("#keyboard1").show();
        $('#keyboard1').jkeyboard({
            layout: "tablet",
            input: $('#notes')

        });

        $("#keyboard2").hide();

    }
    function changeid2()
    {
        $("#keyboard2").show();
        $('#keyboard2').jkeyboard({
            layout: "numbers_only",
            input: $('#price')

        });
        $("#keyboard1").hide();

    }
</script>

<div class="modal-khanos">

        <form method="POST" action="modules/khanos/table/operations.php" id="test-form" enctype="multipart/form-data">

            <?php
            $get = $_GET['item'];

            $query_db = "SELECT * FROM order_temp where id = $get ";
            $db = mysqli_query($conn, $query_db) or die(mysqli_error());
            $row_db = mysqli_fetch_assoc($db);
            ?>
            <div class="row">
                <div class="col-md-5">
                    <input type="text" class="form-control input-lg" onclick="changeid1()" name="Notes" id="notes" placeholder="Cooking Note" value="<?php echo $row_db['Notes'] ?>" id="search_field">
                </div>
                <div class="col-md-5">
                    <input type="text" name="Price" class="form-control input-lg "  id="price" placeholder="<?php echo $row_db['Price'] ?>" autofocus >
                </div>
                <input type="hidden" name="table" value="<?php echo $_GET['id']; ?>" />
                <div class="col-md-2">
                    <label>As a Starter </label>
                    <input type="checkbox" name="Priority" <?php
                    if ($row_db['Priority'] == 1) {
                        echo "checked";
                    }
                    ?> value="1" />
                </div>
            </div>
               <div id="keyboard1" style="background-color: #444"></div>
               
               

            <input type="hidden" name="id" value="<?php echo $get ?>" />
            <button type="submit" class="btn btn-primary btn-block ">Save changes</button>
        </form>

        <div class="row">
            <div class="col-md-6">
                <p>

                <form method="POST" action="modules/khanos/table/operations.php" id="test-form" enctype="multipart/form-data">
                     <input type="hidden" name="table" value="<?php echo $_GET['id']; ?>" />
                    <input type="hidden" name="sil" value="<?php echo $item ?>" />
                    <button  class="btn btn-danger btn-block" value=""><i class="fa fa-trash-o"></i> Delete Item</button>
                </form>
            </div>     


        </div>
   
</div>

 <script>
    $(document).ready(function () {
        $('#show').load('modules/khanos/table/items.php?id=<?php echo $_GET['id']?>')
$('#fiyat').load('modules/khanos/table/price.php?id=<?php echo $_GET['id'] ?>');
        $('.modal-khanos form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function (data) {
                    $('#show').load('modules/khanos/table/items.php?id=<?php echo $_GET['id']?>');
                    $('#fiyat').load('modules/khanos/table/price.php?id=<?php echo $_GET['id'] ?>');
                     $('#myModal').modal('hide');
                }
            });

        });

    });
    
    var y = document.getElementById("myDIV");
   y.style.display = "none";
   var z = document.getElementById("modalll");
z.style.display = "block";
</script>
