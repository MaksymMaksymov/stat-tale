<?php
	include_once("../../config/cfg.php");
	include_once("../models/GetInfoByURLModel.php");

    class RatingModel {

        function __construct() {}

        private $ids;
         
        public function setPoweredIds() {
            $this -> ids = array();

            for ($index = 1; ; $index++) {
                $url = $GLOBALS["RATE_POWER"]."?page=".$index;
                $curlGet = new GetInfoByURLModel();
                $result = $curlGet -> getInformationNoParcer($url);

                if (empty($result)) {
                    break;
                }

                $accounts = 0;
                do {
                    $accounts = strpos($result,"/game/heroes/", $accounts + 1);
                    $accounts_end = strpos($result,'"', $accounts + 13);
                    $id_result = substr($result, $accounts + 13, $accounts_end - $accounts - 13);
                    $id = intval($id_result);
                    if ($id != 0)
                        array_push($this -> ids, $id);
                } while ($id != 0);
            }
            return $this -> dbUpdate();
        } 

        public function dbUpdate() {
            if (!isset($this -> ids) || $this -> ids == null) return false;

            $db_values = "";
            foreach ($this -> ids as $key => $id) {
                if (!empty($db_values))
                    $db_values .= ",";
                $db_values .= "(".$id.")";
            }

            $mysqli = $GLOBALS["mysqli"];
            $mysqli->query("DELETE FROM powered_ids");
            $query = $mysqli->query("INSERT INTO powered_ids(id) VALUES ".$db_values);

            return $query;
        }

        public function dbSelectAll() {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT id FROM powered_ids");
            
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

        function __destruct() {}     
    }
?>
