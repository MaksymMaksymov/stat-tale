<!DOCTYPE html>
<html>
<head>
    <title>Мероприятия</title>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
</head>

<body>  
<?php
error_reporting(0);

include_once("../config/cfg.php");
include_once("../scripts/models/GetInfoByURLModel.php");
include_once("../scripts/models/EmissaryModel.php");

function setEventList() {
    $events = array();
    $array_deleted_ids = EmissaryModel::dbSelectAllDeleted();

    for ($index = 1; ; $index++) {

        if (!empty($array_deleted_ids) && $index == $array_deleted_ids[0]) {
            array_shift($array_deleted_ids);
            continue;
        }

        $url = "https://the-tale.org/game/emissaries/".$index;
        $curlGet = new GetInfoByURLModel();
        $result = $curlGet -> getInformationNoParcer($url);

        $wrong_id = strpos($result,"Неверный идентификатор эмиссара", 1);

        if (empty($result) || $wrong_id > 0) {
            break;
        }

        $find = strpos($result,"<h3>Мероприятия ", 1);
        $found = strpos($result,"/2", $find + 1);
        $amount = substr($result, $find + 26, $found - $find - 26);
        
        $found = strpos($result,"<tbody>", $found + 1);
        for($i = 0; $i < intval($amount); $i++) {
            $find = strpos($result,"<th>", $found + 1);
            $found = strpos($result,"</th>", $find + 1);
            $event = substr($result, $find + 4, $found - $find - 4);
            if (!isset($events[$event]))
                $events[$event] = 1;
            else 
                $events[$event]++;
        }
    }
    return $events;
}

$event_list = setEventList();

if (isset($event_list)) {
    arsort($event_list);
    echo "<table>";
    echo "<tr><th>Мера</th><th>Кол-во</th></tr>";
    foreach ($event_list as $event => $amount) {
        echo "<tr>";
        echo "<td>".$event."</td>";
        echo "<td>".$amount."</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
</body>
</html>