<?php
	include_once("Controller.php");
	include_once("../models/MasterModel.php");

	class MastersController extends Controller {
		public $model_array;

        function __construct() {}
           
	    public function getArrayToParse($array_of_ids = null) {
            if ($array_of_ids == null) {
                $array_of_ids = MasterModel::dbSelectAll();
            }
            
            $this -> model_array = array();
            foreach ($array_of_ids as $key => $value) {
                $model = new MasterModel();
                if (!$model -> setValue($value)) continue;
                array_push($this -> model_array, $model);
            }
        }   

        public function sortByClass($class, $direction = "false") {
            if ($class == "") return;
            $arr_to_sort = array();
            foreach ($this -> model_array as $key => $value) {
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
    $get_info = new MastersController();
    $get_info -> main($arr_ids);
        
    include "../views/MastersView.php";
?>