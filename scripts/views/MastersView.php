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
      $arr_classes = array(array("master","sort"),array("job_type","sort"),array("race","sort"),array("practic","sort"),array("cosmetic","sort"),array("city","sort"),array("integrity","sort"),array("power","sort"),array("power_in","sort"),array("power_out","sort"));
      $arr_headers = array("Мастер","Профессия","Раса","Практическая","Косметическая","Город",array("type" => "img", "src" => "house.png", "alt" => "Целостность строения"),array("type" => "img", "src" => "power_ui.png", "alt" => "% влияния среди Мастеров в городе"),array("type" => "img", "src" => "power_in_ui.png", "alt" => "Влияние от Ближнего круга"),array("type" => "img", "src" => "power_out_ui.png", "alt" => "Влияние от Народа"));
      View::getTablePage($get_info,"master_table",$arr_classes,$arr_headers);
    } else {
      Messages::showNoData("Masters");
    }
    View::getFooter();
	?>
</body>
</html>