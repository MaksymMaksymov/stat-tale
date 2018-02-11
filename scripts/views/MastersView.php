<!DOCTYPE html>
<html lang="ru">
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<head>
    <title>Мастера Сказки</title>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <link rel="stylesheet" type="text/css" href="style/main.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <script src="js/jquery-3.1.0.min.js"></script>
</head>
<body>
  <?php
    include_once("View.php");
    if (isset($get_info) && $get_info -> masters != null) {
      $arr_classes = array(array("master","sort"),array("job_type","sort"),array("race","sort"),array("practic","sort"),array("cosmetic","sort"),array("city","sort"),array("integrity","sort"),array("power","sort"),array("power_in","sort"),array("power_out","sort"),"job",array("positive","sort"),array("negative","sort"));
      $arr_headers = array("Мастер","Профессия","Раса","Практическая","Косметическая","Город",array("type" => "img", "src" => "house.png", "alt" => "Целостность строения"),array("type" => "img", "src" => "power_ui.png", "alt" => "% влияния среди Мастеров в городе"),array("type" => "img", "src" => "power_in_ui.png", "alt" => "Влияние от Ближнего круга"),array("type" => "img", "src" => "power_out_ui.png", "alt" => "Влияние от Народа"),array("type" => "img", "src" => "job_ui.png", "alt" => "Проект"),array("type" => "img", "src" => "good.png", "alt" => "Остаток влияния соратникам"),array("type" => "img", "src" => "bad.png", "alt" => "Остаток влияния противникам"));
      $table_name = "master_table";
      $arr_out_masters = array();
      View::getContent();
      View::getForm("Masters",(isset($class_sorted)) ? $class_sorted : "",(isset($sort_direction)) ? $sort_direction : false,"Мастера Сказки");
      View::getHeaderTable($table_name,$arr_classes,$arr_headers);
      View::getModelData($get_info -> masters,$arr_out_masters);
      View::getTableData($table_name,$arr_classes,$arr_out_masters);
      View::getFooterTable();
      View::getFormEnd();
      View::getFooter();
    }
	?>
</body>
</html>