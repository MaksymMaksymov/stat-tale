<!DOCTYPE html>
<html>
<?php 
  include_once("HeadView.html");
  include_once("View.php");
?>
<script type="text/javascript">
  $(document).ready(function() {
    $('.get-data').click(function() {
          document.getElementById('calc_form').submit();
        });
  });
  var area = document.getElementById("area");
  input.addEventListener("keyup", function(event) {
    if (event.keyCode === 13) {
      event.preventDefault();
      document.getElementById('calc_form').submit();
    }
  });
</script>
<body>
  <?php
    View::getHeader();
    echo "<form id='calc_form' action='' method='post'>";
    if (isset($places)) {
      View::getData($places);
      echo "<br /><br />";
    }
    if (isset($cur_place) && isset($cur_place_id)) {
      echo "<h3>".$cur_place."</h3><br />";
      echo "<input name='cur_place' type='hidden' value='".$cur_place_id."'></input>";
    }
    if (isset($get_info) && $get_info -> model_array != null) {
      $arr_classes = array("master","council","stability","freedom","production","transport","safety","culture","influence");
      $arr_headers = array("Мастер",array("type" => "img", "src" => "persons_ui.png", "alt" => "Совет"),array("type" => "img", "src" => "stability_ui.png", "alt" => "Стабильность"),array("type" => "img", "src" => "freedom_ui.png", "alt" => "Свобода"),array("type" => "img", "src" => "production_ui.png", "alt" => "Производство"),array("type" => "img", "src" => "transport_ui.png", "alt" => "Транспорт"),array("type" => "img", "src" => "safety_ui.png", "alt" => "Безопасность"),array("type" => "img", "src" => "culture_ui.png", "alt" => "Культура"),array("type" => "img", "src" => "power_ui.png", "alt" => "Влияния"));
      View::getTablePageCalc($get_info,"calc_table",$arr_classes,$arr_headers);
      if ($get_info -> prodution_adds != null) {
        echo "<br /><center>";
        $arr_classes = array("size","economy","trade","area","roads","building","caravan");
        $arr_headers = array(array("type" => "img", "src" => "size_ui.png", "alt" => "Размер"),array("type" => "img", "src" => "money_ui.png", "alt" => "Экономика"),array("type" => "img", "src" => "money_ui2.png", "alt" => "Торговля"),array("type" => "img", "src" => "area_ui.png", "alt" => "Площадь владений"),array("type" => "img", "src" => "road_ui.png", "alt" => "Клетки дорог"),array("type" => "img", "src" => "house.png", "alt" => "Количество строений"),array("type" => "img", "src" => "caravan_ui.png", "alt" => "Караван"));
        View::getHeaderTable("prod_add",$arr_classes,$arr_headers);
        $tbl_data[0] = $get_info -> prodution_adds;
        View::getTableData("prod_add",$arr_classes,$tbl_data);
        unset($tbl_data);
        View::getFooterTable();
        echo "</center>";
      }
      if ($get_info -> prodution_adds != null) {
        echo "<br /><center>";
        $arr_classes = array("production","stability");
        $arr_headers = array(array("type" => "img", "src" => "production_ui.png", "alt" => "Корректировка производства"),array("type" => "img", "src" => "stability_ui.png", "alt" => "Корректировка cтабильности"));
        View::getHeaderTable("correction_add",$arr_classes,$arr_headers);
        $tbl_data[0] = $get_info -> correction;
        View::getTableData("correction_add",$arr_classes,$tbl_data);
        View::getFooterTable();
        echo "</center>";
      }
    } else {
      Messages::showNoData("Places");
    }
    View::getFormEnd();
    View::getFooter();
	?>
</body>
</html>