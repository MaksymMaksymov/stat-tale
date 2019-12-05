<!DOCTYPE html>
<html>
<?php 
  include_once("HeadView.html");
  include_once("View.php");
?>

<script type="text/javascript">
  $(document).ready(function() {
    var persons_width = $('td.race').width();
    if (persons_width < 220) {
      document.getElementsByClassName('race')[0].style.width = '220px';
    }
  });
</script>

<body>
  <?php
    View::getHeader();
    if (isset($get_info) && $get_info -> model_array != null) {
      $arr_classes = array("frontier",array("specialization","sort"),array("city","sort"),array("race","sort"),array("stability","sort"),array("freedom","sort"),array("production","sort"),array("transport","sort"),array("safety","sort"),array("culture","sort"),array("area","sort"),array("size","sort"),array("economy","sort"),array("time","sort"),array("power","sort"),array("power_in","sort"),array("power_out","sort"));
      $arr_headers = array("","Специализация","Город",array("type" => "img", "src" => "persons_ui.png", "alt" => "Демография и Совет"),array("type" => "img", "src" => "stability_ui.png", "alt" => "Стабильность"),array("type" => "img", "src" => "freedom_ui.png", "alt" => "Свобода"),array("type" => "img", "src" => "production_ui.png", "alt" => "Производство"),array("type" => "img", "src" => "transport_ui.png", "alt" => "Транспорт"),array("type" => "img", "src" => "safety_ui.png", "alt" => "Безопасность"),array("type" => "img", "src" => "culture_ui.png", "alt" => "Культура"),array("type" => "img", "src" => "area_ui.png", "alt" => "Площадь владений"),array("type" => "img", "src" => "size_ui.png", "alt" => "Размер"),array("type" => "img", "src" => "money_ui.png", "alt" => "Экономика"),array("type" => "img", "src" => "money_ui2.png", "alt" => "Торговля"),array("type" => "img", "src" => "power_ui.png", "alt" => "% влияния среди городов"),array("type" => "img", "src" => "power_in_ui.png", "alt" => "Влияние от Ближнего круга"),array("type" => "img", "src" => "power_out_ui.png", "alt" => "Влияние от Народа"));
      View::getTablePage($get_info,"place_table",$arr_classes,$arr_headers);
    } else {
      Messages::showNoData("Places");
    }
    View::getFooter();
	?>
</body>
</html>