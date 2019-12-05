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
      $arr_classes = array(array("status","sort"), array("emissary","sort"),array("race","sort"),array("clan","sort"),array("power","sort"),array("health","sort"),array("city","sort"),array("ability","sort"),array("warfare","sort"),array("cultural_science","sort"),array("politival_science","sort"),array("religious_studies","sort"),array("sociology","sort"),array("covert_operations","sort"),array("technologies","sort"),array("economy","sort"));
      $arr_headers = array("Статус","Эмиссар","Раса",array("type" => "img", "src" => "clan_ui.png", "alt" => "Гильдия"),"Влияние",array("type" => "img", "src" => "alive.png", "alt" => "Гильдия"),"Город", "Способности","B","K","П","Р","С","Та","Те","Э");
      View::getTablePage($get_info,"emissary_table",$arr_classes,$arr_headers);
    } else {
      Messages::showNoData("Emissaries");
    }
    View::getFooter();
	?>
</body>
</html>