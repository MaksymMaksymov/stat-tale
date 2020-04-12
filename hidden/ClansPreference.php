<!DOCTYPE html>
<html>
<head>
    <title>Предпочтение гильдии по городам</title>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
</head>

<body>  
<?php
error_reporting(0);

include_once("../config/cfg.php");
include_once("../scripts/models/GetInfoByURLModel.php");
include_once("../scripts/models/PlaceModel.php");
include_once("../scripts/models/MasterModel.php");
include_once("../scripts/models/HeroModel.php");

$guid = (isset($_REQUEST["guid"])) ? " AND clan_id =".$_REQUEST["guid"] : "";

$mysqli = $GLOBALS["mysqli"];
$result = $mysqli->query("SELECT id FROM heroes WHERE level > 1 AND can_affect_game = 1".$guid." ORDER BY clan_id");

$ids = array();
while ($row = $result->fetch_assoc()) {
    array_push($ids, $row['id']);
}

$places_pref = array();
$masters = array();
foreach ($ids as $key => $id) {
    $url = "https://the-tale.org/game/heroes/".$id."#hero-tab-main=attributes";
    $curlGet = new GetInfoByURLModel();
    $result = $curlGet -> getInformationNoParcer($url);

    if (empty($result)) {
        break;
    }

    $home = strpos($result,"<strong>родной город</strong>", 0);
    if($home) {
        $home = strpos($result,"/game/places/", $home);
        $home += 12;
        $home_end = strpos($result,"\"", $home);
        $value_result = substr($result, $home + 1,  $home_end - $home - 1);
        $value = intval($value_result);
        if(isset($places_pref[$value]))
            $places_pref[$value] .= ",".$id;
        else 
            $places_pref[$value] = $id;
    }

    $camrade = strpos($result,"<strong>соратник</strong>", 0);
    $enemy = strpos($result,"<strong>противник</strong>", 0);
    $camrade_start = strpos($result,"/persons/", $camrade);
    if($camrade_start && $enemy > $camrade_start) {
        $camrade_start += 8;
        $camrade_end = strpos($result,"\"", $camrade_start);
        $value_result = substr($result, $camrade_start + 1,  $camrade_end - $camrade_start - 1);
        $value = intval($value_result);
        if(isset($masters[$value][0]))
            $masters[$value][0] .= ",".$id;
        else 
            $masters[$value][0] = $id;
    } 
    $enemy_start = strpos($result,"/persons/", $enemy);
    if($enemy_start) {
        $enemy_start += 8;
        $enemy_end = strpos($result,"\"", $enemy_start);
        $value_result = substr($result, $enemy_start + 1,  $enemy_end - $enemy_start - 1);
        $value = intval($value_result); 
        if(isset($masters[$value][1]))
            $masters[$value][1] .= ",".$id;
        else 
            $masters[$value][1] = $id;
    }          
}

$frontier = (isset($_REQUEST["frontier"])) ? ($_REQUEST["frontier"] == 0) ? " WHERE frontier = 0 " : "" : "";
$result = $mysqli->query("SELECT id, name FROM places ".$frontier." ORDER BY name");
$places = array();
while ($row = $result->fetch_assoc()) {
    array_push($places, $row['id']);
}

foreach ($places as $key => $place_id) {
    echo "<b>".PlaceModel::getNameById($place_id)."</b><br />";

    if(isset($places_pref[$place_id])) {
        echo "<strong style='color:blue;'>родной город:</strong> <ul>";
        $hero_ids = explode(",", $places_pref[$place_id]);
        foreach ($hero_ids as $hkey => $hid) {
            $hresult = $mysqli->query("SELECT id,name, clan_name FROM heroes WHERE id=".$hid);
            $hrow = $hresult->fetch_assoc();
            echo "<li><b>".$hrow['clan_name']."</b> <a href='https://the-tale.org/game/heroes/".$hrow['id']."'>".$hrow['name']."</a></li>";
        }
        echo "</ul>";
    }

    $result = $mysqli->query("SELECT id, name FROM masters WHERE city_id = ".$place_id." ORDER BY name");
    //MasterModel::dbSelectByPlaceId($place_id);
    while ($row = $result->fetch_assoc()) {
        if(isset($masters[$row['id']])) {
            if(isset($masters[$row['id']][0])) {
                echo "<i>".$row['name']."</i> соратники: <ul>";
                $hero_ids = explode(",", $masters[$row['id']][0]);
                foreach ($hero_ids as $hkey => $hid) {
                    $hresult = $mysqli->query("SELECT id,name, clan_name FROM heroes WHERE id=".$hid);
                    $hrow = $hresult->fetch_assoc();
                    echo "<li><b>".$hrow['clan_name']."</b> <a href='https://the-tale.org/game/heroes/".$hrow['id']."'>".$hrow['name']."</a></li>";
                }
                echo "</ul>";
            }
            if(isset($masters[$row['id']][1])) {
                echo "<i>".$row['name']."</i> <strong style='color:red;'>противники:</strong> <ul>";
                $hero_ids = explode(",", $masters[$row['id']][1]);
                foreach ($hero_ids as $hkey => $hid) {
                    $hresult = $mysqli->query("SELECT id, name, clan_name FROM heroes WHERE id=".$hid);
                    $hrow = $hresult->fetch_assoc();
                    echo "<li><b>".$hrow['clan_name']."</b> <a href='https://the-tale.org/game/heroes/".$hrow['id']."'>".$hrow['name']."</a></li>";
                }
                echo "</ul>";
            }
        }
    }
    echo "<br />";
}

?>
</body>
</html>