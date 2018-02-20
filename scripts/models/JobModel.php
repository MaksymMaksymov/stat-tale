<?php
	include_once("MasterModel.php");
    include_once("PlaceModel.php");

	class JobModel
    {
    	private $value;

		function __construct() {}   
		
		public function setValue($id, $type) {
            $mysqli = $GLOBALS["mysqli"];
            if ($type == "master")
                $result = $mysqli->query("SELECT * FROM masters WHERE id=".$id);
            else
                $result = $mysqli->query("SELECT * FROM places WHERE id=".$id);
            
            $ids = array();
            if (!isset($result) || $result === false) {
                return false;
            } else {
                while ($row = $result->fetch_assoc()) {
                    $tmp['type'] = $type;
                    $tmp['data'] = $row;
                    $this -> value = $tmp;
                }
            } 

            return true;
		}

        public function getValues() {
            $result = array();
            $row_result = "";
            $index = 0;

            $result[$index] = array();
            if (isset($this -> value['type']) && isset($this -> value['data']['id']) && isset($this -> value['data']['name'])) {
                if ($this -> value['type'] == "master")
                    $raw_result = PrepareToView::createUrl("http://the-tale.org/game/persons/".$this -> value['data']['id'],$this -> value['data']['name']);
                else
                    $raw_result = PrepareToView::createUrl("http://the-tale.org/game/places/".$this -> value['data']['id'],$this -> value['data']['name']);
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = "";
            if (isset($this -> value['data']['job_effect']) && isset($this -> value['data']['job_name'])) {
                $heroes_bonus = ($this -> value['data']['job_effect'] >= 6 && $this -> value['data']['job_effect'] <= 9) ? true : false;
                if ($heroes_bonus)
                    $raw_result .= "<b>";
                $raw_result .= $this -> value['data']['job_name'];
                if ($heroes_bonus)
                    $raw_result .= "</b>";
            }
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['data']['positive_job'])) ? $this -> value['data']['positive_job'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['data']['negative_job'])) ? $this -> value['data']['negative_job'] : "";
            array_push($result[$index], $raw_result);

            return $result; 
		}

        public function getPositive() {
            if (isset($this -> value['data']['job_effect'])) {
                $result['effect'] = ($this -> value['data']['job_effect'] > 9) ? 10 - $this -> value['data']['job_effect'] : $this -> value['data']['job_effect'];
                $result['positive'] = -($this -> value['data']['positive_job']);
                return $result;
            }
            return 0;
        }

        public function getNegative() {
            if (isset($this -> value['data']['job_effect'])) {
                $result['effect'] = ($this -> value['data']['job_effect'] > 9) ? 10 - $this -> value['data']['job_effect'] : $this -> value['data']['job_effect'];
                $result['negative'] = -($this -> value['data']['negative_job']);
                return $result;
            }
            return 0;
        }

		function __destruct() {}
	}
?>