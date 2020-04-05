<?php
    include_once("Controller.php");
	include_once("../models/PlaceModel.php");
    include_once("../models/CalculatorModel.php");

	class CalculatorController extends Controller  {
        public $model_array;
        public $prodution_adds;
        public $correction;

        function __construct() {}
           
        public function getArrayToParse($array_of_ids = null) {
            $this -> model_array = array();
            $this -> prodution_adds = array();
            $this -> correction = array();

            $professions = CalculatorModel::dbSelectAllProfessionIds();

            $current = 0;
            if (isset($_POST["size"])) {
                $current = $_POST["size"];
            }
            $this -> prodution_adds[0] = array();
            array_push($this -> prodution_adds[0], CalculatorModel::setSelectToTen("size", $current, "Выберите размер города"));
            $current = 0;
            if (isset($_POST["economy"])) {
                $current = $_POST["economy"];
            }
            $this -> prodution_adds[1] = array();
            array_push($this -> prodution_adds[1], CalculatorModel::setSelectToTen("economy", $current, "Выберите экономику"));
            $current = 0;
            if (isset($_POST["trade"])) {
                $current = $_POST["trade"];
            }
            $this -> prodution_adds[2] = array();
            array_push($this -> prodution_adds[2], CalculatorModel::setSelectToTen("trade", $current, "Выберите торговлю"));
            $current = 1;
            if (isset($_POST["area"])) {
                $current = $_POST["area"];
            }
            $this -> prodution_adds[3] = array();
            array_push($this -> prodution_adds[3], CalculatorModel::setSelectArea($current, "Определите размер владений (жми Enter)"));
            $current = 0;
            if (isset($_POST["roads"])) {
                $current = $_POST["roads"];
            }
            $this -> prodution_adds[4] = array();
            array_push($this -> prodution_adds[4], CalculatorModel::setSelectRoads($current, "Определите количество подконтрольных дорог(жми Enter)"));
            $current = 0;
            if (isset($_POST["building"])) {
                $current = $_POST["building"];
            }
            $this -> prodution_adds[5] = array();
            array_push($this -> prodution_adds[5], CalculatorModel::setSelectBuilding($current, "Определите количество строений"));
            $current = 0;
            if (isset($_POST["caravan"])) {
                $current = $_POST["caravan"];
            }
            $this -> prodution_adds[6] = array();
            array_push($this -> prodution_adds[6], CalculatorModel::setSelectCaravan($current, "Определите дальность караванов(жми Enter)"));

            $current = 0;
            if (isset($_POST["production"])) {
                $current = $_POST["production"];
            }
            $this -> correction[0] = array();
            array_push($this -> correction[0], CalculatorModel::setSelectCorrection("production", $current, "Корректировка продукции(жми Enter)"));
            $current = 0;
            if (isset($_POST["stability"])) {
                $current = $_POST["stability"];
            }
            $this -> correction[1] = array();
            array_push($this -> correction[1], CalculatorModel::setSelectCorrection("stability", $current, "Корректировка стабильности(жми Enter)"));

            $empty_calc = true;
            for ($i = 1; $i <= 6; $i++) { 
                $current = -1;
                if (isset($_POST["profession".$i])) {
                    $current = $_POST["profession".$i];
                }
                $model = new CalculatorModel();
                if (!$model -> setValue($i, $professions, $current)) continue;
                if ($current != -1) {
                    $empty_calc = false;
                }
                $current = -1;
                if (isset($_POST["master".$i])) {
                    $current = $_POST["master".$i];
                }
                if (!$model -> setValueMaster($current)) continue;
                array_push($this -> model_array, $model);
            }

            if (!$empty_calc) {
                $current = -1;
                if (isset($_POST["spec"])) {
                    $current = $_POST["spec"];
                }
                $model = new CalculatorModel();
                $model -> setValueSpec(7, $current);
                if ($current != -1 && isset($this -> prodution_adds[0]) && isset($this -> prodution_adds[0][0]["selected"])) {
                    $spec_profs = array();
                    for ($i = 1; $i <= 6; $i++) {
                        if (isset($this -> model_array[$i - 1]) && isset($this -> model_array[$i - 1] -> value["profession"][$model -> value["current"]])) {
                            array_push($spec_profs, $this -> model_array[$i - 1] -> value["profession"][$model -> value["current"]]);
                        }
                    }
                    $cur_size = $this -> prodution_adds[0][0]["selected"] + 1;
                    $spec_result = CalculatorModel::setAutoInfluence($cur_size, $spec_profs);
                    $model -> value["spec_points"] = 0;
                    for ($i = 1; $i <= 6; $i++) {
                        if (isset($this -> model_array[$i - 1]) && isset($this -> model_array[$i - 1] -> value["profession"][$model -> value["current"]]) && $this -> model_array[$i - 1] -> value["profession"][$model -> value["current"]] == $spec_result[$i - 1]["value"]) {
                            $this -> model_array[$i - 1] -> value["profession"]["influence"] = $spec_result[$i - 1]["influence"];
                            $model -> value["spec_points"] += $spec_result[$i - 1]["influence"] * $spec_result[$i - 1]["value"] / 100;
                        }
                    }
                    $model -> value["spec_points"] *= Dictionary::getSizeCoef($cur_size);
                }
                array_push($this -> model_array, $model);
                unset($model);

                $model = new CalculatorModel();
                $model -> setValueDefault(8);
                for ($i = 1; $i <= 6; $i++) {
                    if (isset($this -> model_array[$i - 1]) && $this -> model_array[$i - 1] -> value["id"] == $i && isset($this -> model_array[$i - 1] -> value["profession"]))
                    $model -> setValueSum($this -> model_array[$i - 1] -> value["profession"]);
                }
                if (isset($this -> model_array[6]) && $this -> model_array[6] -> value["id"] == $i && isset($this -> model_array[6] -> value["profession"]))
                    $model -> setValueSum($this -> model_array[6] -> value["profession"]);
                $model -> setSize((isset($_POST["size"])) ? $_POST["size"] + 1 : 1);
                $model -> setTradeOrEconomy((isset($_POST["economy"])) ? $_POST["economy"] + 1 : 1);
                $model -> setTradeOrEconomy((isset($_POST["trade"])) ? $_POST["trade"] + 1 : 1);
                $model -> setArea((isset($_POST["area"])) ? $_POST["area"] : 1);
                $model -> setRoads((isset($_POST["roads"])) ? $_POST["roads"] : 0);
                $model -> setBuilding((isset($_POST["building"])) ? $_POST["building"] : 0);
                $model -> setCaravan((isset($_POST["caravan"])) ? $_POST["caravan"] : 0);
                if ((isset($_POST["production"])) && (isset($_POST["stability"])))
                    $model -> setCoreectionValues($_POST["production"],$_POST["stability"]);

                $model -> setValueStabilityCorrection();
                array_push($this -> model_array, $model);
            }
        }

        public function sortByClass($class, $direction = "false") {}

        function __destruct() {}
    }
    
    $places = CalculatorModel::setSelectPlaces();
    if (isset($_POST["place"]) && $_POST["place"] != 0) {
        $cur_place_id = $_POST["place"];
        $cur_place = PlaceModel::getNameById($cur_place_id);
        $place = PlaceModel::getPlaceById($cur_place_id);
        $_POST["size"] = $place["size"] - 1;
        $_POST["economy"] = $place["economy"] - 1;
        $_POST["trade"] = $place["trade"] - 1;
        $_POST["area"] = $place["area"];
        $_POST["frontier"] = ($place["frontier"]) ? true : null;
        $_POST["spec"] = Dictionary::getSpecId($place["specialization"]);
        $master_post = CalculatorModel::setMastersByPlaceId($cur_place_id);
        if (isset($master_post)) {
            for ($i = 1; $i <= 6; $i++) {
                if (isset($master_post[$i-1])) {
                    $_POST["master".$i] = $master_post[$i-1]["master"];
                    $_POST["profession".$i] = $master_post[$i-1]["profession"];
                } else {
                    $_POST["master".$i] = null;
                    $_POST["profession".$i] = null;
                }
            }
            $_POST["building"] = $master_post["building"];
        }
    } else if (isset($_POST["cur_place"])) {
        $cur_place_id = $_POST["cur_place"];
        $cur_place = PlaceModel::getNameById($cur_place_id);
    }

    $arr_ids = null;
    $get_info = new CalculatorController();
    $get_info -> main($arr_ids);

    include "../views/CalculatorView.php";
?>