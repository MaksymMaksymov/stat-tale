<?php
	include_once("../scripts/controllers/Controller.php");
	include_once("../scripts/models/PlacesAddModel.php");
	include_once("../scripts/models/GetInfoByURLModel.php");

	class PlacesController extends Controller {
		public $places;

        function __construct() {}
           
	    public function getArrayToParse($array_of_ids = null) {
            $curlGet = new GetInfoByURLModel(); 
            $arr_all_places = $curlGet -> getInformation($GLOBALS["PLACES_URL_LIST"]); 

            $arr_places = new PlacesModel();
            if (!$arr_places -> setValues($arr_all_places)) return;

            $this -> places = array();
            if ($arr_places -> countValues() > 0) { 
                for ($i = 0; $i < $arr_places -> countValues(); $i++) {
                	if (($array_of_ids == null) || (in_array($arr_places -> getId($i), $array_of_ids))) {
                		$place = new PlacesAddModel();
                		$tmp_url =  str_replace("<place>", $arr_places -> getId($i), $GLOBALS["PLACES_URL_DETAILED"]);
                        $arr_place_add_info = $curlGet -> getInformation($tmp_url); 
                        
                        if (!$place -> setValues($arr_place_add_info)) continue;
                        $place -> setCity($arr_places -> getValuesByIndex($i));
                        array_push($this -> places, $place);
                        unset($place);
                    }
    			}
            } else {
                Messages::showNoData("Places");
            }
            unset($curlGet); 
        }

        public function sortByClass($class, $direction = "false") {
            if ($class == "") return;
            $arr_to_sort = array();
            foreach ($this -> places as $key => $value) {
                switch ($class) {
                    case "specialization": 
                        $arr_to_sort[$key] = $value -> getSpecialization();
                        break;
                    case "city": 
                        $arr_to_sort[$key] = $value -> getCity();
                        break;
                    case "race": 
                        $arr_to_sort[$key] = $value -> getRace();
                        break;
                    case "stability": 
                        $arr_to_sort[$key] = $value -> getStability();
                        break;
                    case "freedom": 
                        $arr_to_sort[$key] = $value -> getFreedom();
                        break;
                    case "production": 
                        $arr_to_sort[$key] = $value -> getProduction();
                        break;
                    case "transport": 
                        $arr_to_sort[$key] = $value -> getTransport();
                        break;
                    case "safety": 
                        $arr_to_sort[$key] = $value -> getSafety();
                        break;
                    case "time": 
                        $arr_to_sort[$key] = $value -> getTime();
                        break;
                    case "culture": 
                        $arr_to_sort[$key] = $value -> getCulture();
                        break;
                    case "area": 
                        $arr_to_sort[$key] = $value -> getArea();
                        break;
                    case "size": 
                        $arr_to_sort[$key] = $value -> getSize();
                        break;
                    case "economy": 
                        $arr_to_sort[$key] = $value -> getEconomy();
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
            $new_places = array();
            foreach ($arr_to_sort as $key => $value) {
                $place = $this -> places[$key];
                array_push($new_places, $place);
            }
            unset($this -> places);
            $this -> places = $new_places;
        }

        function __destruct() {}
    }
?>