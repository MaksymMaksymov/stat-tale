<?php 
  include_once("HeadView.html");
  include_once("View.php");
?>

<script type="text/javascript">
  $(document).ready(function() {
    var barter_width = $('td.barter').width();
    if (barter_width < 200) {
      document.getElementsByClassName('barter')[0].style.width = '200px';
    }
    var persons_width = $('td.persons').width();
    if (persons_width < 220) {
      document.getElementsByClassName('persons')[0].style.width = '220px';
    }
  });
</script>

<body>
  <?php
    if (isset($get_info) && $get_info -> places != null) {
      $arr_classes = array("frontier",array("specialization","sort"),array("city","sort"),array("race","sort"),"persons",array("stability","sort"),array("freedom","sort"),array("production","sort"),array("transport","sort"),array("safety","sort"),array("time","sort"),array("culture","sort"),array("area","sort"),array("size","sort"),array("economy","sort"),array("power","sort"),array("power_in","sort"),array("power_out","sort"),"barter","job",array("positive","sort"),array("negative","sort"));
      $arr_headers = array("","Специализация","Город","Демография",array("type" => "img", "src" => "persons_ui.png", "alt" => "Совет"),array("type" => "img", "src" => "stability_ui.png", "alt" => "Стабильность"),array("type" => "img", "src" => "freedom_ui.png", "alt" => "Свобода"),array("type" => "img", "src" => "production_ui.png", "alt" => "Производство"),array("type" => "img", "src" => "transport_ui.png", "alt" => "Транспорт"),array("type" => "img", "src" => "safety_ui.png", "alt" => "Безопасность"),array("type" => "img", "src" => "time_ui.png", "alt" => "Время перемещения одной клетки со скоростью героя 0.1"),array("type" => "img", "src" => "culture_ui.png", "alt" => "Культура"),array("type" => "img", "src" => "area_ui.png", "alt" => "Площадь владений"),array("type" => "img", "src" => "size_ui.png", "alt" => "Размер"),array("type" => "img", "src" => "money_ui.png", "alt" => "Экономика"),array("type" => "img", "src" => "power_ui.png", "alt" => "% влияния среди городов"),array("type" => "img", "src" => "power_in_ui.png", "alt" => "Влияние от Ближнего круга"),array("type" => "img", "src" => "power_out_ui.png", "alt" => "Влияние от Народа"),array("type" => "img", "src" => "barter_ui.png", "alt" => "Обмены"),array("type" => "img", "src" => "job_ui.png", "alt" => "Проект"),array("type" => "img", "src" => "good.png", "alt" => "Остаток влияния соратникам"),array("type" => "img", "src" => "bad.png", "alt" => "Остаток влияния противникам"));
      $table_name = "master_table";
      $arr_out_places = array();
      View::getContent();
      include_once("MenuView.php");
      View::getForm("Places",(isset($class_sorted)) ? $class_sorted : "",(isset($sort_direction)) ? $sort_direction : false,"Города Сказки");
      View::getHeaderTable($table_name,$arr_classes,$arr_headers);
      View::getModelData($get_info -> places,$arr_out_places);
      View::getTableData($table_name,$arr_classes,$arr_out_places);
      View::getFooterTable();
      View::getFormEnd();
      View::getFooter();
    }
	?>
</body>
</html>