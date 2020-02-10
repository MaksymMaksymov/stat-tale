<?php
	include_once("../../config/cfg.php");
	include_once("RegisterUpdates.php");
	include_once("PrepareToView.php");
    include_once("Dictionary.php");

	abstract class GameModel
    {
        private $value;
        abstract public function setValue($id);
        abstract public static function dbSelectAll();
        abstract public function dbUpdateValues($arrayByCurl);
        abstract public function dbSelectById();
        abstract public function dbInsert($arrayData); 
        abstract public function dbUpdate($arrayData);
        abstract public function getValues();
    }
?>