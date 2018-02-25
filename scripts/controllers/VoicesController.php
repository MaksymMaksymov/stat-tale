<?php
	include_once("Controller.php");
    include_once("../models/PlaceModel.php");
    include_once("../models/VoiceModel.php");

	class VoicesController extends Controller {
		public $model_array;

        function __construct() {}
           
	    public function getArrayToParse($array_of_ids = null) {
            $this -> model_array = array();
            $array_of_ids = PlaceModel::dbSelectAll();
            foreach ($array_of_ids as $key => $value) {
                $model = new VoiceModel();
                if (!$model -> setValue($value)) continue;
                array_push($this -> model_array, $model);
            }
        }   

        public function sortByClass($class, $direction = "false") 
        {}

        function __destruct() {}
    }

    $get_info = new VoicesController();
    $get_info -> main();
        
    include "../views/VoicesView.php";
?>