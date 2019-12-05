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
    $get_info -> main($arr_ids);
        
    include "../views/PlacesView.php";
?>