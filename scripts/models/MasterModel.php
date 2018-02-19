<?php
	include_once("GameModel.php");
    include_once("PlaceModel.php");

	class MasterModel extends GameModel   
    {
    	private $value;

		function __construct() {}   
		
		public function setValue($id) {
            $arr_result = array();  
            $this -> value["id"] = $id;
            return $this -> dbSelectById();	
		}
        
        public static function dbSelectAll()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT id FROM masters");
            
            if ($result === false) {
                return false;
            } else {
                $ids = array();
                while ($row = $result->fetch_assoc()) {
                    array_push($ids, $row['id']);
                }
                return $ids;
            } 
        }

        public function dbUpdateValues($arrayByCurl) {
            if (!CheckStatus::check($arrayByCurl)) return false;

            $arr_result = array();  
            if (isset($arrayByCurl['data'])) {
                $arr_result = $arrayByCurl['data'];
            }
            $this -> value['id'] = $arr_result['id'];

            if ($this -> dbSelectById()) {
                return $this -> dbUpdate($arr_result);
            } else 
                return $this -> dbInsert($arr_result);
        }

        public function dbSelectById()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT * FROM masters WHERE id=".$this -> value["id"]);
            $row = $result->fetch_assoc();
            if (isset($row)) {
                $this -> value = $row;
                return true;
            } else
                return false;
        }

        public function dbInsert($arrayData) {
            if ($arrayData == null || !isset($arrayData['id'])) return false;

            $db_names = "id";
            $db_values = $arrayData['id'];
            $db_names .= (!isset($arrayData['name'])) ? "" : ",name";
            $db_values .= (!isset($arrayData['name'])) ? "" : ",N'".str_replace("'","\'",$arrayData['name'])."'";
            $db_names .= ",city_id";
            $db_values .= ",N'".$arrayData['place']['id']."'";
            $db_names .= (!isset($arrayData['profession'])) ? "" : ",profession";
            $db_values .= (!isset($arrayData['profession'])) ? "" : ",".$arrayData['profession'];
            $db_names .= (!isset($arrayData['gender'])) ? "" : ",gender";
            $db_values .= (!isset($arrayData['gender'])) ? "" : ",".$arrayData['gender'];
            $db_names .= (!isset($arrayData['race'])) ? "" : ",race";
            $db_values .= (!isset($arrayData['race'])) ? "" : ",".$arrayData['race'];
            $db_names .= (!isset($arrayData['attributes']['effects'][0]['name'])) ? "" : ",practic";
            $db_values .= (!isset($arrayData['attributes']['effects'][0]['name'])) ? "" : ",N'".str_replace("'","\'",$arrayData['attributes']['effects'][0]['name'])."'";
            $db_names .= (!isset($arrayData['attributes']['effects'][1]['name'])) ? "" : ",cosmetic";
            $db_values .= (!isset($arrayData['attributes']['effects'][1]['name'])) ? "" : ",N'".str_replace("'","\'",$arrayData['attributes']['effects'][1]['name'])."'";
            $db_names .= ",building";
            $db_values .= (!isset($arrayData['building'])) ? ",false" : ",true";
            $db_names .= (!isset($arrayData['building']['integrity'])) ? "" : ",integrity";
            $db_values .= (!isset($arrayData['building']['integrity'])) ? "" : ",".Round($arrayData['building']['integrity']*100,2);
            $db_names .= (!isset($arrayData['politic_power']['power']['fraction'])) ? "" : ",politic_power";
            $db_values .= (!isset($arrayData['politic_power']['power']['fraction'])) ? "" : ",".Round($arrayData['politic_power']['power']['fraction']*100,0);
            $db_names .= (!isset($arrayData['politic_power']['power']['outer']['value'])) ? "" : ",power_outer";
            $db_values .= (!isset($arrayData['politic_power']['power']['outer']['value'])) ? "" : ",".$arrayData['politic_power']['power']['outer']['value'];
            $db_names .= (!isset($arrayData['politic_power']['power']['outer']['fraction'])) ? "" : ",power_outer_fraction";
            $db_values .= (!isset($arrayData['politic_power']['power']['outer']['fraction'])) ? "" : ",".Round($arrayData['politic_power']['power']['outer']['fraction']*100,0);
            $db_names .= (!isset($arrayData['politic_power']['power']['inner']['fraction'])) ? "" : ",power_inner";
            $db_values .= (!isset($arrayData['politic_power']['power']['inner']['value'])) ? "" : ",".$arrayData['politic_power']['power']['inner']['value'];
            $db_names .= (!isset($arrayData['politic_power']['power']['inner']['fraction'])) ? "" : ",power_inner_fraction";
            $db_values .= (!isset($arrayData['politic_power']['power']['inner']['fraction'])) ? "" : ",".Round($arrayData['politic_power']['power']['inner']['fraction']*100,0);
            $db_names .= (!isset($arrayData['job']['effect'])) ? "" : ",job_effect";
            $db_values .= (!isset($arrayData['job']['effect'])) ? "" : ",".$arrayData['job']['effect'];
            $db_names .= (!isset($arrayData['job']['name'])) ? "" : ",job_name";
            $db_values .= (!isset($arrayData['job']['name'])) ? "" : ",N'".str_replace("'","\'",$arrayData['job']['name'])."'";
            if (isset($arrayData['job']['power_required']) && isset($arrayData['job']['positive_power'])) {
                $tmp = $arrayData['job']['power_required'] - $arrayData['job']['positive_power'];
                $db_values .= ",".$tmp;
                $db_names .= ",positive_job";
            }
            if (isset($arrayData['job']['power_required']) && isset($arrayData['job']['negative_power'])) {
                $tmp = $arrayData['job']['power_required'] - $arrayData['job']['negative_power'];
                $db_values .= ",".$tmp;
                $db_names .= ",negative_job";
            }

            $mysqli = $GLOBALS["mysqli"];
            $query = $mysqli->query("INSERT INTO masters(".$db_names.") VALUES (".$db_values.")");

            if ($query)
                RegisterUpdates::dbUpdateValues("masters");

            return $query;
        }

        public function dbUpdate($arrayData) {
            if ($arrayData == null || !isset($this -> value['id'])) return false;

            $db_values = "id=".$this -> value['id'];
            $db_values .= (!isset($arrayData['name'])) ? "" : ",name=N'".str_replace("'","\'",$arrayData['name'])."'";
            $db_values .= (!isset($arrayData['place']['id'])) ? "" : ",city_id=".$arrayData['place']['id'];
            $db_values .= (!isset($arrayData['profession'])) ? "" : ",profession=".$arrayData['profession'];
            $db_values .= (!isset($arrayData['gender'])) ? "" : ",gender=".$arrayData['gender'];
            $db_values .= (!isset($arrayData['race'])) ? "" : ",race=".$arrayData['race'];
            $db_values .= (!isset($arrayData['attributes']['effects'][0]['name'])) ? "" : ",practic=N'".str_replace("'","\'",$arrayData['attributes']['effects'][0]['name'])."'";
            $db_values .= (!isset($arrayData['attributes']['effects'][1]['name'])) ? "" : ",cosmetic=N'".str_replace("'","\'",$arrayData['attributes']['effects'][1]['name'])."'";
            $db_values .= (!isset($arrayData['building'])) ? ",building=false" : ",building=true";
            $db_values .= (!isset($arrayData['building']['integrity'])) ? "" : ",integrity=".Round($arrayData['building']['integrity']*100,2);
            $db_values .= (!isset($arrayData['politic_power']['power']['fraction'])) ? "" : ",politic_power=".Round($arrayData['politic_power']['power']['fraction']*100,0);
            $db_values .= (!isset($arrayData['politic_power']['power']['outer']['value'])) ? "" : ",power_outer=".$arrayData['politic_power']['power']['outer']['value'];
            $db_values .= (!isset($arrayData['politic_power']['power']['outer']['fraction'])) ? "" : ",power_outer_fraction=".Round($arrayData['politic_power']['power']['outer']['fraction']*100,0);
            $db_values .= (!isset($arrayData['politic_power']['power']['inner']['value'])) ? "" : ",power_inner=".$arrayData['politic_power']['power']['inner']['value'];
            $db_values .= (!isset($arrayData['politic_power']['power']['inner']['fraction'])) ? "" : ",power_inner_fraction=".Round($arrayData['politic_power']['power']['inner']['fraction']*100,0);
            $db_values .= (!isset($arrayData['job']['effect'])) ? "" : ",job_effect=".$arrayData['job']['effect'];
            $db_values .= (!isset($arrayData['job']['name'])) ? "" : ",job_name=N'".str_replace("'","\'",$arrayData['job']['name'])."'";
            $db_values .= (!isset($arrayData['job'])) ? "" : ",positive_job=".($arrayData['job']['power_required'] - $arrayData['job']['positive_power']);
            $db_values .= (!isset($arrayData['job'])) ? "" : ",negative_job=".($arrayData['job']['power_required'] - $arrayData['job']['negative_power']);

            $mysqli = $GLOBALS["mysqli"];
            $query = $mysqli->query("UPDATE masters SET ".$db_values." WHERE id=".$this -> value["id"]);

            if ($query)
                RegisterUpdates::dbUpdateValues("masters");

            return $query;
        }

        public function getValues() {
            $result = array();
            $row_result = "";
            $index = 0;

            $result[$index] = array();
            if (isset($this -> value['id']) && isset($this -> value['name'])) {
                $raw_result = PrepareToView::createUrl("http://the-tale.org/game/persons/".$this -> value['id'],$this -> value['name']);
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['profession'])) ? Dictionary::getProfession($this -> value['profession']) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['gender']) && isset($this -> value['race'])) ? Dictionary::getRace($this -> value['gender'],  $this -> value['race']) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['practic'])) ? $this -> value['practic'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['cosmetic'])) ? $this -> value['cosmetic'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['city_id'])) {
                $raw_result = PrepareToView::createUrl("http://the-tale.org/game/places/".$this -> value['city_id'],PlaceModel::getNameById($this -> value['city_id']));
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['building']) && $this -> value['building'] == true) ? $this -> value['integrity'] : "–";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['politic_power'])) ? $this -> value['politic_power']."%" : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['power_inner'])) {
                $raw_result = PrepareToView::createText($this -> value['power_inner_fraction']."%",$this -> value['power_inner']);
            }
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['power_outer'])) {
                $raw_result = PrepareToView::createText($this -> value['power_outer_fraction']."%",$this -> value['power_outer']);
            }
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = "";
            if (isset($this -> value['job_effect']) && isset($this -> value['job_name'])) {
                $heroes_bonus = ($this -> value['job_effect'] >= 6 && $this -> value['job_effect'] <= 9) ? true : false;
                if ($heroes_bonus)
                    $raw_result .= "<b>";
                $raw_result .= $this -> value['job_name'];
                if ($heroes_bonus)
                    $raw_result .= "</b>";
            }
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['positive_job'])) ? $this -> value['positive_job'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['negative_job'])) ? $this -> value['negative_job'] : "";
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
        	if (isset($this -> value['profession']) && isset($this -> value['gender']) && isset($this -> value['race'])) {
        		$result['profession'] = $this -> value['profession'];
                $result['race'] = $this -> value['race'];
                $result['gender'] = $this -> value['gender'];
                return $result;
            }
            return 0;
        }

        public function getRace() {
        	if (isset($this -> value['gender']) && isset($this -> value['race'])) {
                $result['race'] = $this -> value['race'];
                $result['gender'] = $this -> value['gender'];
                return $result;
            }
            return 0;
        }

        public function getPractic() {
            if (isset($this -> value['practic'])) {
                $result['name'] = $this -> value['practic'];
                $result['race'] = $this -> value['race'];
                return $result;
            }
            return 0;
        }

        public function getCosmetic() {
            if (isset($this -> value['cosmetic'])) {
                $result['name'] = $this -> value['cosmetic'];
                $result['race'] = $this -> value['race'];
                return $result;
            }
            return 0;
        }

        public function getCity() {
            if (isset($this -> value['city_id'])) {
                $result['name'] = PlaceModel::getNameById($this -> value['city_id']);
                return $result;
            }
            return 0;
        }

        public function getIntegrity() {
            if (isset($this -> value['building']) && $this -> value['building'] == true) {
                return $this -> value['integrity'];
            }
            return -1;
        }

        public function getPower() {
            if (isset($this -> value['politic_power'])) {
                return $this -> value['politic_power'];
            }
            return 0;
        }

        public function getPowerIn() {
            if (isset($this -> value['power_inner'])) {
                return $this -> value['power_inner'];
            }
            return 0;
        }

        public function getPowerOut() {
            if (isset($this -> value['power_outer'])) {
                return $this -> value['power_outer'];
            }
            return 0;
        }

        public function getPositive() {
            if (isset($this -> value['job_effect'])) {
                $result['effect'] = ($this -> value['job_effect'] > 9) ? 10 - $this -> value['job_effect'] : $this -> value['job_effect'];
                $result['positive'] = -($this -> value['positive_job']);
                return $result;
            }
            return 0;
        }

        public function getNegative() {
            if (isset($this -> value['job_effect'])) {
                $result['effect'] = ($this -> value['job_effect'] > 9) ? 10 - $this -> value['job_effect'] : $this -> value['job_effect'];
                $result['negative'] = -($this -> value['negative_job']);
                return $result;
            }
            return 0;
        }

		function __destruct() {}
	}
?>