<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Мониторинг данных The Tale</title>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <script src="js/jquery-3.1.0.min.js"></script>
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="style/main.css">
    <script type="text/javascript">
        $(document).ready(function() {
            $('.control').click(function() {
                var hid_controller = document.getElementById('controller');
                var get_id = $(this).attr('id');
                $(hid_controller).attr('value', get_id);
            });
        });
    </script>
</head>
<body> 	
    <?php
        include_once("views/View.php");
        View::getContent();
    ?>
    <a href='http://the-tale.org?referral=56706'><img id='logo_img' src='img/logo.png' title='Сказка' style='margin: 10px 20px;'></a>
	<form name = "actionform" action="" method="POST">
        <input id="controller" type="hidden" name="controller" value="Places" />
        <input type="hidden" name="refresh" value="true" />
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
                    <p>В будущем, возможно, будет добавлен дополнительный функционал.</p>
                    <br />
                    <p>Ранее Хранителями из гильдии <a href="http://the-tale.org/accounts/clans/51">Анархо-пацифизм</a> уже был создан подобный проект, целью которого было отображение данных о гильдиях и их членах. Ознакомиться можно <a href="http://ttgs.herokuapp.com/#/guilds">здесь</a>.</p>
                    <br /><br />
                    <p><b>P.S.</b> Автор не силён во frontend-е и не считает свой код идеальным, поэтому просьба все пожелания по улучшению данного проекта направлять в личку.</p>
                </td>
                <td class="btn">
                    <input id="Places" name="submit" tabindex="1" class="control" value="Список Городов" type="submit">
                </td>
            </tr>
            <tr>
                <td class="btn">
        	       <input id="Masters" name="submit" tabindex="2" class="control" value="Список Мастеров" type="submit">
                </td>
            </tr>
            <tr>
                <td class="btn">
                    <input id="Heroes" name="submit" tabindex="3" class="control" value="Список известных Героев" type="submit">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <b>Внимание! Загрузка данных может занять до нескольких минут!</b>
                </td>
            </tr>
        </table>
	</form>
	<br /> 	
  <?php
        View::getFooter();
  ?>
</body>
</html>