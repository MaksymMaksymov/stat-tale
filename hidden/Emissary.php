<!DOCTYPE html>
<html>
<head>
    <title>Эмиссары</title>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
</head>

<body>  
<?php
error_reporting(0);

include_once("../config/cfg.php");
include_once("../scripts/models/GetInfoByURLModel.php");

function setAllEmissaries() {
    $ids = array();

    for ($index = 1; ; $index++) {
        $url = "https://the-tale.org/game/emissaries/".$index;
        $curlGet = new GetInfoByURLModel();
        $result = $curlGet -> getInformationNoParcer($url);

        $wrong_id = strpos($result,"Неверный идентификатор эмиссара", 1);

        if (empty($result) || $wrong_id > 0) {
            break;
        }

        $emissary["id"] = $index;
        $find = strpos($result,"<small>", 1);
        $found = strpos($result,"</small>", $find + 1);
        $emissary["race"] = substr($result, $find + 12, $found - $find - 12);
        $find = $found + 8;
        $found = strpos($result,"<i", $find + 1);
        $emissary["name"] = substr($result, $find + 1, $found - $find - 1);
        $find = strpos($result,"/clans/", $found + 1);
        $found = strpos($result,"\"", $find + 1);
        $emissary["clan_id"] = substr($result, $find + 7, $found - $find - 7);
        $find = strpos($result,"[", $found + 1);
        $found = strpos($result,"]", $find + 1);
        $emissary["clan_name"] = substr($result, $find, $found + 1 - $find);

        $find = strpos($result,"Статус", $found + 1);
        $find = strpos($result,"\">", $find + 1);
        $found = strpos($result,"</", $find + 1);
        $emissary["status"] = substr($result, $find + 2, $found - $find - 2);
        $find = strpos($result,"Здоровье", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"/", $find + 1);
        $emissary["health"] = substr($result, $find + 4, $found - $find - 4);
        $find = strpos($result,"Город", $found + 1);
        $find = strpos($result,"\">", $find + 1);
        $found = strpos($result,"</", $find + 1);
        $emissary["city"] = substr($result, $find + 2, $found - $find - 2);
        $find = strpos($result,"Влияние", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"(", $find + 1);
        $emissary["power"] = substr($result, $find + 4, $found - $find - 4);
        $find = strpos($result,"Способности", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"<i", $find + 1);
        $emissary["ability"] = substr($result, $find + 4, $found - $find - 4);

        $find = strpos($result,"военное дело", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"/", $find + 1);
        $emissary["warfare"] = substr($result, $find + 4, $found - $find - 4);
        $find = strpos($result,"культурология", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"/", $find + 1);
        $emissary["cultural_science"] = substr($result, $find + 4, $found - $find - 4);
        $find = strpos($result,"политология", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"/", $find + 1);
        $emissary["politival_science"] = substr($result, $find + 4, $found - $find - 4);
        $find = strpos($result,"религиоведение", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"/", $find + 1);
        $emissary["religious_studies"] = substr($result, $find + 4, $found - $find - 4);
        $find = strpos($result,"социология", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"/", $find + 1);
        $emissary["sociology"] = substr($result, $find + 4, $found - $find - 4);
        $find = strpos($result,"тайные операции", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"/", $find + 1);
        $emissary["covert_operations"] = substr($result, $find + 4, $found - $find - 4);
        $find = strpos($result,"технологии", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"/", $find + 1);
        $emissary["technologies"] = substr($result, $find + 4, $found - $find - 4);
        $find = strpos($result,"экономика", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"/", $find + 1);
        $emissary["economy"] = substr($result, $find + 4, $found - $find - 4);

        array_push($ids, $emissary);
    }
    return $ids;
}

$emissaries = setAllEmissaries();

if (isset($emissaries)) {
    echo "<table>";
    echo "<tr><th>Ги</th><th>Раса</th><th>Имя</th><th>Статус</th><th>Здоровье</th><th>Город</th><th>Влияние</th><th>Способности</th>";
    echo "<th>B</th><th>K</th><th>П</th><th>Р</th><th>С</th><th>Та</th><th>Те</th><th>Э</th></tr>";
    foreach ($emissaries as $key => $emissary) {
        echo "<tr>";
        echo "<td><a href='https://the-tale.org/clans/".$emissary["clan_id"]."'><b>".$emissary["clan_name"]."</b></a></td>";
        echo "<td>".$emissary["race"]."</td>";
        echo "<td><a href='https://the-tale.org/game/emissaries/".$emissary["id"]."'>".$emissary["name"]."</td>";
        echo "<td>".$emissary["status"]."</td>";
        echo "<td>".$emissary["health"]."</td>";
        echo "<td>".$emissary["city"]."</td>";
        echo "<td>".$emissary["power"]."</td>";
        echo "<td>".$emissary["ability"]."</td>";
        echo "<td>".$emissary["warfare"]."</td>";
        echo "<td>".$emissary["cultural_science"]."</td>";
        echo "<td>".$emissary["politival_science"]."</td>";
        echo "<td>".$emissary["religious_studies"]."</td>";
        echo "<td>".$emissary["sociology"]."</td>";
        echo "<td>".$emissary["covert_operations"]."</td>";
        echo "<td>".$emissary["technologies"]."</td>";
        echo "<td>".$emissary["economy"]."</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
</body>
</html>