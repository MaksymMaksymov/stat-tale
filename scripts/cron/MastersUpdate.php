<?php
	include_once("../../config/cfg.php");
    include_once("../../config/masters.php");
	include_once("../models/GetInfoByURLModel.php");
	include_once("../models/MasterModel.php");

	$array_of_ids = $GLOBALS["ARRAY_OF_MASTER_IDS"];
    if (isset($array_of_ids))
        foreach ($array_of_ids as $key => $value) {
        	$master = new MasterModel();
            $tmp_url = str_replace("<person>", $value, $GLOBALS["MASTERS_URL"]);
            $arr_masters = array();
            $curlGet = new GetInfoByURLModel();
            $arr_masters = $curlGet -> getInformation($tmp_url);
            $result = $master -> dbUpdateValues($arr_masters);
        }

    header('Location: ../controllers/MastersController.php?updated', true);
?>