﻿<!DOCTYPE html>
<html>
<?php 
	include_once("HeadView.html");
	include_once("View.php");
?>

<body> 	
    <?php 
        View::getHeader();
    ?>
		
	<div id="main">
		<p><b>Сказка</b> - многопользовательская онлайн игра жанра <a href="https://ru.wikipedia.org/wiki/Zero_Player_Game">ZPG</a> с живым миром и своей историей.</p>
	    <p>Целью <b>Сказка-stat</b> является сбор воедино информации об основных сущностях игровой Вселенной Пандоры, к которым можно отнести: Мастеров, героев, города и проекты Мастеров и городов. Проект создан благодаря открытому <a href="http://docs.the-tale.org/ru/stable/external_api/index.html">API</a> игры Сказки. </p>
	    <p>Разработано Хранителем <a href='http://the-tale.org/accounts/56706?referral=56706'>Mefi</a>, который является членом внутригровой гильдии <a href="http://the-tale.org/accounts/clans/87">Центральное Информационное Агентство Пандоры</a>.</p>
	    <p>Ранее Хранителями из гильдии <a href="http://the-tale.org/accounts/clans/51">Анархо-пацифизм</a> уже был создан <a href="http://ttgs.herokuapp.com/#/guilds">аналогичный проект</a>, целью которого было отображение данных о гильдиях и их членах.</p>
	    <p>Рекомендуем ознакомиться с актуальной <a href="http://tale-map.webtricks.pro/">картой Пандоры</a> от <a href="http://the-tale.org/accounts/6014">CrazyNiger</a>-а. Также наши разработки: <a href="#" onclick="getAction('map_ext_form')">расширенная</a> версия и <a href="#" onclick="getAction('map_hex_form')">гекс</a>-версия.</p>
		<p>Есть и <a href="http://docs.the-tale.org/ru/stable/3rd_party.html">другие проекты</a> иных Хранителей Пандоры.</p>
	</div>
	<form id="map_ext_form" action="MapController.php" method="POST">
		<input type="hidden" id="sprite" name="sprite" value="map_alternative_2_noborder_ext">
		<input type="hidden" id="mode" name="mode" value="extended">
	</form>
	<form id="map_hex_form" action="MapController.php" method="POST">
		<input type="hidden" id="sprite" name="sprite" value="maph">
		<input type="hidden" id="mode" name="mode" value="hex">
	</form>

  	<?php
        View::getFooter();
  	?>
</body>
</html>