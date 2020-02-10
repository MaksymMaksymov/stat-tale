<?php
	include_once("../../config/cfg.php");
	include_once("../models/GetInfoByURLModel.php");
	include_once("../models/HeroModel.php");
    include_once("../models/RatingModel.php");
    include_once("../models/VoiceModel.php");

    $rate = new RatingModel();
    $rate -> setPoweredIds();
	$array_of_ids = $rate -> dbSelectAll();
    $array_all_ids = HeroModel::dbSelectAll();

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

            // how to optimise it?
            // find & delete value
            foreach ($array_all_ids as $all_key => $all_value) {
                if ($value == $all_value) {
                    unset($array_all_ids[$all_key ]);
                    break;
                }
            }
        }
    }

    // update ex-donaters
    if (isset($array_all_ids)) {
        foreach ($array_all_ids as $key => $value) {
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