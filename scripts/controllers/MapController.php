<?php
    include_once("../models/GetInfoByURLModel.php");
    include_once("../models/PlaceModel.php");

	class MapController {
        public $template_data;
        private $neighbours;

        function __construct() {}

        public function main() {         
            $curlGet = new GetInfoByURLModel();
            $result = $curlGet -> getInformation("https://the-tale.org/game/map/api/region?api_client=map-v0.4&api_version=0.1");
            // $trade_economy = PlaceModel::dbSelectAllTradesAndEcomomy();
            // $result["data"]["region"]["trade_economy"] = $trade_economy;
            $this -> template_data = $result["data"]["region"];
            if ((isset($_REQUEST['mode'])) && $_REQUEST['mode'] == "extended") {
                $this -> checkCellNeigbours();
                $this -> convertToExtended();
                $this -> template_data["width"] *= 3;
                $this -> template_data["height"] *= 3;
            }
        }

        public function checkCellNeigbours() {
            foreach($this -> template_data["draw_info"] as $x => $row) {
                foreach($row as $y => $cell) {
                    $this -> neighbours[$x][$y] = 0;
                    foreach($cell as $index => $sprite) {
                        if ($sprite[0] >= 15 && $sprite[0] <= 43) {
                            for ($i = -1; $i < 2; $i++) {
                                if ($x + $i < 0 || $x + $i >= $this -> template_data["width"]) {
                                    continue;
                                } 
                                for ($j = -1; $j < 2; $j++) {
                                    if ($y + $j < 0 || $y + $j >= $this -> template_data["height"]) {
                                        continue;
                                    }
                                    if ($this -> template_data["draw_info"][$x + $i][$y + $j][0][0] == $sprite[0]) {
                                        $this -> neighbours[$x][$y]++;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        public function convertToExtended() {
            $newData = array();
            foreach($this -> template_data["draw_info"] as $x => $row) {
                foreach($row as $y => $cell) {
                    for ($i = 0; $i < 3; $i++) {
                        for ($j = 0; $j < 3; $j++) {
                            $newData[$x*3 + $i][$y*3 + $j] = array();
                            foreach($cell as $index => $sprite) {
                                switch($sprite[0]) {
                                    case 15: // terrain
                                    case 16:
                                    case 17:
                                    case 18:
                                    case 19:
                                    case 20:
                                    case 21:
                                    case 22:
                                    case 23:
                                    case 24:
                                    case 25:
                                    case 26:
                                    case 27:
                                    case 28:
                                    case 29:
                                    case 30:
                                    case 31:
                                    case 32:
                                    case 33:
                                    case 34:
                                    case 35:
                                    case 36:
                                    case 37:
                                    case 38:
                                    case 39:
                                    case 40:
                                    case 41:
                                    case 42:
                                    case 43:
                                        if ($i == 1 && $j == 1) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], $sprite);
                                        } else {
                                            if ($x + $i - 2 < 0 || $x + $i - 2 >= $this -> template_data["width"] 
                                                || $y + $j - 2 < 0 || $y + $j - 2 >= $this -> template_data["height"]
                                                || $this -> template_data["draw_info"][$x + $i - 2][$y + $j - 2][0] == $sprite) {
                                                    array_push($newData[$x*3 + $i][$y*3 + $j], $sprite);
                                            } else if ($this -> neighbours[$x][$y] >= $this -> neighbours[$x + $i - 2][$y + $j - 2]) {
                                                array_push($newData[$x*3 + $i][$y*3 + $j], $sprite);
                                            } else {
                                                array_push($newData[$x*3 + $i][$y*3 + $j], $this -> template_data["draw_info"][$x + $i - 2][$y + $j - 2][0]);
                                            }
                                        }
                                        break;
                                    case 44: // cities
                                    case 45:
                                    case 46:
                                    case 47:
                                    case 48:
                                    case 49:
                                    case 50:
                                    case 51:
                                    case 52:
                                    case 53:
                                    case 54:
                                    case 55:
                                    case 56:
                                    case 57:
                                    case 58:
                                    case 59:
                                    case 60:
                                    case 61:
                                    case 62:
                                    case 63:
                                        if ($i == 1 && $j == 1) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], $sprite);
                                        }
                                        break;
                                    case 64:
                                        if ($i == 1 && $j == 1) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], $sprite);
                                        } else if ($j == 1 && $i % 2 == 0) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], array(66, 0));
                                        } else if ($i == 1 && $j % 2 == 0) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], array(67, 0));
                                        } 
                                    case 65:
                                        if ($i == 1 && $j == 1) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], $sprite);
                                        } else if ($j == 1 && ($sprite[1]/90 != 2 && $i == 0 || $sprite[1]/90 != 0 && $i == 2)) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], array(66, 0));
                                        } else if ($i == 1 && ($sprite[1]/90 != 1 && $j == 0 || $sprite[1]/90 != 3 && $j == 2)) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], array(67, 0));
                                        }
                                        break; 
                                    case 66:
                                        if ($j == 1) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], $sprite);
                                        }
                                        break;
                                    case 67:
                                        if ($i == 1) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], $sprite);
                                        }
                                        break;
                                    case 68:
                                        if ($i == 1 && $j == 1) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], $sprite);
                                        } else if ($j == 1 && ($sprite[1]/90 < 2 && $i == 0 || $sprite[1]/90 >= 2 && $i == 2)) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], array(66, 0));
                                        } else if ($i == 1 && ($sprite[1]/90 % 3 == 0 && $j == 0 || $sprite[1]/90 % 3 != 0 && $j == 2)) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], array(67, 0));
                                        }
                                        break;
                                    case 69:
                                        if ($i == 1 && $j == 1) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], $sprite);
                                        } else if ($j == 1 && ($i == 0 && $sprite[1] == 270 || $i == 2 && $sprite[1] == 90)) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], array(66, 0));
                                        } else if ($i == 1 && ($j == 0 && $sprite[1] == 180 || $j == 2 && $sprite[1] == 0)) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], array(67, 0));
                                        }
                                        break;
                                    case 72: // buildings
                                    case 73:
                                    case 74:
                                    case 75:
                                    case 76:
                                    case 77:
                                    case 78:
                                    case 79:
                                    case 80:
                                    case 81:
                                    case 82:
                                    case 83:
                                    case 84:
                                    case 85:
                                    case 86:
                                    case 87:
                                    case 88:
                                    case 89:
                                    case 90:
                                    case 91:
                                    case 92:
                                    case 93:
                                        if ($i == 1 && $j == 1) {
                                            array_push($newData[$x*3 + $i][$y*3 + $j], $sprite);
                                        }
                                        break;
                                    default:
                                        array_push($newData[$x*3 + $i][$y*3 + $j], $sprite);
                                }
                            }
                        }
                    }
                }
            }
            $this -> template_data["draw_info"] = $newData;
        }

        function __destruct() {}
    }
    
    $map = new MapController();
    $map -> main();
    $sprite = (isset($_REQUEST['sprite'])) ? $_REQUEST['sprite'] : "map";
    $mode = (isset($_REQUEST['mode'])) ? $_REQUEST['mode'] : "default";

    include "../views/MapView.php";
?>