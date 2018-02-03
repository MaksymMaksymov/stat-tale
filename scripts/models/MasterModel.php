<?php
	include_once("GameModel.php");

	class MasterModel extends GameModel   
    {
    	private $value;
        private $th_rule;

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
            $result = array();
            $row_result = "";
            $index = 0;

            $result[$index] = array();
            if (isset($this -> value['id']) && isset($this -> value['name'])) {
                $raw_result['type'] = "url";
                $raw_result['href'] = "http://the-tale.org/game/persons/".$this -> value['id'];
                $raw_result['value'] = $this -> value['name'];
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['building'])) ? Dictionary::getProfession($this -> value['building']['type']) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['race'])) ? Dictionary::getRace($this -> value['race']) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['attributes'])) ? $this -> value['attributes']['effects'][0]['name'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['attributes'])) ? $this -> value['attributes']['effects'][1]['name'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['place']['id']) && isset($this -> value['place']['name'])) {
                $raw_result['type'] = "url";
                $raw_result['href'] = "http://the-tale.org/game/places/".$this -> value['place']['id'];
                $raw_result['value'] = $this -> value['place']['name'];
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['building']) && $this -> value['building'] != null) ? Round($this -> value['building']['integrity']*100,2) : "–";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['politic_power']['power']['fraction'])) ? Round($this -> value['politic_power']['power']['fraction']*100,0)."%" : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['politic_power']['power']['inner'])) {
                $raw_result['type'] = "text";
                $raw_result['value'] = Round($this -> value['politic_power']['power']['inner']['value'],0);
                $raw_result['title'] = Round($this -> value['politic_power']['power']['inner']['fraction']*100,0)."%";
            }
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['politic_power']['power']['outer'])) {
                $raw_result['type'] = "text";
                $raw_result['value'] = Round($this -> value['politic_power']['power']['outer']['value'],0);
                $raw_result['title'] = Round($this -> value['politic_power']['power']['outer']['fraction']*100,0)."%";
            }
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = "";
            if (isset($this -> value['job'])) {
                $heroes_bonus = ($this -> value['job']['effect'] >= 6 && $this -> value['job']['effect'] <= 9) ? true : false;
                if ($heroes_bonus)
                    $raw_result .= "<b>";
                $raw_result .= $this -> value['job']['name'];
                if ($heroes_bonus)
                    $raw_result .= "</b>";
            }
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['job'])) ? $this -> value['job']['power_required'] - $this -> value['job']['positive_power'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['job'])) ? $this -> value['job']['power_required'] - $this -> value['job']['negative_power'] : "";
            array_push($result[$index], $raw_result);

            return $result; 
		}

        public function getMaster() {
            if (isset($this -> value['id'])) {
                return $this -> value['id'];
            }
            return 0;
        }

        public function getJobType() {

            return 0;
        }

        public function getRace() {

            return 0;
        }

        public function getPractic() {
            if (isset($this -> value['attributes'])) {
                $result['name'] = $this -> value['attributes']['effects'][0]['name'];
                //$result['race'] =
                return $result;
            }
            return 0;
        }

        public function getCosmetic() {
            if (isset($this -> value['attributes'])) {
                $result['name'] = $this -> value['attributes']['effects'][1]['name'];
                //$result['race'] =
                return $result;
            }
            return 0;
        }

        public function getCity() {
            if (isset($this -> value['place']['name']) && isset($this -> value['politic_power']['power']['fraction'])) {
                $result['name'] = $this -> value['place']['name'];
                $result['power'] = Round($this -> value['politic_power']['power']['fraction']*100,1);
                return $result;
            }
            return 0;
        }

        public function getIntegrity() {
            if (isset($this -> value['building']) && $this -> value['building'] != null) {
                return Round($this -> value['building']['integrity']*100,2);
            }
            return -1;
        }

        public function getPower() {
            if (isset($this -> value['politic_power']['power']['fraction'])) {
                return Round($this -> value['politic_power']['power']['fraction']*100,1);
            }
            return 0;
        }

        public function getPowerIn() {
            if (isset($this -> value['politic_power']['power']['inner'])) {
                return Round($this -> value['politic_power']['power']['inner']['value'],1);
            }
            return 0;
        }

        public function getPowerOut() {
            if (isset($this -> value['politic_power']['power']['outer'])) {
                return Round($this -> value['politic_power']['power']['outer']['value'],1);
            }
            return 0;
        }

        public function getPositive() {
            if (isset($this -> value['job'])) {
                $result['effect'] = ($this -> value['job']['effect'] > 9) ? 10 - $this -> value['job']['effect'] : $this -> value['job']['effect'];
                $result['positive'] = -($this -> value['job']['power_required'] - $this -> value['job']['positive_power']);
                return $result;
            }
            return 0;
        }

        public function getNegative() {
            if (isset($this -> value['job'])) {
                $result['effect'] = ($this -> value['job']['effect'] > 9) ? 10 - $this -> value['job']['effect'] : $this -> value['job']['effect'];
                $result['negative'] = -($this -> value['job']['power_required'] - $this -> value['job']['negative_power']);
                return $result;
            }
            return 0;
        }

		function __destruct() {}
	}
?>