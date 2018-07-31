<?php

require_once '../../../modules/db/db.php';
$item = $_GET['item'];
?>

<div class="modal-khanos">
         <form method="POST" action="modules/table/operations.php" id="test-form" enctype="multipart/form-data">
      <button type="submit" class="btn btn-primary btn-block">Delete ?</button>
         <input type="hidden" name="sil" value="<?php echo $item ?>" />
     <input type="hidden" name="item" value="<?php echo $item ?>" />
   </form>

</div>
 <script>
    $(document).ready(function () {


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