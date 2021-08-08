<?php
    include_once("GameModel.php");
    include_once("PlaceModel.php");

    class EmissaryModel extends GameModel   
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
            $result = $mysqli->query("SELECT id FROM emissaries");
            
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

        public static function dbSelectAllDeleted()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT id FROM emissaries WHERE status LIKE N'%убит%' OR N'%уволен%'");
            
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

        public static function dbSelectAllAlive()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT id FROM emissaries WHERE status LIKE N'%Работает%'");
            
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

		public function dbSelectById()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT * FROM emissaries WHERE id=".$this -> value["id"]);
            $row = $result->fetch_assoc();
            if (isset($row)) {
                $this -> value = $row;
                return true;
            } else
                return false;
        }

        public static function dbCountEmissariesByClanId($clanId)
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("
                        SELECT COUNT(clan_id) as num
                        FROM emissaries
                        WHERE status LIKE N'%Работает%' 
                        AND clan_id=".$clanId." 
                        GROUP BY clan_id");
            $row = $result->fetch_assoc();
            return $row["num"];
        }

		public function dbUpdateValues($arrayByCurl) {
            $this -> value['id'] = $arrayByCurl['id'];

            if ($this -> dbSelectById()) {
                return $this -> dbUpdate($arrayByCurl);
            } else {
                if ($this -> dbInsert($arrayByCurl))
                    return $this -> dbUpdate($arrayByCurl);
                else
                    return false;
            }
        }

        public function dbInsert($arrayData) {
            if ($arrayData == null || !isset($arrayData['id'])) return false;

            $db_names = "id";
            $db_values = $arrayData['id'];
            $db_names .= (!isset($arrayData['name'])) ? "" : ",name";
            $db_values .= (!isset($arrayData['name'])) ? "" : ",N'".str_replace("'","\'",$arrayData['name'])."'";

            $mysqli = $GLOBALS["mysqli"];
            $query = $mysqli->query("INSERT INTO emissaries(".$db_names.") VALUES (".$db_values.")");
            
            if ($query)
                RegisterUpdates::dbUpdateValues("emissaries");

            return $query;
        }

        public function dbUpdate($arrayData) {
            if ($arrayData == null || !isset($this -> value['id'])) return false;

            $db_values = "id=".$this -> value['id'];
            $db_values .= (!isset($arrayData['name'])) ? "" : ",name=N'".str_replace("'","\'",$arrayData['name'])."'";

            $db_values .= (!isset($arrayData['gender'])) ? "" : ",gender=".$arrayData['gender'];
            $db_values .= (!isset($arrayData['race'])) ? "" : ",race=".$arrayData['race'];
            $db_values .= (!isset($arrayData['health'])) ? "" : ",health=".$arrayData['health'];
            $db_values .= (!isset($arrayData['power'])) ? "" : ",power=".$arrayData['power'];
            $db_values .= (!isset($arrayData['status'])) ? "" : ",status=N'".$arrayData['status']."'";
            $db_values .= (!isset($arrayData['ability'])) ? "" : ",ability=".$arrayData['ability'];
			$db_values .= (!isset($arrayData['clan_id'])) ? ",clan_id=NULL" : ",clan_id=".$arrayData['clan_id'];
            $db_values .= (!isset($arrayData['clan_name'])) ? ",clan_name=NULL" : ",clan_name=N'".str_replace("'","\'",$arrayData['clan_name'])."'";
            $db_values .= (!isset($arrayData['city_id'])) ? "" : ",city_id=".$arrayData['city_id'];

            $db_values .= (!isset($arrayData['warfare'])) ? "" : ",warfare=".$arrayData['warfare'];
            $db_values .= (!isset($arrayData['cultural_science'])) ? "" : ",cultural_science=".$arrayData['cultural_science'];
            $db_values .= (!isset($arrayData['politival_science'])) ? "" : ",politival_science=".$arrayData['politival_science'];
            $db_values .= (!isset($arrayData['religious_studies'])) ? "" : ",religious_studies=".$arrayData['religious_studies'];
            $db_values .= (!isset($arrayData['sociology'])) ? "" : ",sociology=".$arrayData['sociology'];
            $db_values .= (!isset($arrayData['covert_operations'])) ? "" : ",covert_operations=".$arrayData['covert_operations'];
            $db_values .= (!isset($arrayData['technologies'])) ? "" : ",technologies=".$arrayData['technologies'];
            $db_values .= (!isset($arrayData['economy'])) ? "" : ",economy=".$arrayData['economy'];

            $mysqli = $GLOBALS["mysqli"];
            $query = $mysqli->query("UPDATE emissaries SET ".$db_values." WHERE id=".$this -> value["id"]);

            if ($query)
                RegisterUpdates::dbUpdateValues("emissaries");

            return $query;
        }

        public function getValues() {
            $result = array();
            $row_result = "";
            $index = 0;

            $result[$index] = array();
            $raw_result = (isset($this -> value['status'])) ? $this -> value['status'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            if (isset($this -> value['id']) && isset($this -> value['name'])) {
                $raw_result = PrepareToView::createUrl("http://the-tale.org/game/emissaries/".$this -> value['id'],$this -> value['name']);
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
            if (isset($this -> value['clan_id']) && isset($this -> value['clan_name'])) {
                $raw_result = PrepareToView::createUrl("http://the-tale.org/clans/".$this -> value['clan_id'],$this -> value['clan_name']);
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
            $raw_result = (isset($this -> value['health'])) ? $this -> value['health'] : "";
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
            $raw_result = (isset($this -> value['ability'])) ? $this -> value['ability'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['warfare'])) ? $this -> value['warfare'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['cultural_science'])) ? $this -> value['cultural_science'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['politival_science'])) ? $this -> value['politival_science'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['religious_studies'])) ? $this -> value['religious_studies'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['sociology'])) ? $this -> value['sociology'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['covert_operations'])) ? $this -> value['covert_operations'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['technologies'])) ? $this -> value['technologies'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['economy'])) ? $this -> value['economy'] : "";
            array_push($result[$index], $raw_result);
            unset($raw_result);

            return $result; 
		}

		public function getEmissary() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['name'] = $this -> value['name'];
                $result['city'] = PlaceModel::getNameById($this -> value['city_id']);
                return $result;
            }
            return 0;
        }

        public function getStatus() {
            if (isset($this -> value['id'])) {
            	$result = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                return $result;
            }
            return 0;
        }

        public function getRace() {
        	if (isset($this -> value['gender']) && isset($this -> value['race'])) {
        		$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['race'] = $this -> value['race'];
                $result['gender'] = $this -> value['gender'];
                return $result;
            }
            return 0;
        }

        public function getClan() {
            if (isset($this -> value['clan_id'])) {
                $result['clan'] = $this -> value['clan_id'];
                return $result;
            }
            return 0;
        }

        public function getCity() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['city'] = PlaceModel::getNameById($this -> value['city_id']);
                return $result;
            }
            return 0;
        }

        public function getAbility() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['ability'] = $this -> value['ability'];
                return $result;
            }
            return 0;
        }

        public function getPower() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['power'] = $this -> value['power'];
                return $result;
            }
            return 0;
        }

        public function getHealth() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['health'] = $this -> value['health'];
                return $result;
            }
            return 0;
        }

        public function getWarfare() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['warfare'] = $this -> value['warfare'];
                return $result;
            }
            return 0;
        }

        public function getCultural_science() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['cultural_science'] = $this -> value['cultural_science'];
                return $result;
            }
            return 0;
        }

        public function getPolitival_science() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['politival_science'] = $this -> value['politival_science'];
                return $result;
            }
            return 0;
        }

        public function getReligious_studies() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['religious_studies'] = $this -> value['religious_studies'];
                return $result;
            }
            return 0;
        }

        public function getSociology() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['sociology'] = $this -> value['sociology'];
                return $result;
            }
            return 0;
        }

        public function getCovert_operations() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['covert_operations'] = $this -> value['covert_operations'];
                return $result;
            }
            return 0;
        }

        public function getTechnologies() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['technologies'] = $this -> value['technologies'];
                return $result;
            }
            return 0;
        }

        public function getEconomy() {
            if (isset($this -> value['id'])) {
            	$result['status'] = (isset($this -> value['status']) && ($this -> value['status'] == "Работает")) ? 1 : 0;
                $result['economy'] = $this -> value['economy'];
                return $result;
            }
            return 0;
        }


		function __destruct() {}           
    }
?>