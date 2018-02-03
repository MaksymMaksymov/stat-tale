<?php
	include_once("config/cfg.php");
	include_once("scripts/controllers/Controller.php");
	include_once("scripts/models/MasterModel.php");
	include_once("scripts/models/GetInfoByURLModel.php");

	class MastersController extends Controller {
		public $masters;

        function __construct() {}
           
	    public function getArrayToParse($array_of_ids = null) {
            if ($array_of_ids == null)  {
                Messages::showNoData("Masters");
            } else {
                $curlGet = new GetInfoByURLModel();
                $this -> masters = array();
                foreach ($array_of_ids as $key => $value) {
                    $master = new MasterModel();
                    $tmp_url =  str_replace("<person>", $value, $GLOBALS["MASTERS_URL"]);
                    $arr_masters = $curlGet -> getInformation($tmp_url); 
                    if (!$master -> setValues($arr_masters)) continue;
                    array_push($this -> masters, $master);
                    unset($master);
                }
                unset($curlGet); 
            }
        }   

        public function sortByClass($class, $direction = "false") {
            if ($class == "") return;
            $arr_to_sort = array();
            foreach ($this -> masters as $key => $value) {
                switch ($class) {
                    case "master": 
                        $arr_to_sort[$key] = $value -> getMaster();
                        break;
                    case "job_type": 
                        $arr_to_sort[$key] = $value -> getJobType();
                        break;
                    case "race": 
                        $arr_to_sort[$key] = $value -> getRace();
                        break;
                    case "practic": 
                        $arr_to_sort[$key] = $value -> getPractic();
                        break;
                    case "cosmetic": 
                        $arr_to_sort[$key] = $value -> getCosmetic();
                        break;
                    case "city": 
                        $arr_to_sort[$key] = $value -> getCity();
                        break;
                    case "integrity": 
                        $arr_to_sort[$key] = $value -> getIntegrity();
                        break;
                    case "power": 
                        $arr_to_sort[$key] = $value -> getPower();
                        break;
                    case "power_in": 
                        $arr_to_sort[$key] = $value -> getPowerIn();
                        break;
                    case "power_out": 
                        $arr_to_sort[$key] = $value -> getPowerOut();
                        break;
                    case "positive": 
                        $arr_to_sort[$key] = $value -> getPositive();
                        break;
                    case "negative": 
                        $arr_to_sort[$key] = $value -> getNegative();
                        break;
                }    
            }
            if ($direction == "false")
                asort($arr_to_sort);
            else 
                arsort($arr_to_sort);
            $new_masters = array();
            foreach ($arr_to_sort as $key => $value) {
                $master = $this -> masters[$key];
                array_push($new_masters, $master);
            }
            unset($this -> masters);
            $this -> masters = $new_masters;
        }

        function __destruct() {}
    }
?>