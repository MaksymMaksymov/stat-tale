<?php
    include_once("GameModel.php");

	class PlaceModel extends GameModel   
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
            $result = $mysqli->query("SELECT id FROM places");
            
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
            $result = $mysqli->query("SELECT * FROM places WHERE id=".$this -> value["id"]);
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
            $db_names .= ",frontier";
            $db_values .= (isset($arrayData['frontier']) && $arrayData['frontier']) ? ",true" : ",false";
            $db_names .= (!isset($arrayData['specialization'])) ? "" : ",specialization";
            $db_values .= (!isset($arrayData['specialization'])) ? "" : ",".$arrayData['specialization'];
            if (isset($arrayData['demographics'])) {
	            $get_race = $this -> getDemographic($arrayData['demographics']);
	            $db_names .= ",demographics,demographics_title";
	            $db_values .= ",N'".$get_race["race"]."',N'".Round($get_race["percent"]*100,1)."%'";
        	}

            $get_arr_attributes = $this -> getAttributesList($arrayData['attributes']['attributes']); 
            $get_arr_effects = $this -> getEffectsList($arrayData['attributes']['effects'], $this -> getPersonsNameList($arrayData['persons']));
            if (isset($get_arr_attributes[11]) && isset($get_arr_effects[11])) {
	            $db_names .= ",stability,stability_title";
	            $db_values .= ",".Round($get_arr_attributes[11]*100,2);
	            $law = ($get_arr_effects[11]['law'] != 0) ? $get_arr_effects[11]['law']."% записи ": "";
	            $ang = ($get_arr_effects[11]['angel'] != 0) ? $get_arr_effects[11]['angel']."% Хранители ": "";
	            $job = ($get_arr_effects[11]['job'] != 0) ? $get_arr_effects[11]['job']."% проекты ": "";
	            $pers = $get_arr_effects[11]['person']."% Мастера ";
	            $oth = ($get_arr_effects[11]['other'] != 0) ? $get_arr_effects[11]['other']."% остальное": "";
	            $db_values .= ",N'".$law.$ang.$job.$pers.$oth."'";
			}
			if (isset($get_arr_attributes[9]) && isset($get_arr_effects[9])) {
	            $db_names .= ",freedom,freedom_title";
	            $db_values .= ",".Round($get_arr_attributes[9]*100,2);
	            $sta = ($get_arr_effects[9]['stability'] != 0) ? $get_arr_effects[9]['stability']."% стабильность ": "";
	            $job = ($get_arr_effects[9]['job'] != 0) ? $get_arr_effects[9]['job']."% проекты ": "";
	            $pers = $get_arr_effects[9]['person']."% Мастера ";
	            $oth = ($get_arr_effects[9]['other'] != 0) ? $get_arr_effects[9]['other']."% остальное": "";
	            $db_values .= ",N'".$sta.$job.$pers.$oth."'";
	        }
	        if (isset($get_arr_attributes[4]) && isset($get_arr_effects[4])) {
	        	$db_names .= ",production,production_title";
	            $db_values .= ",".Round($get_arr_attributes[4],0);
	            $sta = ($get_arr_effects[4]['stability'] != 0) ? $get_arr_effects[4]['stability']." стабильность ": "";
	            $job = ($get_arr_effects[4]['job'] != 0) ? $get_arr_effects[4]['job']." проекты ": "";
	            $pers = $get_arr_effects[4]['person']." Мастера ";
	            $oth = ($get_arr_effects[4]['other'] != 0) ? $get_arr_effects[4]['other']." остальное": "";
	            $db_values .= ",N'".$sta.$job.$pers.$oth."'";	        
	        }
	        if (isset($get_arr_attributes[8]) && isset($get_arr_effects[8])) {
	        	$db_names .= ",transport,transport_title";
	            $db_values .= ",".Round($get_arr_attributes[8]*100,2);
	            $sta = ($get_arr_effects[8]['stability'] != 0) ? $get_arr_effects[8]['stability']."% стабильность ": "";
	            $job = ($get_arr_effects[8]['job'] != 0) ? $get_arr_effects[8]['job']."% проекты ": "";
	            $pers = $get_arr_effects[8]['person']."% Мастера ";
	            $oth = ($get_arr_effects[8]['other'] != 0) ? $get_arr_effects[8]['other']."% остальное": "";
	            $db_values .= ",N'".$sta.$job.$pers.$oth."'";
	        }
	        if (isset($get_arr_attributes[7]) && isset($get_arr_effects[7])) {
	        	$db_names .= ",safety,safety_title";
	            $db_values .= ",".Round($get_arr_attributes[7]*100,2);
	            $sta = ($get_arr_effects[7]['stability'] != 0) ? $get_arr_effects[7]['stability']."% стабильность ": "";
	            $job = ($get_arr_effects[7]['job'] != 0) ? $get_arr_effects[7]['job']."% проекты ": "";
	            $pers = $get_arr_effects[7]['person']."% Мастера ";
	            $oth = ($get_arr_effects[7]['other'] != 0) ? $get_arr_effects[7]['other']."% остальное": "";
	            $db_values .= ",N'".$sta.$job.$pers.$oth."'";
	        }
	        if (isset($get_arr_attributes[7]) && isset($get_arr_attributes[8])) {
	        	$db_names .= ",time";
                $time = (160 / $get_arr_attributes[7]-150) / ($get_arr_attributes[8]*0.1);
                $time = Round($time,0);
                $db_values .= ",".$time;
            }
            if (isset($get_arr_attributes[33]) && isset($get_arr_effects[33])) {
            	$db_names .= ",culture,culture_title";
	            $db_values .= ",".Round($get_arr_attributes[33]*100,2);
	            $sta = ($get_arr_effects[33]['stability'] != 0) ? $get_arr_effects[33]['stability']."% стабильность ": "";
	            $job = ($get_arr_effects[33]['job'] != 0) ? $get_arr_effects[33]['job']."% проекты ": "";
	            $pers = $get_arr_effects[33]['person']."% Мастера ";
	            $oth = ($get_arr_effects[33]['other'] != 0) ? $get_arr_effects[33]['other']."% остальное": "";
	            $db_values .= ",N'".$sta.$job.$pers.$oth."'";
	        }
	        $db_names .= (isset($get_arr_attributes[34])) ? ",area" : "";
	        $db_values .= (isset($get_arr_attributes[34])) ? ",".$get_arr_attributes[34] : "";
            $db_names .= (isset($get_arr_attributes[0])) ? ",size" : "";
            $db_values .= (isset($get_arr_attributes[0])) ? ",".$get_arr_attributes[0] : "";
            $db_names .= (isset($get_arr_attributes[22])) ? ",economy" : "";
            $db_values .= (isset($get_arr_attributes[22])) ? ",".$get_arr_attributes[22] : "";

            if (isset($arrayData['persons'])) {
            	$db_names .= ",persons_count,persons";
            	$db_values .= ",".count($arrayData['persons']).",N'".str_replace("'","\'",$this -> getPersonsList($arrayData['persons']))."'";
            }
            if (isset($arrayData['bills'])) {
            	$db_names .= ",bills_count,bills";
            	$db_values .= ",".count($arrayData['bills']).",N'".str_replace("'","\'",$this -> getBillsList($arrayData['bills']))."'";
            }

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
            $query = $mysqli->query("INSERT INTO places(".$db_names.") VALUES (".$db_values.")");
            
            if ($query)
                RegisterUpdates::dbUpdateValues("places");

            return $query;
        }

        public function dbUpdate($arrayData) {
            if ($arrayData == null || !isset($this -> value['id'])) return false;

            $db_values = "id=".$this -> value['id'];
            $db_values .= (!isset($arrayData['name'])) ? "" : ",name=N'".str_replace("'","\'",$arrayData['name'])."'";
            $db_values .= (isset($arrayData['frontier']) && $arrayData['frontier']) ? ",frontier=true" : ",frontier=false";
            $db_values .= (!isset($arrayData['specialization'])) ? "" : ",specialization=".$arrayData['specialization'];
            if (isset($arrayData['demographics'])) {
	            $get_race = $this -> getDemographic($arrayData['demographics']);
	            $db_values .= ",demographics=N'".$get_race["race"]."',demographics_title=N'".Round($get_race["percent"]*100,1)."%'";
        	}

            $get_arr_attributes = $this -> getAttributesList($arrayData['attributes']['attributes']); 
            $get_arr_effects = $this -> getEffectsList($arrayData['attributes']['effects'], $this -> getPersonsNameList($arrayData['persons']));
            if (isset($get_arr_attributes[11]) && isset($get_arr_effects[11])) {
	            $db_values .= ",stability=".Round($get_arr_attributes[11]*100,2);
	            $law = ($get_arr_effects[11]['law'] != 0) ? $get_arr_effects[11]['law']."% записи ": "";
	            $ang = ($get_arr_effects[11]['angel'] != 0) ? $get_arr_effects[11]['angel']."% Хранители ": "";
	            $job = ($get_arr_effects[11]['job'] != 0) ? $get_arr_effects[11]['job']."% проекты ": "";
	            $pers = $get_arr_effects[11]['person']."% Мастера ";
	            $oth = ($get_arr_effects[11]['other'] != 0) ? $get_arr_effects[11]['other']."% остальное": "";
	            $db_values .= ",stability_title=N'".$law.$ang.$job.$pers.$oth."'";
			}
			if (isset($get_arr_attributes[9]) && isset($get_arr_effects[9])) {
	            $db_values .= ",freedom=".Round($get_arr_attributes[9]*100,2);
	            $sta = ($get_arr_effects[9]['stability'] != 0) ? $get_arr_effects[9]['stability']."% стабильность ": "";
	            $job = ($get_arr_effects[9]['job'] != 0) ? $get_arr_effects[9]['job']."% проекты ": "";
	            $pers = $get_arr_effects[9]['person']."% Мастера ";
	            $oth = ($get_arr_effects[9]['other'] != 0) ? $get_arr_effects[9]['other']."% остальное": "";
	            $db_values .= ",freedom_title=N'".$sta.$job.$pers.$oth."'";
	        }
	        if (isset($get_arr_attributes[4]) && isset($get_arr_effects[4])) {
	            $db_values .= ",production=".Round($get_arr_attributes[4],0);
	            $sta = ($get_arr_effects[4]['stability'] != 0) ? $get_arr_effects[4]['stability']." стабильность ": "";
	            $job = ($get_arr_effects[4]['job'] != 0) ? $get_arr_effects[4]['job']." проекты ": "";
	            $pers = $get_arr_effects[4]['person']." Мастера ";
	            $oth = ($get_arr_effects[4]['other'] != 0) ? $get_arr_effects[4]['other']." остальное": "";
	            $db_values .= ",production_title=N'".$sta.$job.$pers.$oth."'";	        
	        }
	        if (isset($get_arr_attributes[8]) && isset($get_arr_effects[8])) {
	            $db_values .= ",transport=".Round($get_arr_attributes[8]*100,2);
	            $sta = ($get_arr_effects[8]['stability'] != 0) ? $get_arr_effects[8]['stability']."% стабильность ": "";
	            $job = ($get_arr_effects[8]['job'] != 0) ? $get_arr_effects[8]['job']."% проекты ": "";
	            $pers = $get_arr_effects[8]['person']."% Мастера ";
	            $oth = ($get_arr_effects[8]['other'] != 0) ? $get_arr_effects[8]['other']."% остальное": "";
	            $db_values .= ",transport_title=N'".$sta.$job.$pers.$oth."'";
	        }
	        if (isset($get_arr_attributes[7]) && isset($get_arr_effects[7])) {
	            $db_values .= ",safety=".Round($get_arr_attributes[7]*100,2);
	            $sta = ($get_arr_effects[7]['stability'] != 0) ? $get_arr_effects[7]['stability']."% стабильность ": "";
	            $job = ($get_arr_effects[7]['job'] != 0) ? $get_arr_effects[7]['job']."% проекты ": "";
	            $pers = $get_arr_effects[7]['person']."% Мастера ";
	            $oth = ($get_arr_effects[7]['other'] != 0) ? $get_arr_effects[7]['other']."% остальное": "";
	            $db_values .= ",safety_title=N'".$sta.$job.$pers.$oth."'";
	        }
	        if (isset($get_arr_attributes[7]) && isset($get_arr_attributes[8])) {
                $time = (160 / $get_arr_attributes[7]-150) / ($get_arr_attributes[8]*0.1);
                $time = Round($time,0);
                $db_values .= ",time=".$time;
            }
            if (isset($get_arr_attributes[33]) && isset($get_arr_effects[33])) {
	            $db_values .= ",culture=".Round($get_arr_attributes[33]*100,2);
	            $sta = ($get_arr_effects[33]['stability'] != 0) ? $get_arr_effects[33]['stability']."% стабильность ": "";
	            $job = ($get_arr_effects[33]['job'] != 0) ? $get_arr_effects[33]['job']."% проекты ": "";
	            $pers = $get_arr_effects[33]['person']."% Мастера ";
	            $oth = ($get_arr_effects[33]['other'] != 0) ? $get_arr_effects[33]['other']."% остальное": "";
	            $db_values .= ",culture_title=N'".$sta.$job.$pers.$oth."'";
	        }
	        $db_values .= (isset($get_arr_attributes[34])) ? ",area=".$get_arr_attributes[34] : "";
            $db_values .= (isset($get_arr_attributes[0])) ? ",size=".$get_arr_attributes[0] : "";
            $db_values .= (isset($get_arr_attributes[22])) ? ",economy=".$get_arr_attributes[22] : "";

            if (isset($arrayData['persons'])) {
            	$db_values .= ",persons_count=".count($arrayData['persons']).",persons=N'".str_replace("'","\'",$this -> getPersonsList($arrayData['persons']))."'";
            }
            if (isset($arrayData['bills'])) {
            	$db_values .= ",bills_count=".count($arrayData['bills']).",bills=N'".str_replace("'","\'",$this -> getBillsList($arrayData['bills']))."'";
            }

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
            $query = $mysqli->query("UPDATE places SET ".$db_values." WHERE id=".$this -> value["id"]);
            
            if ($query)
                RegisterUpdates::dbUpdateValues("places");

            return $query;
        } 

        public function getValues() {
            $result = array();
            $row_result = "";
            $index = 0;

            $result[$index] = array();
            if (isset($this -> value['frontier'])) { 
            	$raw_result = ($this -> value['frontier']) ? PrepareToView::createImg("frontier.png","Фронтир") : PrepareToView::createImg("not_frontier.png","Ядро");
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['specialization'])) ? Dictionary::getSpecialization($this -> value['specialization']) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['id']) && isset($this -> value['name'])) {
                $raw_result = PrepareToView::createUrl("http://the-tale.org/game/places/".$this -> value['id'],$this -> value['name']);
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['demographics']) && isset($this -> value['demographics_title'])) {
            	$raw_result = PrepareToView::createText($this -> value['demographics_title'],$this -> value['demographics']);
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['persons'])) {
                $raw_result = PrepareToView::createSpoiler("Совет (".$this -> value['persons_count'].")",$this -> value['persons']);
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['stability']) && isset($this -> value['stability_title'])) {
            	$raw_result = PrepareToView::createText($this -> value['stability_title'],$this -> value['stability']);
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['freedom']) && isset($this -> value['freedom_title'])) {
            	$raw_result = PrepareToView::createText($this -> value['freedom_title'],$this -> value['freedom']);
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['production']) && isset($this -> value['production_title'])) {
            	$raw_result = PrepareToView::createText($this -> value['production_title'],$this -> value['production']);
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['transport']) && isset($this -> value['transport_title'])) {
            	$raw_result = PrepareToView::createText($this -> value['transport_title'],$this -> value['transport']);
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['safety']) && isset($this -> value['safety_title'])) {
            	$raw_result = PrepareToView::createText($this -> value['safety_title'],$this -> value['safety']);
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['time'])) {
                $time = $this -> value['time'];
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
                $raw_result = PrepareToView::createText($d.$h.$m.$s,$this -> value['time']);
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['culture']) && isset($this -> value['culture_title'])) {
            	$raw_result = PrepareToView::createText($this -> value['culture_title'],$this -> value['culture']);
        	} else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['area'])) ? $this -> value['area'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['size'])) ? $this -> value['size']: "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['economy'])) ? $this -> value['economy'] : "";
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
            if (isset($this -> value['bills']) && $this -> value['bills_count'] > 0) {
            $raw_result = PrepareToView::createSpoiler("Записи (".$this -> value['bills_count'].")",$this -> value['bills']);
            } else
                $raw_result = "";
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
	                	$arr_eff[$value['attribute']]['stability'] += Round($value['value'],0);
	                else if ($this -> findJob($value['name']))
	                	$arr_eff[$value['attribute']]['job'] += Round($value['value'],0);
	                else if ($this -> findMaster($array_of_persons, $value['name']))
	                	$arr_eff[$value['attribute']]['person'] += Round($value['value'],0);
	                else
	                	$arr_eff[$value['attribute']]['other'] += Round($value['value'],0);
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
            if (isset($this -> value['specialization'])) {
                return Dictionary::getSpecialization($this -> value['specialization']);
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
                return $this -> value['demographics'];
            }
            return 0;
        }

        public function getStability() {
            if (isset($this -> value['stability'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = $this -> value['stability'];
                return $result;
            }
            return 0;
        }

        public function getFreedom() {
            if (isset($this -> value['freedom'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = $this -> value['freedom'];
                return $result;
            }
            return 0;
        }

        public function getProduction() {
            if (isset($this -> value['production'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = $this -> value['production'];
                return $result;
            }
            return 0;
        }

        public function getTransport() {
            if (isset($this -> value['transport'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = $this -> value['transport'];
                return $result;
            }
            return 0;
        }

        public function getSafety() {
            if (isset($this -> value['safety'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = $this -> value['safety'];
                return $result;
            }
            return 0;
        }

        public function getTime() {
            if (isset($this -> value['time'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = -($this -> value['time']);
                return $result;
            }
            return 0;
        }

        public function getCulture() {
            if (isset($this -> value['culture'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = $this -> value['culture'];;
                return $result;
            }
            return 0;
        }

        public function getArea() {
            if (isset($this -> value['area'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = $this -> value['area'];
                return $result;
            }
            return 0;
        }

        public function getSize() {
            if (isset($this -> value['size'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = $this -> value['size'];
                return $result;
            }
            return 0;
        }

        public function getEconomy() {
            if (isset($this -> value['economy'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['attr'] = $this -> value['economy'];
                return $result;
            }
            return 0;
        }

        public function getPower() {
            if (isset($this -> value['politic_power'])) {
            	$result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['power'] = $this -> value['politic_power'];
                return $result;
            }
            return 0;
        }

        public function getPowerIn() {
            if (isset($this -> value['power_inner'])) {
            	$result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['power'] = $this -> value['power_inner'];
                return $result;
            }
            return 0;
        }

        public function getPowerOut() {
            if (isset($this -> value['power_outer'])) {
                $result['frontier'] = (isset($this -> value['frontier']) && (!$this -> value['frontier'])) ? 1 : 0;
                $result['power'] = $this -> value['power_outer'];
                return $result;
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

        public static function getNameById($id) {
        	$mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT name FROM places WHERE id=".$id);
            $row = $result->fetch_assoc();
            if (isset($row)) {
                return $row['name'];
            } else
                return false;
        }

		function __destruct() {}           
    }
?>