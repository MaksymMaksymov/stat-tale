<!DOCTYPE html>
<html>
<?php 
  include_once("HeadView.html");
  include_once("View.php");
?>

<script type="text/javascript">
  $(document).ready(function() {
    var quest_width = $('td.barter').width();
    if (quest_width < 300) {
      document.getElementsByClassName('barter')[0].style.width = '300px';
    }
  });
</script>

<body>
  <?php
    View::getHeader();
    if (isset($get_info) && $get_info -> model_array != null) {
      $arr_classes = array(array("angel","sort"),array("might","sort"),array("clan","sort"),array("hero","sort"),array("race","sort"),array("level","sort"),array("power","sort"),array("companion","sort"),array("habits","sort"),array("money","sort"),array("strength","sort"),array("physic","sort"),array("magic","sort"),array("equiment","sort"),array("preference","sort"),array("speed","sort"),array("initiative","sort"),"position","quest");
      $arr_headers = array("Хранитель",array("type" => "img", "src" => "might_ui.png", "alt" => "Могущество"),array("type" => "img", "src" => "clan_ui.png", "alt" => "Гильдия"),"Герой","Раса",array("type" => "img", "src" => "level_ui.png", "alt" => "Уровень"),"Влияние","Спутник","Черта",array("type" => "img", "src" => "money_ui.png", "alt" => "Золото"),array("type" => "img", "src" => "strength_ui.png", "alt" => "Сила"),array("type" => "img", "src" => "physic_ui.png", "alt" => "Физическая Сила"),array("type" => "img", "src" => "magic_ui.png", "alt" => "Магическая сила"),array("type" => "img", "src" => "equiment_ui.png", "alt" => "Уровень экипировки"),array("type" => "img", "src" => "preference_ui.png", "alt" => "Средняя полезность экипировки"),array("type" => "img", "src" => "speed_ui.png", "alt" => "Скорость движения"),array("type" => "img", "src" => "initiative_ui.png", "alt" => "Инициатива"),array("type" => "img", "src" => "position_ui.png", "alt" => "Местоположение героя"),"Задание");
      View::getTablePage($get_info,"hero_table",$arr_classes,$arr_headers);
    } else {
      Messages::showNoData("Heroes");
    }
    View::getFooter();
	?>
</body>
</html>