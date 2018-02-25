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
                if ($this -> value['type'] == "master") {
                    $raw_result = PrepareToView::createUrl("http://the-tale.org/game/persons/".$this -> value['data']['id'],$this -> value['data']['name']);
                    array_push($result[$index], $raw_result);
                    unset($raw_result);
                    $raw_result = " (";
                    array_push($result[$index], $raw_result);
                    unset($raw_result);
                    $raw_result = PrepareToView::createUrl("http://the-tale.org/game/places/".$this -> value['data']['city_id'],PlaceModel::getNameById($this -> value['data']['city_id']));
                    array_push($result[$index], $raw_result);
                    unset($raw_result);
                    $raw_result = ")";
                }
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
                $tmp = $raw_result;
                $heroes_bonus = ($this -> value['data']['job_effect'] >= 6 && $this -> value['data']['job_effect'] <= 9) ? true : false;
                if ($heroes_bonus)
                    $tmp .= "<b>";
                $tmp .= $this -> value['data']['job_name'];
                if ($heroes_bonus)
                    $tmp .= "</b>";
                $raw_result = PrepareToView::createText(Dictionary::getJobEffect($this -> value['data']['job_effect']), $tmp);
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

        public function getName() {
            if (isset($this -> value['type']) && isset($this -> value['data']['id']) && isset($this -> value['data']['name'])) {
                if ($this -> value['type'] == "master") {
                    $result['name'] = PlaceModel::getNameById($this -> value['data']['city_id']);
                    $result['order'] = 1;
                }
                else {
                    $result['name'] = $this -> value['data']['name'];
                    $result['order'] = 0;
                }
                return $result;
            }
            return 0;
        }

        public function getPositive() {
            if (isset($this -> value['data']['job_effect'])) {
                $result['effect'] = ($this -> value['data']['job_effect'] > 9) ? 10 - $this -> value['data']['job_effect'] : $this -> value['data']['job_effect'];
                $result['positive'] = $this -> value['data']['positive_job'];
                return $result;
            }
            return 0;
        }

        public function getNegative() {
            if (isset($this -> value['data']['job_effect'])) {
                $result['effect'] = ($this -> value['data']['job_effect'] > 9) ? 10 - $this -> value['data']['job_effect'] : $this -> value['data']['job_effect'];
                $result['negative'] = $this -> value['data']['negative_job'];
                return $result;
            }
            return 0;
        }

		function __destruct() {}
	}
?>