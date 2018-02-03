<?php
	include_once("GameModel.php");

	class AngelModel extends GameModel   
    {
    	private $value;

		function __construct() {}   
		
		public function setValues($arrayByCurl) {
            if (!CheckStatus::check($arrayByCurl)) return false;

            $arr_result = array();  
            if (isset($arrayByCurl['data'])) {
                $arr_result = $arrayByCurl['data'];
            }
            $this -> value = $arr_result;
            return true; 	
		}
            
        public function getValues() {
          	return $this -> value; 
		}

		function __destruct() {}
	}
?>