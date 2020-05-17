<!DOCTYPE html>
<html>
<head>
    <title>История города</title>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
</head>

<body>  
<?php
error_reporting(0);

include_once("../config/cfg.php");
include_once("../scripts/models/GetInfoByURLModel.php");
include_once("../scripts/models/PlaceModel.php");
include_once("../scripts/models/MasterModel.php");

function convertDate($date) {
	$date = trim($date);
	$date_parts = explode(" ", $date);
	$new_date .= $date_parts[0].".";
	switch ($date_parts[1]) {
		case 'юного':
			$new_date .= "1.";
			break;
		case 'зрелого':
			$new_date .= "2.";
			break;
		case 'взрослого':
			$new_date .= "3.";
			break;
		case 'мудрого':
			$new_date .= "4.";
			break;
		case 'старого':
			$new_date .= "5.";
			break;
		case 'мёртвого':
			$new_date .= "6.";
			break;
		default:
			$new_date .= "0.";
			break;
	}
	switch ($date_parts[3]) {
		case 'сухого':
			$new_date .= "1.";
			break;
		case 'жаркого':
			$new_date .= "2.";
			break;
		case 'сырого':
			$new_date .= "3.";
			break;
		case 'холодного':
			$new_date .= "4.";
			break;
		default:
			$new_date .= "0.";
			break;
	}
	$new_date .= $date_parts[5];
	return $new_date;
}

function checkCity($city_id, $from, $to) {
	$hronology = array();
	for ($page = $from; $page <= $to; $page++) { 
		$url = "https://the-tale.org/game/chronicle/?page=".$page."&place=".$city_id;
		$curlGet = new GetInfoByURLModel();
        $result = $curlGet -> getInformationNoParcer($url);

        if (empty($result)) {
            break;
        }

        $time = 0;
        $text = 0;
        $page_hronology = array();
        do {
            $time = strpos($result,"В реальности:", $text + 1);
            if ($time < $text)
            	break;
            $time = strpos($result,"</div>\">", $time + 1);
            $time_end = strpos($result,"</span>", $time + 8);
            $time_result = substr($result, $time + 8, $time_end - $time - 8);
            $text = strpos($result,"<span>", $time_end + 1);
            $text_end = strpos($result,"</span>", $text + 6);
            $text_result = substr($result, $text + 6, $text_end - $text - 6);
            if (isset($_REQUEST["date"]))
            	$time_result = convertDate($time_result);
            $page_hronology[$time_result] = $text_result;
        } while (true);
        $page_hronology = array_reverse($page_hronology);
        foreach ($page_hronology as $key => $value) {
        	$hronology[$key] = $value;
        }
	}

    return $hronology;
}

if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}

function compareDate($first, $second) {
	$first_parts = explode(".", $first);
	$second_parts = explode(".", $second);
	for ($i = 3; $i >= 0; $i--) { 
		if ($second_parts[$i] > $first_parts[$i]) {
			return $first;
		} else if ($second_parts[$i] < $first_parts[$i]) {
			return $second;
		}
	}
	return $first;
}

$hronology = array();
$last_index = 0;
for ($i = 0; $i < 10; $i++) { 
	if (isset($_REQUEST["page_from".$i])) $page_from = $_REQUEST["page_from".$i];
	else $page_from = 1;
	if (isset($_REQUEST["page_to".$i])) $page_to = $_REQUEST["page_to".$i];
	else $page_to = 1;
	if (isset($_REQUEST["city".$i]) && $_REQUEST["city".$i] != 54 && $_REQUEST["city".$i] < 56 && $_REQUEST["city".$i] > 0) {
	    $hronology[$i] = checkCity($_REQUEST["city".$i], $page_from, $page_to);
	} else {
		$last_index = $i;
		break;
	}
}

if (isset($_REQUEST["date"])) {
	$history = array();
	do {
		$oldest = "1.1.1.10000";
		$oldest_index = $last_index;
		for ($i = 0; $i < $last_index; $i++) {
			if (current($hronology[$i]) != false) {
				if (compareDate($oldest, array_key_first($hronology[$i])) != $oldest) {
					$oldest = array_key_first($hronology[$i]);
					$oldest_index = $i;
				}
			}
		}
		if ($oldest_index != $last_index) {
			$history[array_key_first($hronology[$oldest_index])] = array_shift($hronology[$oldest_index]);
		}

	} while($oldest_index != $last_index);
	echo "[list]<br />";
	foreach ($history as $data => $chronicle) {
		echo "[*][b]".$data."[/b]<br/>";
		echo $chronicle."<br/>";
	}
	echo "[/list]";
}
else {
	for ($i = 0; $i < $last_index; $i++) { 
		if (isset($hronology[$i])) {
			foreach ($hronology[$i] as $data => $chronicle) {
				echo "<b>".$data."</b><br/>";
				echo $chronicle."<br/>";
			}
			echo "<br />==================<br /><br />";
		}
	}
}

?>
</body>
</html>