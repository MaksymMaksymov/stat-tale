<?php
	//TEMPLATE the-tale.org/<resource_path>/api/<method_name>?api_version=<method_version>&api_client=<client_id>&<method_arguments>;
	//resource_path — путь к игровой сущности (аккаунту, игре, рейтингам и т.п.);
	//method_name — название метода, может отсутствовать;
	//method_version — версия метода (в стандартном формате через точку: 1.0, 1.1 и т.п.);
	//client_id — идентификатор клиентского приложения;
	//method_arguments — аргументы, необходимые методу.
	
	$HOST = "https://the-tale.org/";
	$API_CLIENT = "stattale-1";

	$PLACES_PATH = "game/places/api/list";
	$PLACES_VERSION = "1.1";
	$PLACES_URL_LIST = $HOST.$PLACES_PATH."?api_version=".$PLACES_VERSION."&api_client=".$API_CLIENT;

	$PLACES_DETAILED_PATH = "game/places/<place>/api/show";
	$PLACES_DETAILED_VERSION = "2.2";
	$PLACES_URL_DETAILED = $HOST.$PLACES_DETAILED_PATH."?api_version=".$PLACES_DETAILED_VERSION."&api_client=".$API_CLIENT;

	$MASTERS_PATH = "game/persons/<person>/api/show";
	$MASTERS_VERSION = "1.1";
	$MASTERS_URL =  $HOST.$MASTERS_PATH."?api_version=".$MASTERS_VERSION."&api_client=".$API_CLIENT;

	$HEROES_PATH = "game/api/info";
	$HEROES_VERSION = "1.9";
	$HEROES_URL =  $HOST.$HEROES_PATH."?api_version=".$HEROES_VERSION."&api_client=".$API_CLIENT."&account=<account>";

	$ANGELS_PATH = "accounts/<account>/api/show";
	$ANGELS_VERSION = "1.0";
	$ANGELS_URL =  $HOST.$ANGELS_PATH."?api_version=".$ANGELS_VERSION."&api_client=".$API_CLIENT;

	$RATE_POWER = $HOST."game/ratings/politics_power";

	$dbHost='localhost';
	$dbName='tale_db';
	$dbUser='root';
	$dbPass='';

	$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
	$mysqli->query("SET NAMES utf8 COLLATE utf8_general_ci");
	if (mysqli_connect_errno()) {
		$dbError = true;
	    exit();
	}
?>