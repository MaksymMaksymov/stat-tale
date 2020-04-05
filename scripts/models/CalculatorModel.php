<?php
    include_once("PlaceModel.php");
    include_once("MasterModel.php");

	class CalculatorModel 
    {
    	public $value;

		function __construct() {}

		public function setValue($id, $professions, $current) {
            $this -> value["id"] = $id;
            $this -> value["professions"] = $professions;
            $this -> value["current"] = $current;
            if (isset($this -> value["current"]) && $this -> value["current"] != -1) {
            	if ($this -> dbSelectProfessionsById()) {
            		return $this;	
            	}
            }
            return $this;	
		}

		public function setValueMaster($current) {
            if (isset($this -> value["current"]) && $this -> value["current"] != -1) {
            	if ($this -> dbSelectMastersByProfId()) {
            		$this -> value["master"]["current"] = $current;
            		return $this;
            	}
            }
            return $this;	
		}

		public function setValueSpec($id, $current) {
            $this -> value["id"] = $id;
            $this -> value["specs"] = $this -> dbSelectAllSpecIdsAndNames(); 
            $this -> value["current"] = $current;
            if (isset($this -> value["current"]) && $this -> value["current"] != -1) {
            	if ($this -> dbSelectSpecById()) {
            		return $this;	
            	}
            }
            return $this;	
		}

		public function setValueDefault($id) {
            $this -> value["id"] = $id;
            $this -> value["profession"] = array('stability' => 100,
												 'freedom' => 100,
												 'production' => -10, // default landscape 
												 'transport' => 0,
												 'safety' => 0,
												 'culture' => 100,
	        								);
            return $this;
		}

		public function setValueSum($profession) {
			$this -> value["profession"]["stability"] += $profession["stability"];
			$this -> value["profession"]["freedom"] += $profession["freedom"];
			$this -> value["profession"]["production"] += $profession["production"];
			$this -> value["profession"]["transport"] += $profession["transport"];
			$this -> value["profession"]["safety"] += $profession["safety"];
			$this -> value["profession"]["culture"] += $profession["culture"];
            return $this;	
		}

		public function setTradeOrEconomy($current) {
			$this -> value["profession"]["production"] += $current*33;
		}

		public function setSize($current) {
			$this -> value["profession"]["production"] -= $current*100;
			$this -> value["profession"]["transport"] -= $current*5;
		}

		public function setArea($current) {
			$math = (sqrt($current) - 1) / 2 * 2;
			if (isset($_POST["frontier"])) {
				$math /= 2;
			}
			$this -> value["profession"]["production"] += Round($math*34,0);
		}

		public function setRoads($current) {
			$this -> value["profession"]["production"] -= $current*10;
		}

		public function setBuilding($current) {
			$this -> value["profession"]["production"] -= $current*40;
		}

		public function setCaravan($current) {
			$this -> value["profession"]["production"] -= $current*2;
		}

		public function setCoreectionValues($production, $stability) {
			$this -> value["profession"]["production"] += $production;
			$this -> value["profession"]["stability"] += $stability;
		}

		public function setValueStabilityCorrection() {
			if ($this -> value["profession"]["stability"] >= 100) {
				$this -> value["profession"]["stability"] = 100;
			} else {
				$delta_Stability = 100 - $this -> value["profession"]["stability"];
				$this -> value["profession"]["freedom"] += Round(0.75*$delta_Stability,2);
				$this -> value["profession"]["production"] -= 2*$delta_Stability;
				$this -> value["profession"]["transport"] -= Round(0.75*$delta_Stability,2);
				$this -> value["profession"]["safety"] -= Round(0.15*$delta_Stability,2);
				$this -> value["profession"]["culture"] -= $delta_Stability;
			}
			
            return $this;	
		}

		public function dbSelectSpecById()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT * FROM specs WHERE id='".$this -> value["current"]."'");
            $row = $result->fetch_assoc();
            if (isset($row)) {
                $this -> value["profession"] = $row; // spec has similar to professions fields
                return true;
            } else
                return false;
        }

        public function dbSelectAllSpecIdsAndNames()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT id, name FROM specs");
            if ($result === false) {
                return false;
            } else {
                $ids["-1"] = "";
                while ($row = $result->fetch_assoc()) {
                    $ids[$row['id']] = $row['name'];
                }
                return $ids;
            }
        }

		public function dbSelectProfessionsById()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT * FROM professions WHERE id=".($this -> value["current"] + 1));
            $row = $result->fetch_assoc();
            if (isset($row)) {
                $this -> value["profession"] = $row;
                return true;
            } else
                return false;
        }

		public static function dbSelectAllProfessionIds()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT id FROM professions ORDER BY id ASC");
            if ($result === false) {
                return false;
            } else {
                $ids = array("-1" => "");
                while ($row = $result->fetch_assoc()) {
                    $profession = Dictionary::getProfession($row['id'] - 1);
                    array_push($ids, $profession);
                }
                return $ids;
            }
        }

		public function dbSelectMastersByProfId()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT id, name, gender, race, city_id FROM masters WHERE profession=".$this -> value["current"]);
            $this -> value["master"]["list"] = array();
            $this -> value["master"]["list"]["-1"] = "";
            while ($row = $result->fetch_assoc()) {
                $place = PlaceModel::getNameById($row["city_id"]);
                $this -> value["master"]["list"][$row["id"]] = Dictionary::getRace($row["gender"],$row["race"])." ".$row["name"]." из ".$place."-a";
            }
            return true;
        }

		public function getValues() {
            $result = array();
            $row_result = "";
            $index = 0;

            $result[$index] = array();
            if (isset($this -> value['master']['list']) && isset($this -> value['master']['current'])) {
            	$raw_result = PrepareToView::createSelect("master".$this -> value['id'], $this -> value['master']['list'], $this -> value['master']['current'], "Выберете Мастера");
        	} else if(isset($this -> value['spec_points'])) {
        		if ($this -> value['spec_points'] < 50) {
        			$raw_result = PrepareToView::createTextRed("Очки специализации",Round($this -> value['spec_points'],2));
        		} else {
        			$raw_result = PrepareToView::createText("Очки специализации",Round($this -> value['spec_points'],2));
        		}
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

			$index++;
            $result[$index] = array();
            if (isset($this -> value['id'])) {
            	if (isset($this -> value['professions']) && isset($this -> value['current'])) {
                	$raw_result = PrepareToView::createSelect("profession".$this -> value['id'], $this -> value['professions'], $this -> value['current'], "Выберете профессию");
            	} else if (isset($this -> value['specs']) && isset($this -> value['current'])) {
                		$raw_result = PrepareToView::createSelect("spec", $this -> value['specs'], $this -> value['current'], "Выберете специализацию");
                } else if ($this -> value['id'] == 8) {
                		$raw_result = "Итого";
                }
            } else
            	$raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['profession']['stability'])) {
            	$raw_result = PrepareToView::createText("",Round($this -> value['profession']['stability'],2));
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['profession']['freedom'])) {
            	$raw_result = PrepareToView::createText("",Round($this -> value['profession']['freedom'],2));
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['profession']['production'])) {
            	$raw_result = PrepareToView::createText("",Round($this -> value['profession']['production'],2));
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['profession']['transport'])) {
            	$raw_result = PrepareToView::createText("",Round($this -> value['profession']['transport'],2));
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['profession']['safety'])) {
            	$raw_result = PrepareToView::createText("",Round($this -> value['profession']['safety'],2));
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['profession']['culture'])) {
            	$raw_result = PrepareToView::createText("",Round($this -> value['profession']['culture'],2));
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['profession']['influence'])) {
            	if ($this -> value['profession']['influence'] == 0) {
            		$raw_result = PrepareToView::createTextRed("",Round($this -> value['profession']['influence'],2)."%");
            	} else {
            		$raw_result = PrepareToView::createText("",Round($this -> value['profession']['influence'],2)."%");
            	}
            	array_push($result[$index], $raw_result);
        	} else {}
            unset($raw_result);

            return $result; 
		}

		public static function setSelectToTen($name, $current, $title) {
			$index = array();
			for ($i = 1; $i <= 10; $i++) { 
				array_push($index, $i);
				if ($name != "size" && $i == 5 && isset($_POST["frontier"])) {
					if ($current > 5)
						$current = 1;
					break;
				}
			}
			return PrepareToView::createSelect($name, $index, $current, $title);	
		}

		public static function setSelectArea($current, $title) {
			return PrepareToView::createNumber("area", $current, $title, 1, 150);	
		}

		public static function setSelectRoads($current, $title) {
			return PrepareToView::createNumber("roads", $current, $title, 0, 150);	
		}

		public static function setSelectBuilding($current, $title) {
			$count = 0;
			for ($i = 1; $i <= 6; $i++) { 
                if (isset($_POST["profession".$i]) && $_POST["profession".$i] != -1) {
                    $count++;
                }
            }
            if ($count == 0)
            	$current = 0;
            $index = array("0" => "");
            for ($i = 1; $i <= $count; $i++) { 
				array_push($index, $i);
			}
			return PrepareToView::createSelect("building", $index, $current, $title);
		}

		public static function setSelectCaravan($current, $title) {
			return PrepareToView::createNumber("caravan", $current, $title, 0, 150);	
		}

		public static function setSelectCorrection($name, $current, $title) {
			return PrepareToView::createNumber($name, $current, $title, -100, 200);	
		}

		public static function setAutoInfluence($size, $spec_profs) {
			$max_value = -50;
			$count_max = 0;
			$not_zero_count = 0;
			$result = array();

			foreach ($spec_profs as $key => $value) {
				if ($value > $max_value) {
					$max_value = $value;
					$count_max = 1;
				} else if ($value == $max_value) {
					$count_max++;
				}
				$result[$key]["influence"] = 0;
				$result[$key]["value"] = $value;
			}
			foreach ($result as $key => $value) {
				if ($value["value"] > 0 && $value["value"] < $max_value) {
					$not_zero_count++;
				}
			}

			if ($max_value <= 0 || $not_zero_count == 0) {
				foreach ($result as $key => $value) {
					if ($value["value"] == $max_value) {
						$result[$key]["influence"] = Round(100 / $count_max,2);
					}
				}
			} else {
				$coef = Dictionary::getSizeCoef($size);
				$influence = 50 * 100 / $coef / $max_value;
				if ($influence > 100) {
					$influence = 100;
				}
				foreach ($result as $key => $value) {
					if ($value["value"] == $max_value) {
						$result[$key]["influence"] = Round($influence / $count_max,2);
					} else if ($not_zero_count > 0 && $value["value"] > 0) {
						$result[$key]["influence"] = Round((100 - $influence) / $not_zero_count,2);
					}
				}
			}
			return $result;
		}

		public static function setSelectPlaces() {
			$place_list = PlaceModel::dbSelectAllNames();
			return PrepareToView::createSelect("place", $place_list, 0, "Выберите город");
		}

		public static function setMastersByPlaceId($id) {
			$mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT id, profession, building FROM masters WHERE city_id=".$id);
            $building_count = 0;
            $i = 0;
            $council = array();
            while ($row = $result->fetch_assoc()) {
                $council[$i]["master"] = $row["id"];
                $council[$i]["profession"] = $row["profession"];
                if ($row["building"]) {
                	$building_count++;
                }
                $i++;
            }
            $council["building"] = $building_count;
            return $council;
		}

		function __destruct() {}
	}
?>