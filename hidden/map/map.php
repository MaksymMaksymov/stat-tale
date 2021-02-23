<!DOCTYPE html>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title>map preview</title>
   </head>
   <body>
   	  <div id="map-data" style="display: none;"><?php
   	  	include_once("../../scripts/models/GetInfoByURLModel.php");
        include_once("../../scripts/models/PlaceModel.php");
   	  	$url = "https://the-tale.org/game/map/api/region?api_client=map-v0.4&api_version=0.1";
        $curlGet = new GetInfoByURLModel();
        $result = $curlGet -> getInformation($url);
        $trade_economy = PlaceModel::dbSelectAllTradesAndEcomomy();
        $result["data"]["region"]["trade_economy"] = $trade_economy;
        echo json_encode($result["data"]["region"]);
   	  ?>
   	  </div>
      <canvas id="map" width="2240" height="2240"></canvas>
      <script src="js/sprites.js"></script>
      <script src="js/draw-map.js"></script>
   </body>
</html>