<?php
	include_once("Controller.php");
	include_once("../models/HeroModel.php");
	include_once("../models/GetInfoByURLModel.php");

	class HeroesController extends Controller {
		public $model_array;

        function __construct() {}
           
	    public function getArrayToParse($array_of_ids = null) {
            if ($array_of_ids == null) { 
                $array_of_ids = HeroModel::dbSelectAll();
            }
            
            $this -> model_array = array();
            foreach ($array_of_ids as $key => $value) {
                $model = new HeroModel();
                if (!$model -> setValue($value)) continue;
                array_push($this -> model_array, $model);
            }
        }

        public function sortByClass($class, $direction = "false") {
            if ($class == "") return;
            $arr_to_sort = array();
            foreach ($this -> model_array as $key => $value) {
                switch ($class) {
                    case "angel": 
                        $arr_to_sort[$key] = $value -> getAngel();
                        break;
                    case "might": 
                        $arr_to_sort[$key] = $value -> getMight();
                        break;
                    case "clan": 
                        $arr_to_sort[$key] = $value -> getClan();
                        break;
                    case "hero": 
                        $arr_to_sort[$key] = $value -> getHero();
                        break;
                    case "race": 
                        $arr_to_sort[$key] = $value -> getRace();
                        break;
                    case "level": 
                        $arr_to_sort[$key] = $value -> getLevel();
                        break;
                    case "power": 
                        $arr_to_sort[$key] = $value -> getPower();
                        break;
                    case "companion": 
                        $arr_to_sort[$key] = $value -> getCompanion();
                        break;
                    case "habits": 
                        $arr_to_sort[$key] = $value -> getHabits();
                        break;
                    case "money": 
                        $arr_to_sort[$key] = $value -> getMoney();
                        break;
                    case "virt_strength": 
                        $arr_to_sort[$key] = $value -> getVirtualStrength();
                        break;
                    case "strength": 
                        $arr_to_sort[$key] = $value -> getStrength();
                        break;
                    case "physic": 
                        $arr_to_sort[$key] = $value -> getPhysic();
                        break;
                    case "magic": 
                        $arr_to_sort[$key] = $value -> getMagic();
                        break;
                    case "equiment": 
                        $arr_to_sort[$key] = $value -> getEquiment();
                        break;
                    case "speed": 
                        $arr_to_sort[$key] = $value -> getSpeed();
                        break;
                    case "initiative": 
                        $arr_to_sort[$key] = $value -> getInitiative();
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
    $get_info = new HeroesController();
    $get_info -> main($arr_ids);
        
    include "../views/HeroesView.php";
?>