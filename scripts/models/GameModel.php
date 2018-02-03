<?php
    include_once("Dictionary.php");

	abstract class GameModel
    {
        private $value;
        abstract public function setValues($arrayByCurl);
        abstract public function getValues();
    }
?>