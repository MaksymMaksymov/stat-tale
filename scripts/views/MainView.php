<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Мониторинг данных The Tale</title>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <script src="../../js/jquery-3.1.0.min.js"></script>
    <link rel="shortcut icon" href="../../img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../../style/main.css">
</head>
<body> 	
    <?php
        include_once("View.php");
        View::getContent();
    ?>
    <a href='http://the-tale.org?referral=56706'><img id='logo_img' src='../../img/logo.png' title='Сказка' style='margin: 10px 20px;'></a>
        <table class="main_table">
            <tr>
                <th colspan="2">
                    <h1>Мониторинг данных The Tale</h1>
                </th>
            </tr>
            <tr>
                <td rowspan="3">
                    <p><b>Сказка</b> - многопользовательская онлайн игра жанра <a href="https://ru.wikipedia.org/wiki/Zero_Player_Game">ZPG</a> с живым миром и своей историей.</p>
                    <p>Целью данного проекта является собрать воедино информацию об основных сущностях игровой Вселенной Пандоры. Проект создан благодаря открытому <a href="http://the-tale.org/guide/api">API</a> игры одним из Хранителей внутригровой гильдии <a href="http://the-tale.org/accounts/clans/87">Центральное Информационное Агентство Пандоры</a>.</p>
                    <p>Ранее Хранителями из гильдии <a href="http://the-tale.org/accounts/clans/51">Анархо-пацифизм</a> уже был создан подобный проект, целью которого было отображение данных о гильдиях и их членах. Ознакомиться можно <a href="http://ttgs.herokuapp.com/#/guilds">здесь</a>.</p>
                    <p>Рекомендуем также ознакомиться с актуальной <a href="http://tale-map.webtricks.pro/">картой Пандоры</a> от <a href="http://the-tale.org/accounts/6014">CrazyNiger</a>-а, а также с <a href="http://docs.the-tale.org/ru/stable/3rd_party.html">другими проектами</a> Хранителей.</p>
                </td>
                <td class="btn">
                	<form name = "actionform" action="PlacesController.php" method="POST">
                    <input id="Places" name="submit" tabindex="1" value="Список Городов" type="submit">
                    </form>
                </td>
            </tr>
            <tr>
                <td class="btn">
                <form name = "actionform" action="MastersController.php" method="POST">
        	       <input id="Masters" name="submit" tabindex="2" value="Список Мастеров" type="submit">
        	       </form>
                </td>
            </tr>
            <tr>
                <td class="btn">
                	<form name = "actionform" action="HeroesController.php" method="POST">
                    <input id="Heroes" name="submit" tabindex="3" value="Список известных Героев" type="submit">
                	</form>
                </td>
            </tr>
        </table>
	<br /> 	
  <?php
        View::getFooter();
  ?>
</body>
</html>