<?php
	include_once("../../config/cfg.php");
	include_once("../models/GetInfoByURLModel.php");
	include_once("../models/PlaceModel.php");

    $curlGet = new GetInfoByURLModel(); 
    $arr_all_places = $curlGet -> getInformation($GLOBALS["PLACES_URL_LIST"]); 

    if (!CheckStatus::check($arr_all_places)) 
        exit();

    $array_of_ids = array();
    if (isset($arr_all_places['data']['places'])) {
        foreach ($arr_all_places['data']['places'] as $key => $value)  {
            $place_array = array();
            $place_array['id'] = $value['id'];
            $place_array['specialization'] = $value['specialization'];
            if (count($place_array) > 0) {
                array_push($array_of_ids, $place_array);
            }
        }       
    }


    foreach ($array_of_ids as $key => $value) {
        $place = new PlaceModel();
        $tmp_url =  str_replace("<place>", $value['id'], $GLOBALS["PLACES_URL_DETAILED"]);
        $arr_places = $curlGet -> getInformation($tmp_url);
        $arr_places['data']['specialization'] = $value['specialization'];
        $result = $place -> dbUpdateValues($arr_places);
    }
?>