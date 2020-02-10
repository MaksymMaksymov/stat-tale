<!DOCTYPE html>
<html>
<?php 
  include_once("HeadView.html");
  include_once("View.php");
?>

<body>
  <?php
    View::getHeader();
    if (isset($get_info) && $get_info -> model_array != null) {
      View::getTablesPage($get_info,"voice_table");
    } else {
      Messages::showNoData("Voices");
    }
    View::getFooter();
	?>
</body>
</html>