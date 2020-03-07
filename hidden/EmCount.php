<!DOCTYPE html>
<html>
<head>
    <title>Количество эмиссаров у гильдий</title>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
</head>

<body>  
<?php
	error_reporting(0);

	include_once("../config/cfg.php");
	
	$mysqli = $GLOBALS["mysqli"];
    $result = $mysqli->query("
    	SELECT COUNT(clan_id),clan_name
		FROM emissaries
		WHERE status LIKE N'%Работает%' 
		GROUP BY clan_id 
		ORDER BY COUNT(clan_id) DESC, clan_id");
	echo "<table>";
    echo "<tr><th>Кол-во</th><th>Ги</th>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr><td>".$row["COUNT(clan_id)"]."</td><td>".$row["clan_name"]."</td><tr>";
    }
    echo "</table>";
?>
</body>
</html>