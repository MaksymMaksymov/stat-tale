<?php
    include_once("GameModel.php");
	include_once("PlacesModel.php");

	class PlacesAddModel extends GameModel   
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
            $result = array();
            $row_result = "";
            $index = 0;

            $result[$index] = array();
            if (isset($this -> value['persons'])) {
                $raw_result['type'] = "img";
                $raw_result['src'] = ($this -> value['frontier']) ? "frontier.png" : "not_frontier.png";
                $raw_result['alt'] = ($this -> value['frontier']) ? "Фронтир" : "Ядро";
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['city']['specialization'])) ? Dictionary::getSpecialization($this -> value['city']['specialization']) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['id']) && isset($this -> value['name'])) {
                $raw_result['type'] = "url";
                $raw_result['href'] = "http://the-tale.org/game/places/".$this -> value['id'];
                $raw_result['value'] = $this -> value['name'];
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['demographics'])) {
                $get_race = $this -> getDemographic($this -> value['demographics']);
                $raw_result['type'] = "text";
                $raw_result['value'] = $get_race["race"];
                $raw_result['title'] = Round($get_race["percent"]*100,1)."%";
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['demographics']) && isset($this -> value['persons'])) {
                $raw_result['type'] = "spoiler";
                $raw_result['name'] = "Совет (".count($this -> value['persons']).")";
                $raw_result['value'] = $this -> getPersonsList($this -> value['persons']);
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $get_arr_attributes = $this -> getAttributesList($this -> value['attributes']['attributes']);
            $get_arr_effects = $this -> getEffectsList($this -> value['attributes']['effects'], $this -> getPersonsNameList($this -> value['persons']));

            $index++;
            $result[$index] = array();
            if (isset($get_arr_attributes[11])) { 
	            if (isset($get_arr_effects[11])) {
	                $raw_result['type'] = "text";
	                $raw_result['value'] = Round($get_arr_attributes[11]*100,2)."%";
	                $law = ($get_arr_effects[11]['law'] != 0) ? $get_arr_effects[11]['law']."% записи ": "";
	                $ang = ($get_arr_effects[11]['angel'] != 0) ? $get_arr_effects[11]['angel']."% Хранители ": "";
	                $job = ($get_arr_effects[11]['job'] != 0) ? $get_arr_effects[11]['job']."% проекты ": "";
	                $pers = $get_arr_effects[11]['person']."% Мастера ";
	                $oth = ($get_arr_effects[11]['other'] != 0) ? $get_arr_effects[11]['other']."% остальное": "";
	                $raw_result['title'] = $law.$ang.$job.$pers.$oth;
	            } else 
	            	$raw_result = Round($get_arr_attributes[11]*100,2)."%";
			} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($get_arr_attributes[9])) { 
	            if (isset($get_arr_effects[9])) {
	                $raw_result['type'] = "text";
	                $raw_result['value'] = Round($get_arr_attributes[9]*100,2)."%";
	                $sta = ($get_arr_effects[9]['stability'] != 0) ? $get_arr_effects[9]['stability']."% стабильность ": "";
	                $job = ($get_arr_effects[9]['job'] != 0) ? $get_arr_effects[9]['job']."% проекты ": "";
	                $pers = $get_arr_effects[9]['person']."% Мастера ";
	                $oth = ($get_arr_effects[9]['other'] != 0) ? $get_arr_effects[9]['other']."% остальное": "";
	                $raw_result['title'] = $sta.$job.$pers.$oth;
	            } else 
	            	$raw_result = Round($get_arr_attributes[9]*100,2)."%";
			} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($get_arr_attributes[4])) { 
	            if (isset($get_arr_effects[4])) {
	                $raw_result['type'] = "text";
	                $raw_result['value'] = Round($get_arr_attributes[4],2);
	                $sta = ($get_arr_effects[4]['stability'] != 0) ? $get_arr_effects[4]['stability']." стабильность ": "";
	                $job = ($get_arr_effects[4]['job'] != 0) ? $get_arr_effects[4]['job']." проекты ": "";
	                $pers = $get_arr_effects[4]['person']." Мастера ";
	                $oth = ($get_arr_effects[4]['other'] != 0) ? $get_arr_effects[4]['other']." остальное": "";
	                $raw_result['title'] = $sta.$job.$pers.$oth;
	            } else 
	            	$raw_result = Round($get_arr_attributes[4],2);
			} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($get_arr_attributes[8])) { 
	            if (isset($get_arr_effects[8])) {
	                $raw_result['type'] = "text";
	                $raw_result['value'] = Round($get_arr_attributes[8]*100,2)."%";
	                $sta = ($get_arr_effects[8]['stability'] != 0) ? $get_arr_effects[8]['stability']."% стабильность ": "";
	                $job = ($get_arr_effects[8]['job'] != 0) ? $get_arr_effects[8]['job']."% проекты ": "";
	                $pers = $get_arr_effects[8]['person']."% Мастера ";
	                $oth = ($get_arr_effects[8]['other'] != 0) ? $get_arr_effects[8]['other']."% остальное": "";
	                $raw_result['title'] = $sta.$job.$pers.$oth;
	            } else 
	            	$raw_result = Round($get_arr_attributes[8]*100,2)."%";
			} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($get_arr_attributes[7])) { 
	            if (isset($get_arr_effects[7])) {
	                $raw_result['type'] = "text";
	                $raw_result['value'] = Round($get_arr_attributes[7]*100,2)."%";
	                $sta = ($get_arr_effects[7]['stability'] != 0) ? $get_arr_effects[7]['stability']."% стабильность ": "";
	                $job = ($get_arr_effects[7]['job'] != 0) ? $get_arr_effects[7]['job']."% проекты ": "";
	                $pers = $get_arr_effects[7]['person']."% Мастера ";
	                $oth = ($get_arr_effects[7]['other'] != 0) ? $get_arr_effects[7]['other']."% остальное": "";
	                $raw_result['title'] = $sta.$job.$pers.$oth;
	            } else 
	            	$raw_result = Round($get_arr_attributes[7]*100,2)."%";
			} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($get_arr_attributes[7]) && isset($get_arr_attributes[8])) {
                $time = (160 / $get_arr_attributes[7]-150) / ($get_arr_attributes[8]*0.1);
                $time = Round($time,0);
                $raw_result['type'] = "text";
                $raw_result['value'] = $time;
                $secs = $time % 60;
                $time -= $secs;
                $time /= 60;
                $mins = $time % 60;
                $time -= $mins;
                $time /= 60;
                $hs = $time % 24;
                $time -= $hs;
                $time /= 24;
                $d = ($time > 0) ? $time."d " : "";
                $h = ($hs > 0) ? $hs."h " : "";
                $m = ($mins > 0) ? $mins."m " : "";
                $s = ($secs > 0) ? $secs."s " : "";
                $raw_result['title'] = $d.$h.$m.$s;
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($get_arr_attributes[33])) { 
	            if (isset($get_arr_effects[33])) {
	                $raw_result['type'] = "text";
	                $raw_result['value'] = Round($get_arr_attributes[33]*100,2)."%";
	                $sta = ($get_arr_effects[33]['stability'] != 0) ? $get_arr_effects[33]['stability']."% стабильность ": "";
	                $job = ($get_arr_effects[33]['job'] != 0) ? $get_arr_effects[33]['job']."% проекты ": "";
	                $pers = $get_arr_effects[33]['person']."% Мастера ";
	                $oth = ($get_arr_effects[33]['other'] != 0) ? $get_arr_effects[33]['other']."% остальное": "";
	                $raw_result['title'] = $sta.$job.$pers.$oth;
	            } else 
	            	$raw_result = Round($get_arr_attributes[33]*100,2)."%";
			} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($get_arr_attributes[34])) ? $get_arr_attributes[34] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($get_arr_attributes[0])) ? $get_arr_attributes[0] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($get_arr_attributes[22])) ? $get_arr_attributes[22] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['politic_power']['power']['fraction'])) ? Round($this -> value['politic_power']['power']['fraction']*100,2)."%" : "";
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
            if (isset($this -> value['bills']) && count($this -> value['bills']) > 0) {
                $raw_result['type'] = "spoiler";
                $raw_result['name'] = "Записи (".count($this -> value['bills']).")";
                $raw_result['value'] = $this -> getBillsList($this -> value['bills']);
            } else
                $raw_result = "";
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
        
        public function setCity($city) {
            $this -> value['city'] = $city;
        }

        private function getPersonsNameList($arr_of_persons) {
            $arr_name = array();
            foreach ($arr_of_persons as $key => $value) {
            	array_push($arr_name, $value['name']);                  
            }
            return $arr_name;
        }

        private function getPersonsList($arr_of_persons) {
            $result = "<ul>";
            foreach ($arr_of_persons as $key => $value) {
               $prof = Dictionary::getProfession($value['type']);        
               $result .= "<li>".$prof." <b>".$value['name']."</b></li>";                       
            }
            $result .= "</ul>";
            return $result;
        }
               
        private function getAttributesList($array_of_attributes) {
            $arr_attr = array();                            
            foreach ($array_of_attributes as $key => $value) {
                $arr_attr[$value['id']] = $value['value'];
            }
            return $arr_attr;
        }

        private function getEffectsList($array_of_effects, $array_of_persons) {
            $arr_eff = array(4 => array("stability" => 0, "job" => 0, "person" => 0, "other" => 0),
        					 7 => array("stability" => 0, "job" => 0, "person" => 0, "other" => 0),
        					 8 => array("stability" => 0, "job" => 0, "person" => 0, "other" => 0),
        					 9 => array("stability" => 0, "job" => 0, "person" => 0, "other" => 0),
        					 11 => array("angel" => 0, "law" => 0, "job" => 0, "person" => 0, "other" => 0),
        					 33 => array("stability" => 0, "job" => 0, "person" => 0, "other" => 0));                            
            foreach ($array_of_effects as $key => $value) {
            	if ($value['attribute'] == 11) {
            		if ($this -> findLaw($value['name']))
	                	$arr_eff[$value['attribute']]['law'] += Round($value['value']*100,2);
	                else if ($this -> findAngel($value['name']))
	                	$arr_eff[$value['attribute']]['angel'] += Round($value['value']*100,2);
	                else if ($this -> findJob($value['name']))
	                	$arr_eff[$value['attribute']]['job'] += Round($value['value']*100,2);
	                else if ($this -> findMaster($array_of_persons, $value['name']))
	                	$arr_eff[$value['attribute']]['person'] += Round($value['value']*100,2);
	                else
	                	$arr_eff[$value['attribute']]['other'] += Round($value['value']*100,2);
            	} else if ($value['attribute'] == 4) {
            		if ($this -> findStability($value['name']))
	                	$arr_eff[$value['attribute']]['stability'] += Round($value['value'],2);
	                else if ($this -> findJob($value['name']))
	                	$arr_eff[$value['attribute']]['job'] += Round($value['value'],2);
	                else if ($this -> findMaster($array_of_persons, $value['name']))
	                	$arr_eff[$value['attribute']]['person'] += Round($value['value'],2);
	                else
	                	$arr_eff[$value['attribute']]['other'] += Round($value['value'],2);
            	} else if ($value['attribute'] == 7 || $value['attribute'] == 8 || $value['attribute'] == 9 || $value['attribute'] == 33) {
	            	if ($this -> findStability($value['name']))
	                	$arr_eff[$value['attribute']]['stability'] += Round($value['value']*100,2);
	                else if ($this -> findJob($value['name']))
	                	$arr_eff[$value['attribute']]['job'] += Round($value['value']*100,2);
	                else if ($this -> findMaster($array_of_persons, $value['name']))
	                	$arr_eff[$value['attribute']]['person'] += Round($value['value']*100,2);
	                else
	                	$arr_eff[$value['attribute']]['other'] += Round($value['value']*100,2);
	            }
            }
            return $arr_eff;
        }

        private function findMaster($array_of_persons, $person) {
        	foreach ($array_of_persons as $key => $value) {
                if ($value == $person)
                	return true;
            }
            return false;
        }

        private function findJob($job) {
        	$result = strpos($job, "Проект");
        	return ($result === false) ? false : true;
        }

        private function findAngel($angel) {
        	$result = strpos($angel, "Хранитель");
        	return ($result === false) ? false : true;
        }

        private function findLaw($law) {
        	$result = strpos($law, "запись");
        	return ($result === false) ? false : true;
        }

        private function findStability($stability) {
        	$result = strpos($stability, "стабильность");
        	return ($result === false) ? false : true;
        }

        private function getBillsList($arr_of_bills) {
            $result = "<ul>";
            foreach ($arr_of_bills as $key => $value) {
                if (isset($value['properties'])) {
                    foreach ($value['properties'] as $kp => $vp) {
                        $result .= "<li>".$vp."</li>"; 
                    }
                }                      
            }
            $result .= "</ul>";
            return $result;
        }

        private function getDemographic($arr_of_races) {
            $result['race'] = "";
            $result['percent'] = 0;
            foreach ($arr_of_races as $key => $value) {
                if (isset($value['percents']) && isset($value['race']) ) {
                    if ($value['percents'] > $result['percent']) {
                        $result['percent'] = $value['percents'];
                        $result['race'] = Dictionary::getRace(-1,  $value['race']);
                    }
                }                      
            }
            return $result;
        }

        public function getSpecialization() {
            if (isset($this -> value['city']['specialization'])) {
                return Dictionary::getSpecialization($this -> value['city']['specialization']);
            }
            return 0;
        }

        public function getCity() {
            if (isset($this -> value['name'])) {
                return $this -> value['name'];
            }
            return 0;
        }

        public function getRace() {
            if (isset($this -> value['demographics'])) {
                $get_race = $this -> getDemographic($this -> value['demographics']);
                $result['race'] = $get_race["race"];
                return $result;
            }
            return 0;
        }

        public function getStability() {
            $get_arr_attributes = $this -> getAttributesList($this -> value['attributes']['attributes']);
            if (isset($get_arr_attributes[11])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = Round($get_arr_attributes[11]*100,2);
                return $result;
            }
            return 0;
        }

        public function getFreedom() {
            $get_arr_attributes = $this -> getAttributesList($this -> value['attributes']['attributes']);
            if (isset($get_arr_attributes[9])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = Round($get_arr_attributes[9]*100,2);
                return $result;
            }
            return 0;
        }

        public function getProduction() {
            $get_arr_attributes = $this -> getAttributesList($this -> value['attributes']['attributes']);
            if (isset($get_arr_attributes[4])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = Round($get_arr_attributes[4]*100,0);
                return $result;
            }
            return 0;
        }

        public function getTransport() {
            $get_arr_attributes = $this -> getAttributesList($this -> value['attributes']['attributes']);
            if (isset($get_arr_attributes[8])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = Round($get_arr_attributes[8]*100,2);
                return $result;
            }
            return 0;
        }

        public function getSafety() {
            $get_arr_attributes = $this -> getAttributesList($this -> value['attributes']['attributes']);
            if (isset($get_arr_attributes[7])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = Round($get_arr_attributes[7]*100,2);
                return $result;
            }
            return 0;
        }

        public function getTime() {
            $get_arr_attributes = $this -> getAttributesList($this -> value['attributes']['attributes']);
            if (isset($get_arr_attributes[7]) && isset($get_arr_attributes[8])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $time = (160 / $get_arr_attributes[7]-150) / ($get_arr_attributes[8]*0.1);
                $result['attr'] =  Round($time,0);
                return $result;
            }
            return 0;
        }

        public function getCulture() {
            $get_arr_attributes = $this -> getAttributesList($this -> value['attributes']['attributes']);
            if (isset($get_arr_attributes[33])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = Round($get_arr_attributes[33]*100,2);
                return $result;
            }
            return 0;
        }

        public function getArea() {
            $get_arr_attributes = $this -> getAttributesList($this -> value['attributes']['attributes']);
            if (isset($get_arr_attributes[34])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] =  $get_arr_attributes[34];
                return $result;
            }
            return 0;
        }

        public function getSize() {
            $get_arr_attributes = $this -> getAttributesList($this -> value['attributes']['attributes']);
            if (isset($get_arr_attributes[0])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] =  $get_arr_attributes[0];
                return $result;
            }
            return 0;
        }

        public function getEconomy() {
            $get_arr_attributes = $this -> getAttributesList($this -> value['attributes']['attributes']);
            if (isset($get_arr_attributes[22])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] =  $get_arr_attributes[22];
                return $result;
            }
            return 0;
        }

        public function getPower() {
            if (isset($this -> value['politic_power']['power']['fraction'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['power'] =  Round($this -> value['politic_power']['power']['fraction']*100,1);
                return $result;
            }
            return 0;
        }

        public function getPowerIn() {
            if (isset($this -> value['politic_power']['power']['inner'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['power'] =  Round($this -> value['politic_power']['power']['inner']['value'],1);
                return $result;
            }
            return 0;
        }

        public function getPowerOut() {
            if (isset($this -> value['politic_power']['power']['outer'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['power'] =  Round($this -> value['politic_power']['power']['outer']['value'],1);
                return $result;
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