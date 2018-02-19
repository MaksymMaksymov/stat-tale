<?php
	if (strpos($_SERVER['REQUEST_URI'],"get_data.php") === false)
		include_once("../../config/cfg.php");
	else
		include_once("../config/cfg.php");
	include_once("RegisterUpdates.php");
	include_once("PrepareToView.php");
    include_once("Dictionary.php");

	abstract class GameModel
    {
        private $value;
        //abstract public function setValues($arrayByCurl);
        abstract public function getValues();
    }
?>