<!DOCTYPE html>
<html>
<?php 
	include_once("HeadView.html");
	include_once("View.php");
?>

<body> 	
    <?php 
        View::getContent();
        include_once("MenuView.php");
    ?>
		
	<section>
		<div class = "content">
			<p><b>Сказка</b> - многопользовательская онлайн игра жанра <a href="https://ru.wikipedia.org/wiki/Zero_Player_Game">ZPG</a> с живым миром и своей историей.</p>
	        <p>Целью данного проекта является собрать воедино информацию об основных сущностях игровой Вселенной Пандоры. Проект создан благодаря открытому <a href="http://the-tale.org/guide/api">API</a> игры одним из Хранителей внутригровой гильдии <a href="http://the-tale.org/accounts/clans/87">Центральное Информационное Агентство Пандоры</a>.</p>
	        <p>Ранее Хранителями из гильдии <a href="http://the-tale.org/accounts/clans/51">Анархо-пацифизм</a> уже был создан подобный проект, целью которого было отображение данных о гильдиях и их членах. Ознакомиться можно <a href="http://ttgs.herokuapp.com/#/guilds">здесь</a>.</p>
	        <p>Рекомендуем также ознакомиться с актуальной <a href="http://tale-map.webtricks.pro/">картой Пандоры</a> от <a href="http://the-tale.org/accounts/6014">CrazyNiger</a>-а, а также с <a href="http://docs.the-tale.org/ru/stable/3rd_party.html">другими проектами</a> Хранителей.</p>
		</div>
	</section>

  	<?php
        View::getFooter();
  	?>
</body>
</html>