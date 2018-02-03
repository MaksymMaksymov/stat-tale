<?php
    include_once("GameModel.php");
    include_once("AngelModel.php");

	class HeroModel extends GameModel   
    {
    	private $value;
        private static $places;

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

        public function setAngel($angel) {
            $this -> value['angel'] = $angel -> getValues(); 
        }
        
        public function getValues() { // content, type=url,a,img
            $result = array();
            $row_result = "";
            $index = 0;

            $result[$index] = array();
            if (isset($this -> value['angel']['name']) && isset($this -> value['account']['id'])) {
                $raw_result['type'] = "url";
                $raw_result['href'] = "http://the-tale.org/accounts/".$this -> value['account']['id'];
                $raw_result['value'] = $this -> value['angel']['name'];
                $raw_result['title'] = "Посещал ".date('d.m.Y', $this -> value['account']['last_visit']);
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);
            if (isset($this -> value['angel']['permissions']) && $this -> value['angel']['permissions']['can_affect_game']) {
                $raw_result['type'] = "img";
                $raw_result['src'] = "can_affect.png";
                $raw_result['alt'] = "Длань Судьбы";
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['account']['hero']['might'])) ? Round($this -> value['account']['hero']['might']['value'],1) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['angel']['clan'])) {
                $raw_result['type'] = "url";
                $raw_result['href'] = "http://the-tale.org/accounts/clans/".$this -> value['angel']['clan']['id'];
                $raw_result['value'] = $this -> value['angel']['clan']['abbr'];
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['account']['id']) && isset($this -> value['account']['hero']['base']['name'])) {
                $raw_result['type'] = "url";
                $raw_result['href'] = "http://the-tale.org/game/heroes/".$this -> value['account']['id'];
                $raw_result['value'] = $this -> value['account']['hero']['base']['name'];
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);
            if (isset($this -> value['account']['hero']['base']['alive'])) {
                if ($this -> value['account']['hero']['base']['alive'] == true) {
                    $raw_result['type'] = "img";
                    $raw_result['src'] = "alive.png";
                    $raw_result['alt'] = "Жив";
                } else if ($this -> value['account']['hero']['base']['alive'] == false) {
                    $raw_result['type'] = "img";
                    $raw_result['src'] = "dead.png";
                    $raw_result['alt'] = "Мертв";
                } else 
                    $raw_result = "";
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['account']['hero']['base']['gender']) && isset($this -> value['account']['hero']['base']['race'])) ? Dictionary::getRace($this -> value['account']['hero']['base']['gender'],  $this -> value['account']['hero']['base']['race']) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['account']['hero']['base']['level'])) {
                $raw_result['type'] = "text";
                $raw_result['value'] = $this -> value['account']['hero']['base']['level'];
                $raw_result['title'] = $this -> value['account']['hero']['base']['experience_to_level'] - $this -> value['account']['hero']['base']['experience']." ед. опыта до следующего уровня";
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['angel']['ratings']) && ($this -> value['angel']['permissions']['can_affect_game'])) ? Round($this -> value['angel']['ratings']['politics_power']['value']*100,0) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['account']['hero']['companion'])) ? $this -> value['account']['hero']['companion']['name'] : "";
            unset($col);
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['account']['hero']['habits'])) {
                $raw_result['type'] = "img";
                $raw_result['src'] = ($this -> value['account']['hero']['habits']['peacefulness']['raw'] > 0) ? "good.png" : "bad.png";
                $raw_result['alt'] = $this -> value['account']['hero']['habits']['peacefulness']['verbose'];
                array_push($result[$index], $raw_result);
                unset($raw_result);
                $raw_result['type'] = "img";
                $raw_result['src'] = ($this -> value['account']['hero']['habits']['honor']['raw'] > 0) ? "good.png" : "bad.png";
                $raw_result['alt'] = $this -> value['account']['hero']['habits']['honor']['verbose'];
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['account']['hero']['base']['money'])) ? $this -> value['account']['hero']['base']['money'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['account']['hero']['secondary']['power'])) ? $this -> value['account']['hero']['secondary']['power'][0] + $this -> value['account']['hero']['secondary']['power'][1] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['account']['hero']['secondary']['power'])) ? $this -> value['account']['hero']['secondary']['power'][0] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['account']['hero']['secondary']['power'])) ? $this -> value['account']['hero']['secondary']['power'][1] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['account']['hero']['equipment'])) {
                $tmp_result = $this -> computeLvlEquip($this -> value['account']['hero']['equipment']);
                $raw_result["type"] = "text";
                $raw_result["value"] = $tmp_result["value"];
                $raw_result["title"] = $tmp_result["title"];
            } else 
                $raw_result = "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['account']['hero']['equipment'])) ? $this -> computeAvgPreference($this -> value['account']['hero']['equipment']) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['account']['hero']['secondary']['move_speed'])) ? $this -> value['account']['hero']['secondary']['move_speed'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['account']['hero']['secondary']['initiative'])) ? Round($this -> value['account']['hero']['secondary']['initiative'],3) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['account']['hero']['position'])) ? Round($this -> value['account']['hero']['position']['x'],0).",".Round($this -> value['account']['hero']['position']['y'],0) : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['account']['hero']['quests'])) {
                $quests = $this -> value['account']['hero']['quests']['quests']; 
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
                                $raw_result = $lv['name'];
                                $is_no_quest = true;
                                break;
                            }
                            else {
                                $raw_result['type'] = "spoiler";
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
                                if (!$this -> checkPlaces()) 
                                    $raw_result['value'] .= " (".$this -> findPlaceById($av[2]['place']).")";
                            } else if ($av[1] == 1) {
                                $raw_result['value'] .= "<b>".$av[2]['name']."</b>";
                            }
                        }
                        $raw_result['value'] .= "</li>";
                    } 
                }
                if (!$is_no_quest)
                    $raw_result['value'] .= "</ul>";
            } else
                $raw_result = "";
            array_push($result[$index], $raw_result);

            return $result;
        } 

        public static function setPlacesDictionary($arr_places) {
            HeroModel::$places = $arr_places;
        }

        private function findPlaceById($id) {
            return HeroModel::$places[$id];
        }

        private function checkPlaces() {
            return HeroModel::$places == null;
        }

        private function computeLvlEquip($artifacts) {
            $power_sum = 0;     
            $rare_count = 0;
            $epic_count = 0;
            for ($i = 0; $i < count($artifacts); $i++) {
                $power_sum += $artifacts[$i]['power'][0] + $artifacts[$i]['power'][1];  
                if ($artifacts[$i]['rarity'] == 2)
                        $epic_count++;
                else if ($artifacts[$i]['rarity'] == 1)
                    $rare_count++;
            }
            $compute_level['value'] = Round($power_sum/(count($artifacts) + 1),0);
            $compute_level['title'] = ($epic_count > 0) ? $epic_count." epic ": "";
            $compute_level['title'] .= ($rare_count > 0) ? $rare_count." rare": "";
            return $compute_level;
        }

        private function computeAvgPreference($artifacts) {
            $preference_sum = 0;     
            for ($i = 0; $i < count($artifacts); $i++)
                $preference_sum += $artifacts[$i]['preference_rating'];    
            $compute_avg = Round($preference_sum/count($artifacts),0);      
            return $compute_avg;
        }

        public function getAngel() {
            if (isset($this -> value['angel']['name'])) {
                return $this -> value['angel']['name'];
            }
            return 0;
        }

        public function getMight() {
            if (isset($this -> value['account']['hero']['might']['value'])) {
                return Round($this -> value['account']['hero']['might']['value'],1);
            }
            return 0;
        }

        public function getClan() {
            if (isset($this -> value['angel']['clan'])) {
                return $this -> value['angel']['clan']['id'];
            }
            return 0;
        }

        public function getHero() {
            if (isset($this -> value['account']['hero']['base']['name'])) {
                return $this -> value['account']['hero']['base']['name'];
            }
            return 0;
        }

        public function getRace() {
            if (isset($this -> value['account']['hero']['base']['gender']) && isset($this -> value['account']['hero']['base']['race'])) {
                $result['race'] = $this -> value['account']['hero']['base']['race'];
                $result['gender'] = $this -> value['account']['hero']['base']['gender'];
                return $result;
            }
            return 0;
        }

        public function getLevel() {
            if (isset($this -> value['account']['hero']['base']['level']) && isset($this -> value['account']['hero']['base']['experience'])) {
                $result['lvl'] = $this -> value['account']['hero']['base']['level'];
                $result['exp'] = $this -> value['account']['hero']['base']['experience'];
                return $result;
            }
            return 0;
        }

        public function getPower() {
            if (isset($this -> value['angel']['ratings']) && ($this -> value['angel']['permissions']['can_affect_game'])) {
                return Round($this -> value['angel']['ratings']['politics_power']['value']*100,2);
            }
            return 0;
        }

        public function getCompanion() {
            if (isset($this -> value['account']['hero']['companion']['type'])) {
                return $this -> value['account']['hero']['companion']['type'];
            }
            return 0;
        }

        public function getHabits() {
            if (isset($this -> value['account']['hero']['habits'])) {
                $result['peace'] = $this -> value['account']['hero']['habits']['peacefulness']['raw'];
                $result['honor'] = $this -> value['account']['hero']['habits']['honor']['raw'];
                return $result;
            }
            return 0;
        }

        public function getMoney() {
            if (isset($this -> value['account']['hero']['base']['money'])) {
                return $this -> value['account']['hero']['base']['money'];
            }
            return 0;
        }

        public function getStrength() {
            if (isset($this -> value['account']['hero']['secondary']['power'])) {
                return $this -> value['account']['hero']['secondary']['power'][0] + $this -> value['account']['hero']['secondary']['power'][1];
            }
            return 0;
        }

        public function getPhysic() {
            if (isset($this -> value['account']['hero']['secondary']['power'])) {
                return $this -> value['account']['hero']['secondary']['power'][0];
            }
            return 0;
        }

        public function getMagic() {
            if (isset($this -> value['account']['hero']['secondary']['power'])) {
                return $this -> value['account']['hero']['secondary']['power'][1];
            }
            return 0;
        }

        public function getEquiment() {
            if (isset($this -> value['account']['hero']['equipment'])) {
                $result = $this -> computeLvlEquip($this -> value['account']['hero']['equipment']);
                return $result['value'];
            }
            return 0;
        }

        public function getPreference() {
            if (isset($this -> value['account']['hero']['equipment'])) {
                return $this -> computeAvgPreference($this -> value['account']['hero']['equipment']);
            }
            return 0;
        }

        public function getSpeed() {
            if (isset($this -> value['account']['hero']['secondary']['move_speed'])) {
                return $this -> value['account']['hero']['secondary']['move_speed'];
            }
            return 0;
        }

        public function getInitiative() {
            if (isset($this -> value['account']['hero']['secondary']['initiative'])) {
                return $this -> value['account']['hero']['secondary']['initiative'];
            }
            return 0;
        }

		function __destruct() {}
	}
?>