<?php
	include_once("Controller.php");
	include_once("../models/MasterModel.php");
    include_once("../models/PlaceModel.php");
    include_once("../models/JobModel.php");

	class JobsController extends Controller {
		public $model_array;

        function __construct() {}
           
	    public function getArrayToParse($array_of_ids = null) {
            $this -> model_array = array();
            $array_of_ids = MasterModel::dbSelectAll();
            foreach ($array_of_ids as $key => $value) {
                $model = new JobModel();
                if (!$model -> setValue($value, "master")) continue;
                array_push($this -> model_array, $model);
            }

            $array_of_ids = PlaceModel::dbSelectAll();
            foreach ($array_of_ids as $key => $value) {
                $model = new JobModel();
                if (!$model -> setValue($value, "place")) continue;
                array_push($this -> model_array, $model);
            }
        }   

        public function sortByClass($class, $direction = "false") {
            if ($class == "") return;
            $arr_to_sort = array();
            foreach ($this -> model_array as $key => $value) {
                switch ($class) {
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

    $get_info = new JobsController();
    $get_info -> main();
        
    include "../views/JobsView.php";
?>