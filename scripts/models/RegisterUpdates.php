<?php
	if (strpos($_SERVER['REQUEST_URI'],"get_data.php") === false)
        include_once("../../config/cfg.php");
    else
        include_once("../config/cfg.php");

	class RegisterUpdates
    {
    	function __construct() {}

    	public static function dbUpdateValues($table_name) {
    		if (RegisterUpdates::dbSelectById($table_name)) {
                return RegisterUpdates::dbUpdate($table_name);
            } else 
                return RegisterUpdates::dbInsert($table_name);
    	}

    	private static function dbSelectById($table_name)
        {
            $mysqli = $GLOBALS["mysqli"];
            $result = $mysqli->query("SELECT * FROM table_updates WHERE table_name='".$table_name."'");
            $row = $result->fetch_assoc();
            if (isset($row)) {
                return true;
            } else
                return false;
        }

        private static function dbInsert($table_name) {
        	$mysqli = $GLOBALS["mysqli"];
        	return $mysqli->query("INSERT INTO table_updates(table_name,updated_at) VALUES (N'".$table_name."',N'".date('Y-m-d H:i:s')."')");
        }

        private static function dbUpdate($table_name) {
        	$mysqli = $GLOBALS["mysqli"];
        	return $mysqli->query("UPDATE table_updates SET updated_at=N'".date('Y-m-d H:i:s')."'". "WHERE table_name='".$table_name."'");
        }

    	function __destruct() {}
    }
?>