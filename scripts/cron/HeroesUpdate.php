<?php
	include_once("../../config/cfg.php");
    include_once("../../config/heroes.php");
	include_once("../models/GetInfoByURLModel.php");
	include_once("../models/HeroModel.php");

	$array_of_ids = $GLOBALS["ARRAY_OF_HERO_IDS"];
    if (isset($array_of_ids))
        foreach ($array_of_ids as $key => $value) {
        	$hero = new HeroModel();
            $tmp_url = str_replace("<account>", $value, $GLOBALS["HEROES_URL"]);
            $arr_heroes = array();
            $arr_angels = array();
            $curlGet = new GetInfoByURLModel();
            $arr_heroes = $curlGet -> getInformation($tmp_url);
            $tmp_url =  str_replace("<account>", $value, $GLOBALS["ANGELS_URL"]);
            $arr_angels = $curlGet -> getInformation($tmp_url);
            $arr_heroes['data']['angel'] = $arr_angels['data'];
            $result = $hero -> dbUpdateValues($arr_heroes);
        }
?>