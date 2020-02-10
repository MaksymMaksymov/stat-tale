<?php
    include_once("Controller.php");
    include_once("../models/EmissaryModel.php");

    class EmissariesController extends Controller {
        public $model_array;

        function __construct() {}
           
        public function getArrayToParse($array_of_ids = null) {
            if ($array_of_ids == null) {
                $array_of_ids = EmissaryModel::dbSelectAll();
            }
            
            $this -> model_array = array();
            foreach ($array_of_ids as $key => $value) {
                $model = new EmissaryModel();
                if (!$model -> setValue($value)) continue;
                array_push($this -> model_array, $model);
            }
        }   

        public function sortByClass($class, $direction = "false") {
            if ($class == "") return;
            $arr_to_sort = array();
            foreach ($this -> model_array as $key => $value) {
                switch ($class) {
                    case "emissary": 
                        $arr_to_sort[$key] = $value -> getEmissary();
                        break;
                    case "status": 
                        $arr_to_sort[$key] = $value -> getStatus();
                        break;
                    case "race": 
                        $arr_to_sort[$key] = $value -> getRace();
                        break;
                    case "clan": 
                        $arr_to_sort[$key] = $value -> getClan();
                        break;
                    case "city": 
                        $arr_to_sort[$key] = $value -> getCity();
                        break;
                    case "ability": 
                        $arr_to_sort[$key] = $value -> getAbility();
                        break;
                    case "power": 
                        $arr_to_sort[$key] = $value -> getPower();
                        break;
                    case "health": 
                        $arr_to_sort[$key] = $value -> getHealth();
                        break;
                    case "warfare": 
                        $arr_to_sort[$key] = $value -> getWarfare();
                        break;
                    case "cultural_science": 
                        $arr_to_sort[$key] = $value -> getCultural_science();
                        break;
                    case "politival_science": 
                        $arr_to_sort[$key] = $value -> getPolitival_science();
                        break;
                    case "religious_studies": 
                        $arr_to_sort[$key] = $value -> getReligious_studies();
                        break;
                    case "sociology": 
                        $arr_to_sort[$key] = $value -> getSociology();
                        break;
                    case "covert_operations": 
                        $arr_to_sort[$key] = $value -> getCovert_operations();
                        break;
                    case "technologies": 
                        $arr_to_sort[$key] = $value -> getTechnologies();
                        break;
                    case "economy": 
                        $arr_to_sort[$key] = $value -> getEconomy();
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
    $get_info = new EmissariesController();
    $get_info -> main($arr_ids);
        
    include "../views/EmissariesView.php";
?>