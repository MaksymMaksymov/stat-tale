<?php
    include_once("PlaceModel.php");

	class VoiceModel
    {
    	private $value;

		function __construct() {}   
		
		public function setValue($id) {
            $this -> value["id"] = $id;
            return $this -> dbSelectById(); 
		}

        public function dbSelectById()
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT * FROM places_history WHERE city_id=".$this -> value["id"]);
            if (isset($result)) {
                $this -> value['data'] = array();
                while ($row = $result->fetch_assoc()) {
                    array_push($this -> value['data'], $row);
                }
                return true;
            } else
                return false;
        }

        public static function dbDeletePlacesHistory($id) {
            $mysqli = $GLOBALS["mysqli"];
            $query = $mysqli->query("DELETE FROM places_history WHERE angel_id = ".$id);
            return $query;
        }

        public function getValues() {
            $result = array();
            $row_result = "";
            $index = 0;

            $result[$index] = array();
            $raw_result = PrepareToView::createUrl("http://the-tale.org/game/places/".$this -> value['id'],PlaceModel::getNameById($this -> value['id']));
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            $result[$index] = array();
            $raw_result = (isset($this -> value['data'])) ? count($this -> value['data']) : 0;
            array_push($result[$index], $raw_result);
            unset($raw_result);

            $index++;
            if (isset($this -> value['data'])) {
                $raw_result = $this -> getClansData($this -> value['data']);
            } else {
                $raw_result = "";
            }
            $result[$index] = $raw_result;

            return $result; 
		}

        public function getClansData($data) {
            $result = array(array("clan" => "Безгильдийные", "count" => 0, "heroes" => array()));
            foreach ($data as $key => $value) {
                if ($value['clan_id'] === NULL) {
                    $result[0]['count'] += 1; 
                    array_push($result[0]['heroes'], PrepareToView::createUrl("http://the-tale.org/accounts/".$value['angel_id'],$value['angel_name']));
                } else {
                    $index = intval($value['clan_id']);
                    if (isset($result[$index])) {
                        $result[$index]['count'] += 1; 
                        array_push($result[$index]['heroes'], PrepareToView::createUrl("http://the-tale.org/accounts/".$value['angel_id'],$value['angel_name']));
                    } else {
                        $result[$index] = array("clan" => PrepareToView::createUrl("http://the-tale.org/accounts/clans/".$value['clan_id'],$value['clan_name']), "count" => 1, "heroes" => array(PrepareToView::createUrl("http://the-tale.org/accounts/".$value['angel_id'],$value['angel_name'])));
                    }
                }
            }
            return $result;
        }

		function __destruct() {}
	}
?>