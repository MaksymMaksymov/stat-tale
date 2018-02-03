<?php
	include_once("GameModel.php");

	class PlacesModel extends GameModel   
    {
    	private $value;

		function __construct() {}
	
		public function setValues($arrayByCurl) {
			if (!CheckStatus::check($arrayByCurl)) return false;

			$arr_result = array();
			if (isset($arrayByCurl['data']['places'])) {                      
				for ($i = 1; $i <= count($arrayByCurl['data']['places']); $i++) {
					$place_array = array();	
					$place_array = $arrayByCurl['data']['places'][$i];
				    if (count($place_array) > 0) {
						array_push($arr_result, $place_array);
					}	
				}		
			}
			$this -> value = $arr_result;
			return true;
		} 

		public function getValues() {
			return $this -> value; 
		}

		public function getValuesByIndex($index) {
			return $this -> value[$index]; 
		}

		public function countValues() {
			return count($this -> value);
		}

		public function getId($index) {
			return $this -> value[$index]['id'];
		}

		public function getName($index) {
			return $this -> value[$index]['name'];
		}

		function __destruct() {}
	}
?>