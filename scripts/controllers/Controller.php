<?php
	include_once("../../config/cfg.php");
	abstract class Controller {
		public $model_array;
        abstract public function GetArrayToParse($array_of_ids = null);
        abstract public function sortByClass($class, $direction = "false");

        public function main($arr_ids = null) {
        	$this -> getArrayToParse($arr_ids);
    		$this -> sortByClass((isset($_REQUEST['class'])) ? $_REQUEST['class'] : "",(isset($_REQUEST['direction'])) ? $_REQUEST['direction'] : "false");
        }
    }
?>