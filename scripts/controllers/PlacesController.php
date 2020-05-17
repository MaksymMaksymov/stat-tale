<?php
	include_once("Controller.php");
	include_once("../models/PlaceModel.php");
	include_once("../models/GetInfoByURLModel.php");

	class PlacesController extends Controller {
		public $model_array;

        function __construct() {}
           
	    public function getArrayToParse($array_of_ids = null) {
            if ($array_of_ids == null) {
                $array_of_ids = PlaceModel::dbSelectAll();
            }
            
            $this -> model_array = array();
            foreach ($array_of_ids as $key => $value) {
                $model = new PlaceModel();
                if (!$model -> setValue($value)) continue;
                array_push($this -> model_array, $model);
            }
        }

        public function sortByClass($class, $direction = "false") {
            if ($class == "") return;
            $arr_to_sort = array();
            foreach ($this -> model_array as $key => $value) {
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
                }    
            }
            if ($direction == "false")
                asort($arr_to_sort);
            else 
                arsort($arr_to_sort);
            $new_array = array();
            foreach ($arr_to_sort as $key => $value) {
                $model = $this -> model_array[$key];
                array_push($new_array, $model);
            }
            unset($this -> model_array);
            $this -> model_array = $new_array;
        }

        function __destruct() {}
    }

    $arr_ids = null;
    $get_info = new PlacesController();

    $get_tags = ""; 
    if (isset($_GET["cia"])) {
        $arr_ids[16] = 16;
        $arr_ids[17] = 17;
        $arr_ids[18] = 18;
        $arr_ids[22] = 22;
        $arr_ids[52] = 52;
        $arr_ids[53] = 53;
        $get_tags .= "&cia";
    }
    if (isset($_GET["tn"])) {
        $arr_ids[2] = 2;
        $arr_ids[3] = 3;
        $arr_ids[9] = 9;
        $arr_ids[10] = 10;
        $arr_ids[11] = 11;
        $arr_ids[12] = 12;
        $arr_ids[13] = 13;
        $arr_ids[14] = 14;
        $arr_ids[15] = 15;
        $arr_ids[16] = 16;
        $arr_ids[17] = 17;
        $arr_ids[18] = 18;
        $arr_ids[22] = 22;
        $arr_ids[44] = 44;
        $get_tags .= "&tn";
    }
    if (isset($_GET["corp"])) {
        $arr_ids[3] = 3;
        $arr_ids[13] = 13;
        $arr_ids[16] = 16;
        $arr_ids[22] = 22;
        $arr_ids[38] = 38;
        $arr_ids[39] = 39;
        $arr_ids[46] = 46;
        $arr_ids[48] = 48;
        $arr_ids[49] = 49;
        $get_tags .= "&corp";
    }
    if (isset($_GET["ar"])) {
        $arr_ids[2] = 2;
        $arr_ids[8] = 8;
        $arr_ids[9] = 9;
        $arr_ids[10] = 10;
        $arr_ids[20] = 20;
        $arr_ids[25] = 25;
        $arr_ids[28] = 28;
        $arr_ids[29] = 29;
        $arr_ids[30] = 30;
        $arr_ids[42] = 42;
        $arr_ids[43] = 43;
        $get_tags .= "&ar";
    }
    if (isset($_GET["orda"])) {
        $arr_ids[1] = 1;
        $arr_ids[4] = 4;
        $arr_ids[5] = 5;
        $arr_ids[6] = 6;
        $arr_ids[7] = 7;
        $arr_ids[32] = 32;
        $get_tags .= "&orda";
    }

    if (isset($_GET["ars"])) {
        $arr_ids[21] = 21;
        $arr_ids[24] = 24;
        $arr_ids[27] = 27;
        $arr_ids[35] = 35;
        $arr_ids[36] = 36;
        $arr_ids[37] = 37;
        $arr_ids[47] = 47;
        $arr_ids[55] = 55;
        $get_tags .= "&ars";
    }

    $get_info -> main($arr_ids);
        
    include "../views/PlacesView.php";
?>