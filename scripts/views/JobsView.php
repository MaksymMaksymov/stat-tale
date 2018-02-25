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
      $arr_classes = array(array("name","sort"),"job",array("positive","sort"),array("negative","sort"));
      $arr_headers = array("Исполнитель",array("type" => "img","src" => "job_ui.png", "alt" => "Проект"),array("type" => "img", "src" => "good.png", "alt" => "Остаток влияния соратникам"),array("type" => "img", "src" => "bad.png", "alt" => "Остаток влияния противникам"));
      View::getTwoTablePage($get_info,"job_table",$arr_classes,$arr_headers);
    } else {
      Messages::showNoData("Jobs");
    }
    View::getFooter();
	?>
</body>
</html>