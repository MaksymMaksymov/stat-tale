<?php
	include_once("../config/cfg.php");
	abstract class Controller {
        abstract public function GetArrayToParse($array_of_ids = null);
    }
?>