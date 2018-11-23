<?php
    include_once("GameModel.php");
    include_once("PlaceModel.php");

	class HeroModel extends GameModel   
    {
    	private $value;

		function __construct() {}   
		
		public function setValue($id) {
            $this -> value["id"] = $id;
            return $this -> dbSelectById(); 	
		}

        public static function dbSelectAll()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT id FROM heroes WHERE level > 1 AND can_affect_game = 1");
            
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
            $this -> value['id'] = $arr_result['account']['id'];

            if ($this -> dbSelectById()) {
                return $this -> dbUpdate($arr_result);
            } else {
                if ($this -> dbInsert($arr_result))
                    return $this -> dbUpdate($arr_result);
                else
                    return false;
            }
        }

        public function dbSelectById()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT * FROM heroes WHERE id=".$this -> value["id"]);
            $row = $result->fetch_assoc();
            if (isset($row)) {
                $this -> value = $row;
                return true;
            } else
                return false;
        }
        
        public function dbInsert($arrayData) {
            if ($arrayData == null || !isset($arrayData['account']['id'])) return false;

            $db_names = "id";
            $db_values = $arrayData['account']['id'];
            $db_names .= (!isset($arrayData['account']['hero']['base']['name'])) ? "" : ",name";
            $db_values .= (!isset($arrayData['account']['hero']['base']['name'])) ? "" : ",N'".str_replace("'","\'",$arrayData['account']['hero']['base']['name'])."'";
            $db_names .= (!isset($arrayData['angel']['name'])) ? "" : ",angel_name";
            $db_values .= (!isset($arrayData['angel']['name'])) ? "" : ",N'".str_replace("'","\'",$arrayData['angel']['name'])."'";

            $mysqli = $GLOBALS["mysqli"];
            $query = $mysqli->query("INSERT INTO heroes(".$db_names.") VALUES (".$db_values.")");
            
            if ($query)
                RegisterUpdates::dbUpdateValues("heroes");

            return $query;
        }

        public function dbUpdate($arrayData) {
            if ($arrayData == null || !isset($this -> value['id'])) return false;

            $db_values = "id=".$this -> value['id'];
            $db_values .= (!isset($arrayData['account']['hero']['base']['name'])) ? "" : ",name=N'".str_replace("'","\'",$arrayData['account']['hero']['base']['name'])."'";
            $db_values .= (!isset($arrayData['angel']['name'])) ? "" : ",angel_name=N'".str_replace("'","\'",$arrayData['angel']['name'])."'";
            $db_values .= (!isset($arrayData['account']['hero']['might'])) ? "" : ",might=".Round($arrayData['account']['hero']['might']['value'],1);
            $db_values .= (isset($arrayData['angel']['permissions']['can_affect_game']) && $arrayData['angel']['permissions']['can_affect_game']) ? ",can_affect_game=true" : ",can_affect_game=false";
            $db_values .= (!isset($arrayData['account']['last_visit'])) ? "" : ",last_visit=N'".date('Y-m-d H:i:s', $arrayData['account']['last_visit'])."'";
            $db_values .= (isset($arrayData['account']['hero']['base']['alive']) && $arrayData['account']['hero']['base']['alive']) ? ",alive=true" : ",alive=false";

            // dog-nail for multi-accounts
            if ($this -> value['id'] == "27216" || $this -> value['id'] == "27217" || $this -> value['id'] == "27239" || $this -> value['id'] == "27241" || $this -> value['id'] == "27248" || $this -> value['id'] == "35056" || $this -> value['id'] == "35058" || $this -> value['id'] == "35059" || $this -> value['id'] == "35060" || $this -> value['id'] == "35061" || $this -> value['id'] == "51117" || $this -> value['id'] == "51208" || $this -> value['id'] == "51221") {
                $arrayData['angel']['clan']['id'] = "100500";
                $arrayData['angel']['clan']['abbr'] = "ArgoF";
            } else if ( $this -> value['id'] == "51400" || $this -> value['id'] == "51401" || $this -> value['id'] == "51402" || $this -> value['id'] == "51403" || $this -> value['id'] == "51405" || $this -> value['id'] == "51407" || $this -> value['id'] == "51409" || $this -> value['id'] == "59328" || $this -> value['id'] == "59361" || $this -> value['id'] == "59362" || $this -> value['id'] == "59388" || $this -> value['id'] == "59398" || $this -> value['id'] == "59399" || $this -> value['id'] == "59975" || $this -> value['id'] == "59976" || $this -> value['id'] == "59978" || $this -> value['id'] == "59979") {
                $arrayData['angel']['clan']['id'] = "100501";
                $arrayData['angel']['clan']['abbr'] = "ArgoT";
            } else if ($this -> value['id'] == "5337" || $this -> value['id'] == "29292" || $this -> value['id'] == "67298" || $this -> value['id'] == "67299" || $this -> value['id'] == "67300" || $this -> value['id'] == "67589" || $this -> value['id'] == "67899" || $this -> value['id'] == "70585" || $this -> value['id'] == "71188" || $this -> value['id'] == "72331" || $this -> value['id'] == "72332" || $this -> value['id'] == "72333" || $this -> value['id'] == "72439") {
                $arrayData['angel']['clan']['id'] = "100499";
                $arrayData['angel']['clan']['abbr'] = "ExP";
            }
            
            $db_values .= (!isset($arrayData['angel']['clan']['id'])) ? ",clan_id=NULL" : ",clan_id=".$arrayData['angel']['clan']['id'];
            $db_values .= (!isset($arrayData['angel']['clan']['abbr'])) ? ",clan_name=NULL" : ",clan_name=N'".str_replace("'","\'",$arrayData['angel']['clan']['abbr'])."'";

            $db_values .= (!isset($arrayData['account']['hero']['base']['gender'])) ? "" : ",gender=".$arrayData['account']['hero']['base']['gender'];
            $db_values .= (!isset($arrayData['account']['hero']['base']['race'])) ? "" : ",race=".$arrayData['account']['hero']['base']['race'];
            $db_values .= (!isset($arrayData['account']['hero']['base']['level'])) ? "" : ",level=".$arrayData['account']['hero']['base']['level'];
            $db_values .= (!isset($arrayData['account']['hero']['base']['experience_to_level']) || !isset($arrayData['account']['hero']['base']['experience'])) ? "" : ",exp=".($arrayData['account']['hero']['base']['experience_to_level'] - $arrayData['account']['hero']['base']['experience']);
            $db_values .= (!isset($arrayData['angel']['permissions']['can_affect_game'])) ? "" : ",power=".Round($arrayData['angel']['ratings']['politics_power']['value']*100,0);
            $db_values .= (!isset($arrayData['account']['hero']['companion']['name'])) ? "" : ",companion=N'".str_replace("'","\'",$arrayData['account']['hero']['companion']['name'])."'";
            $db_values .= (!isset($arrayData['account']['hero']['habits']['peacefulness']['raw'])) ? "" : ",peacefulness=".$arrayData['account']['hero']['habits']['peacefulness']['raw'];
            $db_values .= (!isset($arrayData['account']['hero']['habits']['peacefulness']['verbose'])) ? "" : ",peacefulness_verbose=N'".$arrayData['account']['hero']['habits']['peacefulness']['verbose']."'";
            $db_values .= (!isset($arrayData['account']['hero']['habits']['honor']['raw'])) ? "" : ",honor=".$arrayData['account']['hero']['habits']['honor']['raw'];
            $db_values .= (!isset($arrayData['account']['hero']['habits']['honor']['verbose'])) ? "" : ",honor_verbose=N'".$arrayData['account']['hero']['habits']['honor']['verbose']."'";
            $db_values .= (!isset($arrayData['account']['hero']['base']['money'])) ? "" : ",money=".$arrayData['account']['hero']['base']['money'];
            $db_values .= (!isset($arrayData['account']['hero']['secondary']['power'][0])) ? "" : ",physic=".$arrayData['account']['hero']['secondary']['power'][0];
            $db_values .= (!isset($arrayData['account']['hero']['secondary']['power'][1])) ? "" : ",magic=".$arrayData['account']['hero']['secondary']['power'][1];
            $db_values .= (!isset($arrayData['account']['hero']['equipment']) || !isset($arrayData['account']['hero']['base']['level'])) ? "" : ",virt_strength=".$this -> computeVirtualStrength($arrayData['account']['hero']['equipment'], $arrayData['account']['hero']['base']['level']);
            $db_values .= (!isset($arrayData['account']['hero']['secondary']['power'][1]) || !isset($arrayData['account']['hero']['secondary']['power'][0])) ? "" : ",strength=".($arrayData['account']['hero']['secondary']['power'][1] + $arrayData['account']['hero']['secondary']['power'][0]);
            $db_values .= (!isset($arrayData['account']['hero']['equipment'])) ? "" : ",avg_equip=".$this -> computeAvgPreference($arrayData['account']['hero']['equipment']);
            if (isset($arrayData['account']['hero']['equipment'])) {
                $equip = $this -> computeRareEquip($arrayData['account']['hero']['equipment']);
                $db_values .= ",rare_equip_title=N'".$equip."'";
            }
            $db_values .= (!isset($arrayData['account']['hero']['secondary']['move_speed'])) ? "" : ",speed=".Round($arrayData['account']['hero']['secondary']['move_speed'],3);
            $db_values .= (!isset($arrayData['account']['hero']['secondary']['initiative'])) ? "" : ",initiative=".Round($arrayData['account']['hero']['secondary']['initiative'],3);
            $db_values .= (!isset($arrayData['account']['hero']['position']['x']) || !isset($arrayData['account']['hero']['position']['y'])) ? "" : ",position=N'".Round($arrayData['account']['hero']['position']['x'],0).",".Round($arrayData['account']['hero']['position']['y'],0)."'";
            if (isset($arrayData['account']['hero']['quests'])) {
                $quests = $arrayData['account']['hero']['quests']['quests']; 
                $is_no_quest = false;
                foreach ($quests as $qk => $qv) {
                    if ($qk == 0) continue; // don't need this quest
                    $lines = $qv['line'];
                    $raw_result['value'] = "<ul>";
                    foreach ($lines as $lk => $lv) { 
                        $actors = $lv['actors'];
                        if ($qk == 1) {
                            if ($lv['uid'] == "no-quest") {
                                unset($raw_result['value']);
                                $raw_result['name'] = $lv['name'];
                                $is_no_quest = true;
                                break;
                            }
                            else {
                                $raw_result['name'] = $lv['name'];
                            }
                        }
                        $raw_result['value'] .= "<li title='".$lv['experience']." ед. опыта'>".$lv['name']." (".$lv['power'].")";
                        foreach ($actors as $ak => $av) {
                            if ($av[1] != 2) {
                                if ($ak == 0)
                                    $raw_result['value'] .= ": ";
                                else
                                    $raw_result['value'] .= " — ";
                            }
                            if ($av[1] == 0) {
                                $raw_result['value'] .= "<b>".$av[2]['name']."</b>";
                                $raw_result['value'] .= " (".PlaceModel::getNameById($av[2]['place']).")";
                            } else if ($av[1] == 1) {
                                $raw_result['value'] .= "<b>".$av[2]['name']."</b>";
                            }
                        }
                        $raw_result['value'] .= "</li>";
                    } 
                }
                if (!$is_no_quest)
                    $raw_result['value'] .= "</ul>";
                $db_values .= (isset($raw_result['name'])) ? ",quest=N'".str_replace("'","\'",$raw_result['name'])."'" : ",quest=NULL";
                $db_values .= (isset($raw_result['value'])) ? ",quest_list=N'".str_replace("'","\'",$raw_result['value'])."'" : ",quest_list=NULL";
            }

            $mysqli = $GLOBALS["mysqli"];
            $query = $mysqli->query("UPDATE heroes SET ".$db_values." WHERE id=".$this -> value["id"]);
            
            if ($query)
                RegisterUpdates::dbUpdateValues("heroes");
            if ($arrayData['angel']['permissions']['can_affect_game'] && (time() - $arrayData['account']['last_visit'] < 84600 * 4))
                $this -> dbUpdatePlacesHistory($arrayData);

            return $query;
        }

        public function dbUpdatePlacesHistory($arrayData) {
            if ($arrayData == null || !isset($this -> value['id']) || !isset($arrayData['angel']['places_history'])) return false;

            $db_values = "";
            $i = 1;
            $last_value = 100;
            foreach ($arrayData['angel']['places_history'] as $key => $value) {
                if ($i == 10) {
                    if (isset($value['count']))
                        $last_value = $value['count'];
                } else if ($i > 10) {
                    if ($value['count'] != $last_value)
                        break;
                }
                if (!empty($db_values))
                    $db_values .= ",";
                if (!isset($value['place']['id']))
                    return false;
                $db_values .= "(".$this -> value['id'].",".$value['place']['id'].",N'".$arrayData['angel']['name']."',";
                $db_values .= (!isset($arrayData['angel']['clan'])) ? "NULL, NULL" : "N'".$arrayData['angel']['clan']['abbr']."',".$arrayData['angel']['clan']['id'];
                $db_values .= ")";
                ++$i;
            }

            $mysqli = $GLOBALS["mysqli"];
            $query = $mysqli->query("INSERT INTO places_history(angel_id,city_id,angel_name,clan_name,clan_id) VALUES ".$db_values);

            return $query;
        }

        public function getValues() {
            $result = array();
            $row_result = "";
            $index = 0;

            $result[$index] = array();
            if (isset($this -> value['angel_name']) && isset($this -> value['id'])) {
                $raw_result = PrepareToView::createUrl("http://the-tale.org/accounts/".$this -> value['id'],$this -> value['angel_name'],"Посещал ".$this -> value['last_visit']);
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);
            if (isset($this -> value['can_affect_game']) && $this -> value['can_affect_game']) {
                $raw_result = PrepareToView::createImg("can_affect.png","Длань Судьбы");
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['might'])) ? $this -> value['might'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['clan_id']) && isset($this -> value['clan_name']) && $this -> value['clan_id'] < 100500) {
                $raw_result = PrepareToView::createUrl("http://the-tale.org/clans/".$this -> value['clan_id'],$this -> value['clan_name']);
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['id']) && isset($this -> value['name'])) {
                $raw_result = PrepareToView::createUrl("http://the-tale.org/game/heroes/".$this -> value['id'],$this -> value['name']);
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);
            if (isset($this -> value['alive'])) {
                if ($this -> value['alive'] ) 
                    $raw_result = PrepareToView::createImg("alive.png","Жив");
                else
                    $raw_result = PrepareToView::createImg("dead.png","Мёртв");
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['gender']) && isset($this -> value['race'])) ? Dictionary::getRace($this -> value['gender'],  $this -> value['race']) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['level']) && isset($this -> value['exp'])) {
                $raw_result = PrepareToView::createText($this -> value['exp']." ед. опыта до следующего уровня",$this -> value['level']);
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['power'])) ? $this -> value['power'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['companion'])) ? $this -> value['companion'] : "";
            unset($col);
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['money'])) ? $this -> value['money'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['virt_strength']) && isset($this -> value['level'])) {
                $equip_level = Round($this -> value['virt_strength']/12,0);
                $diff_level = Round($equip_level / $this -> value['level'], 3);
                $raw_result = PrepareToView::createText($equip_level." (".$diff_level." раза)", $this -> value['virt_strength']);
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['strength']) && isset($this -> value['level'])) {
                $equip_level = Round($this -> value['strength']/12,0);
                $diff_level = $equip_level - $this -> value['level'];
                $diff_level = ($diff_level > 0) ? "+".$diff_level : $diff_level;
                $raw_result = PrepareToView::createText($equip_level." (".$diff_level.")", $this -> value['strength']);
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['physic'])) ? $this -> value['physic'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['magic'])) ? $this -> value['magic'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['avg_equip']) && isset($this -> value['rare_equip_title'])) {
                $raw_result = PrepareToView::createText($this -> value['rare_equip_title'],$this -> value['avg_equip']);
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['speed'])) ? $this -> value['speed'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['initiative'])) ? $this -> value['initiative'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['peacefulness']) && isset($this -> value['peacefulness_verbose']) && isset($this -> value['honor']) && isset($this -> value['honor_verbose'])) {
                if (Dictionary::getPeace($this -> value['peacefulness_verbose']) == 4)
                    $raw_result = PrepareToView::createImg("frontier.png",$this -> value['peacefulness_verbose']);
                else if ($this -> value['peacefulness'] > 0)
                    $raw_result = PrepareToView::createImg("good.png",$this -> value['peacefulness_verbose']);
                else 
                    $raw_result = PrepareToView::createImg("bad.png",$this -> value['peacefulness_verbose']);
                array_push($result[$index], $raw_result);
                unset($raw_result);
                if (Dictionary::getHonor($this -> value['honor_verbose']) == 4)
                    $raw_result = PrepareToView::createImg("frontier.png",$this -> value['honor_verbose']);
                else if ($this -> value['honor'] > 0)
                    $raw_result = PrepareToView::createImg("good.png",$this -> value['honor_verbose']);
                else 
                    $raw_result = PrepareToView::createImg("bad.png",$this -> value['honor_verbose']);
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            /*unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['position'])) ? $this -> value['position'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['quest'])) {
                if ($this -> value['quest'] != "безделье" && isset($this -> value['quest_list']))
                    $raw_result = PrepareToView::createSpoiler($this -> value['quest'],$this -> value['quest_list']);
                else
                    $raw_result = $this -> value['quest'];
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);*/

            return $result;
        } 

        private function computeVirtualStrength($artifacts, $level) {
            $result_power = 0;
            foreach ($artifacts as $i => $artifact) {
                if ($artifact['rarity'] == 2)
                    $result_power += ($artifact['power'][0] + $artifact['power'][1]) * 2;
                else if ($artifact['rarity'] == 1)
                    $result_power += ($artifact['power'][0] + $artifact['power'][1]) * 1.5;
                else
                    $result_power += ($artifact['power'][0] + $artifact['power'][1]);
            }
            $result_power += ($level/2 + 1)*2;
            return Round($result_power, 0);
        }

        private function computeRareEquip($artifacts) {    
            $rare_count = 0;
            $epic_count = 0;
            foreach ($artifacts as $i => $artifact) {
                if ($artifact['rarity'] == 2)
                    $epic_count++;
                else if ($artifact['rarity'] == 1)
                    $rare_count++;
            }
            $compute_level = ($epic_count > 0) ? $epic_count." epic ": "";
            $compute_level .= ($rare_count > 0) ? $rare_count." rare": "";
            return $compute_level;
        }

        private function computeAvgPreference($artifacts) {
            $preference_sum = 0;     
            foreach ($artifacts as $i => $artifact) 
                $preference_sum += $artifact['preference_rating'];    
            $compute_avg = Round($preference_sum/count($artifacts),0);      
            return $compute_avg;
        }

        public function getAngel() {
            if (isset($this -> value['angel_name'])) {
                return $this -> value['angel_name'];
            }
            return 0;
        }

        public function getMight() {
            if (isset($this -> value['might'])) {
                return $this -> value['might'];
            }
            return 0;
        }

        public function getClan() {
            if (isset($this -> value['clan_id'])) {
                $result['clan'] = ($this -> value['clan_id'] < 100500) ? $this -> value['clan_id'] : 0;
                $result['lvl'] = $this -> value['level'];
                return $result;
            }
            return 0;
        }

        public function getHero() {
            if (isset($this -> value['name'])) {
                return $this -> value['name'];
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

        public function getLevel() {
            if (isset($this -> value['level']) && isset($this -> value['exp'])) {
                $result['lvl'] = $this -> value['level'];
                $result['exp'] = -($this -> value['exp']);
                return $result;
            }
            return 0;
        }

        public function getPower() {
            if (isset($this -> value['power'])) {
                return $this -> value['power'];
            }
            return 0;
        }

        public function getCompanion() {
            if (isset($this -> value['companion'])) {
                return $this -> value['companion'];
            }
            return 0;
        }

        public function getHabits() {
            if (isset($this -> value['peacefulness_verbose']) && isset($this -> value['honor_verbose'])) {
                $result['peace'] = Dictionary::getPeace($this -> value['peacefulness_verbose']);
                $result['honor'] = Dictionary::getHonor($this -> value['honor_verbose']);
                $result['lvl'] = $this -> value['level'];
                return $result;
            }
            return 0;
        }

        public function getMoney() {
            if (isset($this -> value['money'])) {
                return $this -> value['money'];
            }
            return 0;
        }

        public function getVirtualStrength() {
            if (isset($this -> value['virt_strength'])) {
                return $this -> value['virt_strength'];
            }
            return 0;
        }

        public function getStrength() {
            if (isset($this -> value['strength'])) {
                return $this -> value['strength'];
            }
            return 0;
        }

        public function getPhysic() {
            if (isset($this -> value['physic'])) {
                return $this -> value['physic'];
            }
            return 0;
        }

        public function getMagic() {
            if (isset($this -> value['magic'])) {
                return $this -> value['magic'];
            }
            return 0;
        }

        public function getEquiment() {
            if (isset($this -> value['avg_equip'])) {
                return $this -> value['avg_equip'];
            }
            return 0;
        }

        public function getSpeed() {
            if (isset($this -> value['speed'])) {
                return $this -> value['speed'];
            }
            return 0;
        }

        public function getInitiative() {
            if (isset($this -> value['initiative'])) {
                return $this -> value['initiative'];
            }
            return 0;
        }

		function __destruct() {}
	}
?>