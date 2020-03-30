<!DOCTYPE html>
<html>
<head>
    <title>Влияние гильдии по городам</title>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
</head>

<body>  
<?php
error_reporting(0);

include_once("../config/cfg.php");
include_once("../scripts/models/GetInfoByURLModel.php");
include_once("../scripts/models/PlaceModel.php");
include_once("../scripts/models/MasterModel.php");

function checkPlaces($city_id, $guid) {
    $cur_part = 0;
    foreach ($city_id as $key => $id) {
        $url = "https://the-tale.org/game/places/".$id;
        $curlGet = new GetInfoByURLModel();
        $result = $curlGet -> getInformationNoParcer($url);

        if (empty($result)) {
            break;
        }

        $label = 0;

        $summa = 0;
        do {
            $label = strpos($result,"/clans/".$guid."\"", $label + 1);
            $label = strpos($result,'<td>', $label + 1);
            $label = strpos($result,'>', $label + 5);
            $label_end = strpos($result,'</span>', $label);
            $value_result = substr($result, $label + 1, $label_end - $label - 1);
            $value = intval($value_result);
            if ($value != 0)
                $summa += $value;
        } while ($value != 0);

        $mysqli = $GLOBALS["mysqli"];
        $getInfluence = $mysqli->query("SELECT power_inner FROM places WHERE id = ".$id);
        $all_power = $getInfluence->fetch_assoc();
        $parts = Round($summa*100 / $all_power["power_inner"], 0);
        if ($summa > 0) {
            echo "<b><strong style='color:blue;'>".PlaceModel::getNameById($id)."</strong></b> ";
            echo $summa." / ".$all_power["power_inner"]. " (".$parts."%)<br />";
        } else if ($summa < 0) {
            echo "<b>".PlaceModel::getNameById($id)."</b> ".$summa."<br />";
        }
        $cur_part = $parts;
    }
    return $cur_part;
}

function checkMasters($master_id, $guid) {
    $summaAll = 0;
    foreach ($master_id as $key => $id) {
        $url = "https://the-tale.org/game/persons/".$id;
        $curlGet = new GetInfoByURLModel();
        $result = $curlGet -> getInformationNoParcer($url);

        if (empty($result)) {
            break;
        }

        $label = 0;

        $summa = 0;
        do {
            $label = strpos($result,"/clans/".$guid."\"", $label + 1);
            $label = strpos($result,'<td>', $label + 1);
            $label = strpos($result,'>', $label + 5);
            $label_end = strpos($result,'</span>', $label);
            $value_result = substr($result, $label + 1, $label_end - $label - 1);
            $value = intval($value_result);        
            if ($value != 0)
                $summa += $value;
        } while ($value != 0);

        $mysqli = $GLOBALS["mysqli"];
        $getInfluence = $mysqli->query("SELECT power_inner FROM masters WHERE id = ".$id);
        $all_power = $getInfluence->fetch_assoc();
        $parts = Round($summa*100 / $all_power["power_inner"], 0);
        if ($summa > 0) {
            echo "<b>".MasterModel::getNameById($id)."</b> ";
            echo $summa." / ".$all_power["power_inner"]. " (".$parts."%)<br />";
        } else if ($summa < 0) {
            echo "<b>".MasterModel::getNameById($id)."</b> ".$summa."<br />";
        }
        $summaAll += $summa;
    }
    return $summaAll;
}

if (isset($_REQUEST["guid"])) $guid = $_REQUEST["guid"];
else $guid = 1;

// TODO нет функционала для отсутствия влияния или удаленных гильдий!

if (!isset($_REQUEST["city"])) {
    $city_id = PlaceModel::dbSelectAll();
    if (!$city_id) die();

    checkPlaces($city_id, $guid);
} else {
    $city_id = $_REQUEST["city"];
    $master_id = MasterModel::dbSelectByPlaceId($city_id);

    $part_c = checkPlaces(array($city_id), $guid);
    $part = checkMasters($master_id, $guid);
    $mysqli = $GLOBALS["mysqli"];
    $get_full = $mysqli->query("SELECT SUM(power_inner) FROM masters WHERE power_inner > 0  AND city_id = ".$city_id);
    $full_power = $get_full->fetch_assoc();
    echo "<b><strong style='color:blue;'>Все Мастера</strong></b> ";
    $parts = Round($part*100 / $full_power["SUM(power_inner)"], 0);
    if ($part > 0 && $full_power["SUM(power_inner)"] > 0)
        echo $part." / ".$full_power["SUM(power_inner)"]. " (".$parts."%)<br />";
    else
        echo $part."<br />";
    if ($part_c >= 0 && $parts >= 0) {
        echo "<b><strong style='color:brown;'>Средняя доля</strong></b> ";
        $avg = Round(($part_c + $parts) / 2, 0);
        echo $avg."%<br />";
    }
}

?>
</body>
</html>