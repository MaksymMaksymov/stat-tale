	<script
	  src="http://code.jquery.com/jquery-3.3.1.min.js"
	  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	  crossorigin="anonymous">
	</script>

	<script type="text/javascript">
		function getAction(form_id) {
			document.getElementById(form_id).submit();
		}
	</script>

	<header>
		<div class = "header-logo">
			<a href = "http://the-tale.org?referral=56706"><img src = "../../img/logo.png"></a>
			<h1>Мониторинг данных The Tale</h1>	
		</div>
	</header>
	
	<div class="bar">
		<nav id="desktop">
			<ul>
				<li onclick="getAction('main_form')"><a href="#">Главная</a></li>
				<li onclick="getAction('places_form')"><a href="#">Города</a></li>
				<li onclick="getAction('masters_form')"><a href="#">Мастера</a></li>
				<li onclick="getAction('heroes_form')"><a href="#">Герои</a></li>
				<!--<li><a href="#">Проекты</a></li>-->
				<!--<li><a href="#">Голоса</a></li>-->
				<form id = "main_form" action="MainController.php" method="POST"></form>
				<form id = "places_form" action="PlacesController.php" method="POST"></form>
				<form id = "masters_form" action="MastersController.php" method="POST"></form>
				<form id = "heroes_form" action="HeroesController.php" method="POST"></form>
			</ul>
		</nav>
	</div>
	<div id="burg">
		<a href="#">
			<span class="bar" id ="top"></span>
			<span class="bar" id ="middle"></span>
			<span class="bar" id ="botton"></span>
		</a>
	</div>
	<div class="mobile-tab">
		<nav id="mobile">
			<ul>
				<li onclick="getAction('main_form')"><a href="#">Главная</a></li>
				<li onclick="getAction('places_form')"><a href="#">Города</a></li>
				<li onclick="getAction('masters_form')"><a href="#">Мастера</a></li>
				<li onclick="getAction('heroes_form')"><a href="#">Герои</a></li>
			</ul>
		</nav>
	</div>