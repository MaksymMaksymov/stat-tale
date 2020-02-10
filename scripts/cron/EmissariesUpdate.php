<?php
	include_once("../../config/cfg.php");
	include_once("../models/GetInfoByURLModel.php");
	include_once("../models/EmissaryModel.php");

    for ($index = 1; ; $index++) {
        $url = "https://the-tale.org/game/emissaries/".$index;
        $curlGet = new GetInfoByURLModel();
        $result = $curlGet -> getInformationNoParcer($url);

        $wrong_id = strpos($result,"Неверный идентификатор эмиссара", 1);

        if (empty($result) || $wrong_id > 0) {
            break;
        }

        $update = new EmissaryModel();

        $emissary["id"] = $index;
        $find = strpos($result,"<small>", 1);
        $found = strpos($result,"</small>", $find + 1);
        $tmp = substr($result, $find + 12, $found - $find - 12);
        $race_and_gender = Dictionary::getRaceAndGender($tmp);
        $emissary["race"] = $race_and_gender["race"];
        $emissary["gender"] = $race_and_gender["gender"];
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
        $find = strpos($result,"/game/places/", $found + 1);
        $found = strpos($result,"\"", $find + 1);
        $emissary["city_id"] = substr($result, $find + 13, $found - $find - 13);
        $find = strpos($result,"Влияние", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"(", $find + 1);
        $emissary["power"] = substr($result, $find + 4, $found - $find - 4);
        $find = strpos($result,"Способности", $found + 1);
        $find = strpos($result,"<td>", $find + 1);
        $found = strpos($result,"<i", $find + 1);
        $emissary["ability"] = substr($result, $find + 4, $found - $find - 4);
        if (strpos($emissary["ability"],",", 0)) {
            $emissary["ability"] = substr($emissary["ability"], 0, strpos($emissary["ability"],",", 0));
            var_dump($emissary["ability"]);
        }

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

        $result = $update -> dbUpdateValues($emissary);
    }
?>