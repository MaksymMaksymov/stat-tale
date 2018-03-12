<?php
	include_once("../../config/cfg.php");
	include_once("../models/GetInfoByURLModel.php");
	include_once("../models/HeroModel.php");
    include_once("../models/RatingModel.php");
    include_once("../models/VoiceModel.php");

    $rate = new RatingModel();
    $rate -> setPoweredIds();
	$array_of_ids = $rate -> dbSelectAll();

    if (isset($array_of_ids)) {
        VoiceModel::dbDeleteNotAllPlacesHistory($array_of_ids);
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

            VoiceModel::dbDeletePlacesHistory($arr_angels['data']['id']);
            $result = $hero -> dbUpdateValues($arr_heroes);
        }
    }
?>