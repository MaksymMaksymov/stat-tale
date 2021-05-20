<?php
    include_once("../models/GetInfoByURLModel.php");
    include_once("../models/PlaceModel.php");
    include_once("../models/EmissaryModel.php");

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
                $this -> addExtraLore();
                $this -> smoothBiomCells();
                $this -> template_data["width"] *= 3;
                $this -> template_data["height"] *= 3;
            }
        }

        private function checkCellNeigbours() {
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

        private function convertToExtended() {
            $newData = array();
            foreach($this -> template_data["draw_info"] as $x => $row) {
                foreach($row as $y => $cell) {
                    foreach($cell as $index => $sprite) {
                        if ($sprite[0] >= 15 && $sprite[0] <= 43) {
                            $this -> convertTerrain($newData, $x, $y, $sprite);
                        } else if($sprite[0] >= 44 && $sprite[0] <= 63) {
                            $this -> convertCity($newData, $x, $y, $sprite);
                        } else if ($sprite[0] >= 64 && $sprite[0] <= 69) {
                            $this -> convertRoad($newData, $x, $y, $sprite);
                        } else if ($sprite[0] >= 72 && $sprite[0] <= 93) {
                            $this -> convertBuilding($newData, $x, $y, $sprite);
                        }
                    }
                }
            }
            $this -> template_data["draw_info"] = $newData;
        }

        private function addExtraLore() { // hard-code! :D
            /*$pathFromUJtoSL = "";
            foreach($this -> template_data["roads"] as $x => $road) {
                if ($road["point_1_id"] == 3 && $road["point_2_id"] == 39) {
                    $pathFromUJtoSL = $road["path"];
                    break;
                }
            }*/
            include_once("map/Rails.php");
            if (isset($railsPath)) {
                foreach(json_decode($railsPath) as $id => $road) {
                    $this -> drawRailPath($this -> convertPathToExt($road->path), $road->y, $road->x);
                }
            }
        }

        private function convertPathToExt($path) {
            $result = $path[0].$path[0];
            for($i = 1; $i < strlen($path); $i++) {
                for($j = 0; $j < 3; $j++) {
                    $result.=$path[$i];
                }
            }
            return $result;
        }
        
        private function drawRailPath($path, $x, $y) {
            // var_dump($x.",".$y.",".$path);
            $x = $x*3 + 1;
            $y = $y*3 + 1;
            $this -> moveRoadByTemplate($path[0], $x, $y);
            $this -> replaceRoadToNew($x, $y, array(235, $this -> rotateEndByTemplate($path[0])));
            $this -> moveRoadByTemplate($path[0], $x, $y);
            for($i = 1; $i < strlen($path) - 1; $i++) {
                $this -> replaceRoadToNew($x, $y, $this -> getSpriteByTemplate($path[$i-1].$path[$i]));
                $this -> moveRoadByTemplate($path[$i], $x, $y);
            }
            $this -> replaceRoadToNew($x, $y, array(235, $this -> rotateEndByTemplate($path[strlen($path) - 1]) - 180));
        }

        private function moveRoadByTemplate($template, &$x, &$y) {
            switch($template) {
                case "r":
                    $y++;
                    break;
                case "l":
                    $y--;
                    break;
                case "u":
                    $x--;
                    break;
                case "d":
                    $x++;
                    break;
            }
        }

        private function rotateEndByTemplate($template) {
            switch($template) {
                case "r":
                    return 0;
                case "l":
                    return 180;
                case "u":
                    return 270;
                case "d":
                    return 90;
                default:
                    return 0;
            }
        }

        private function getSpriteByTemplate($template) {
            switch($template) {
                case "rr":
                case "ll":
                    return array(233, 0);
                case "uu":
                case "dd":
                    return array(233, 90);
                case "ur":
                case "ld":
                    return array(234, 180);
                case "rd":
                case "ul":
                    return array(234, 270);
                case "ru":
                case "dl":
                    return array(234, 0);
                case "dr":
                case "lu":
                    return array(234, 90);
                default:
                    return array(234, 0);
            }
        }

        private function replaceRoadToNew($x, $y, $sprite) {
            if ($sprite[0] == 233 && $sprite[1] == 180) {
                var_dump($this -> template_data["draw_info"][$x][$y][1][0]);
            } 
            if (isset($this -> template_data["draw_info"][$x][$y][1])) {
                if ($sprite[0] == $this -> template_data["draw_info"][$x][$y][1][0] + 166 && $sprite[1] == $this -> template_data["draw_info"][$x][$y][1][1]
                    || $sprite[1] == 90 && $sprite[0] == $this -> template_data["draw_info"][$x][$y][1][0] + 167) {
                        $this -> replaceBiomToNew($this -> template_data["draw_info"][$x][$y][1], $sprite);
                        return;
                }
                array_splice($this -> template_data["draw_info"][$x][$y], 2, 0, array($sprite));
                return;
            }
            array_splice($this -> template_data["draw_info"][$x][$y], 1, 0, array($sprite));
        }

        private function convertTerrain(&$data, $x, $y, $sprite) {
            for ($i = 0; $i < 3; $i++) {
                for ($j = 0; $j < 3; $j++) {
                    $data[$x*3 + $i][$y*3 + $j] = array();
                    if ($i == 1 && $j == 1) {
                        array_push($data[$x*3 + $i][$y*3 + $j], $sprite);
                    } else {
                        if ($x + $i - 2 < 0 || $x + $i - 2 >= $this -> template_data["width"] 
                            || $y + $j - 2 < 0 || $y + $j - 2 >= $this -> template_data["height"]
                            || $this -> template_data["draw_info"][$x + $i - 2][$y + $j - 2][0] == $sprite) {
                                array_push($data[$x*3 + $i][$y*3 + $j], $sprite);
                        } else if ($this -> neighbours[$x][$y] >= $this -> neighbours[$x + $i - 2][$y + $j - 2]) {
                            array_push($data[$x*3 + $i][$y*3 + $j], $sprite);
                        } else {
                            array_push($data[$x*3 + $i][$y*3 + $j], $this -> template_data["draw_info"][$x + $i - 2][$y + $j - 2][0]);
                        }
                    }
                }
            }
        }

        private function convertCity(&$data, $x, $y, $sprite) {
            if ($sprite[0] % 4 != 0) {
                for ($i = 0; $i < 3; $i++) {
                    for ($j = 0; $j < 3; $j++) {
                        if ($i == 1 && $j == 1) {
                            if (isset($data[$x*3 + 1][$y*3 + 1][1]) && $data[$x*3 + 1][$y*3 + 1][1][0] >= 64 && $data[$x*3 + 1][$y*3 + 1][1][0] <= 69) {
                                array_splice($data[$x*3 + 1][$y*3 + 1], 1, 1);
                            }
                            continue;
                        }
                        $cityTier = ($sprite[0] - 44) % 4;
                        $cityRace = floor(($sprite[0] - 44) / 4);
                        $citySpriteFound = 103 + ($cityTier-1)*4 + $cityRace*12*2;
                        if ($sprite[0] % 4 != 1) {
                            $this -> replaceBiomToNew($data[$x*3 + $i][$y*3 + $j][0], $data[$x*3 + 1][$y*3 + 1][0]);
                        }
                        if ($j % 2 == 0) {
                            array_push($data[$x*3 + $i][$y*3 + $j], ($j == 0) ? array($citySpriteFound + 3, 0) : array($citySpriteFound + 2, 0));
                        }
                        if ($i % 2 == 0) {
                            array_push($data[$x*3 + $i][$y*3 + $j], ($i == 0) ? array($citySpriteFound + 1, 0) : array($citySpriteFound + 4, 0));
                        }
                        if ($i == 1) {
                            if ($j == 0 && isset($data[$x*3 + 1][$y*3 + 0][1]) && $data[$x*3 + 1][$y*3 + 0][1][0] == 67) {
                                $this -> replaceBiomToNew($data[$x*3 + 1][$y*3 + 0][1], array(69, 180));
                                if (isset($data[$x*3 + 1][$y*3 + 0][2]) && $data[$x*3 + 1][$y*3 + 0][2][0] == $citySpriteFound + 3) {
                                    $this -> replaceBiomToNew($data[$x*3 + 1][$y*3 + 0][2], array($citySpriteFound + 15, 0));
                                }
                            } else if ($j == 2 && isset($data[$x*3 + 1][$y*3 + 2][1]) && $data[$x*3 + 1][$y*3 + 2][1][0] == 67) {
                                $this -> replaceBiomToNew($data[$x*3 + 1][$y*3 + 2][1], array(69, 0));
                                if (isset($data[$x*3 + 1][$y*3 + 2][2]) && $data[$x*3 + 1][$y*3 + 2][2][0] == $citySpriteFound + 2) {
                                    $this -> replaceBiomToNew($data[$x*3 + 1][$y*3 + 2][2], array($citySpriteFound + 14, 0));
                                }
                            }
                        } else if ($j == 1) {
                            if ($i == 0 && isset($data[$x*3][$y*3 + 1][1]) && $data[$x*3][$y*3 + 1][1][0] == 66) {
                                $this -> replaceBiomToNew($data[$x*3][$y*3 + 1][1], array(69, 270));
                                if (isset($data[$x*3][$y*3 + 1][2]) && $data[$x*3][$y*3 + 1][2][0] == $citySpriteFound + 1) {
                                    $this -> replaceBiomToNew($data[$x*3][$y*3 + 1][2], array($citySpriteFound + 13, 0));
                                }
                            } else if ($i == 2 && isset($data[$x*3 + 2][$y*3 + 1][1]) && $data[$x*3 + 2][$y*3 + 1][1][0] == 66) {
                                $this -> replaceBiomToNew($data[$x*3 + 2][$y*3 + 1][1], array(69, 90));
                                if (isset($data[$x*3 + 2][$y*3 + 1][2]) && $data[$x*3 + 2][$y*3 + 1][2][0] == $citySpriteFound + 4) {
                                    $this -> replaceBiomToNew($data[$x*3 + 2][$y*3 + 1][2], array($citySpriteFound + 16, 0));
                                }
                            }
                        }
                    }
                }
            }
            $this -> setCityTemplateBySprite($data, $x, $y, $sprite);
        }

        private function setCityTemplateBySprite(&$data, $x, $y, $sprite) {
            for ($i = 0; $i < 3; $i++) {
                for ($j = 0; $j < 3; $j++) {
                    switch($sprite[0]) {
                        case 44:
                        case 48:
                        case 52:
                        case 56:
                        case 60:
                            if ($i == 1 && $j == 1) {
                                array_push($data[$x*3 + $i][$y*3 + $j], $sprite);
                            }
                            break;
                        case 45:
                        case 49:
                        case 53:
                        case 57:
                        case 61:
                            if ($i == 1 && $j == 1) {
                                array_push($data[$x*3 + $i][$y*3 + $j], $this -> EmissaryCastleInCityByCoords($x, $y, $sprite));
                            }  else {
                                if (!$this -> checkNotEmptyBiomCell($data[$x*3 + $i][$y*3 + $j][0])) {
                                    array_push($data[$x*3 + $i][$y*3 + $j], array($sprite[0]-($x + $y + $i + $j) % 2, 0));
                                }
                            }
                            break;
                        case 46:
                        case 50:
                        case 54:
                        case 58:
                        case 62:
                            if ($i == 1 && $j == 1) {
                                array_push($data[$x*3 + $i][$y*3 + $j], $this -> EmissaryCastleInCityByCoords($x, $y, $sprite));
                            } else {
                                $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
                                array_push($data[$x*3 + $i][$y*3 + $j], array($sprite[0]-($x + $y + $i + $j) % 3, 0));
                            }
                            break;
                        case 47:
                        case 51:
                        case 55:
                        case 59:
                        case 63:
                            if ($i == 1 && $j == 1) {
                                array_push($data[$x*3 + $i][$y*3 + $j], $this -> EmissaryCastleInCityByCoords($x, $y, $sprite));
                            } else {
                                $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
                                array_push($data[$x*3 + $i][$y*3 + $j], array($sprite[0]-($x + $y + $i + $j) % 4, 0));
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        private function EmissaryCastleInCityByCoords($x, $y, $sprite) {
            $protector = $this -> findCityProtectorIdByCoods($x, $y);
            if ($protector == 0) {
                return $sprite;
            }
            switch(EmissaryModel::dbCountEmissariesByClanId($protector)) {
                case 10:
                case 9:
                    return array(97, 0);
                case 8:
                case 7:
                    return array(96, 0);
                case 6:
                case 5:
                case 4:
                    return array(95, 0);
                default: return array(94, 0);
            }
        }

        private function findCityProtectorIdByCoods($x, $y) {
            foreach($this -> template_data["places"] as $id => $city) {
                if ($city["pos"]["y"] == $x && $city["pos"]["x"] == $y) {
                    return (isset($city["clan_protector"]["id"])) ? $city["clan_protector"]["id"] : 0;
                }
            }
            return 0;
        }

        private function convertRoad(&$data, $x, $y, $sprite) {
            for ($i = 0; $i < 3; $i++) {
                for ($j = 0; $j < 3; $j++) {
                    if (!isset($data[$x*3 + $i][$y*3 + $j])) {
                        $data[$x*3 + $i][$y*3 + $j] = array();
                    }
                    switch($sprite[0]) {
                        case 64:
                            if ($i == 1 && $j == 1) {
                                array_push($data[$x*3 + $i][$y*3 + $j], $sprite);
                            } else if ($j == 1 && $i % 2 == 0) {
                                $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
                                array_push($data[$x*3 + $i][$y*3 + $j], array(66, 0));
                            } else if ($i == 1 && $j % 2 == 0) {
                                $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
                                array_push($data[$x*3 + $i][$y*3 + $j], array(67, 0));
                            }
                            break;
                        case 65:
                            if ($i == 1 && $j == 1) {
                                array_push($data[$x*3 + $i][$y*3 + $j], $sprite);
                            } else if ($j == 1 && ($sprite[1]/90 != 2 && $i == 0 || $sprite[1]/90 != 0 && $i == 2)) {
                                $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
                                array_push($data[$x*3 + $i][$y*3 + $j], array(66, 0));
                            } else if ($i == 1 && ($sprite[1]/90 != 1 && $j == 0 || $sprite[1]/90 != 3 && $j == 2)) {
                                $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
                                array_push($data[$x*3 + $i][$y*3 + $j], array(67, 0));
                            }
                            break; 
                        case 66:
                            if ($j == 1) {
                                $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
                                array_push($data[$x*3 + $i][$y*3 + $j], $sprite);
                            }
                            break;
                        case 67:
                            if ($i == 1) {
                                $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
                                array_push($data[$x*3 + $i][$y*3 + $j], $sprite);
                            }
                            break;
                        case 68:
                            if ($i == 1 && $j == 1) {
                                array_push($data[$x*3 + $i][$y*3 + $j], $sprite);
                            } else if ($j == 1 && ($sprite[1]/90 < 2 && $i == 0 || $sprite[1]/90 >= 2 && $i == 2)) {
                                $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
                                array_push($data[$x*3 + $i][$y*3 + $j], array(66, 0));
                            } else if ($i == 1 && ($sprite[1]/90 % 3 == 0 && $j == 0 || $sprite[1]/90 % 3 != 0 && $j == 2)) {
                                $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
                                array_push($data[$x*3 + $i][$y*3 + $j], array(67, 0));
                            }
                            break;
                        case 69:
                            if ($i == 1 && $j == 1) {
                                array_push($data[$x*3 + $i][$y*3 + $j], $sprite);
                            } else if ($j == 1 && ($i == 0 && $sprite[1] == 270 || $i == 2 && $sprite[1] == 90)) {
                                $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
                                array_push($data[$x*3 + $i][$y*3 + $j], array(66, 0));
                            } else if ($i == 1 && ($j == 0 && $sprite[1] == 180 || $j == 2 && $sprite[1] == 0)) {
                                $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
                                array_push($data[$x*3 + $i][$y*3 + $j], array(67, 0));
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        private function convertBuilding(&$data, $x, $y, $sprite) {
            $minLength = $this -> template_data["width"] + $this -> template_data["height"];
            $minId = 1;
            foreach($this -> template_data["places"] as $id => $city) {
                $ManhattanLegth = abs($city["pos"]["x"] - $y) + abs($city["pos"]["y"]  - $x);
                if ($ManhattanLegth < $minLength 
                    || $ManhattanLegth == $minLength && $city["size"] > $this -> template_data["places"][$minId]["size"]) {
                    $minId = $id;
                    $minLength = $ManhattanLegth;
                }
            }
            $i = 1 + Round(($this -> template_data["places"][$minId]["pos"]["y"] - $x)/$minLength);
            $j = 1 + Round(($this -> template_data["places"][$minId]["pos"]["x"] - $y)/$minLength);
            if ($i != 1 && $j != 1) {
                $this -> replaceBiomToNew($data[$x*3 + 1][$y*3 + 1][0], $data[$x*3 + $i][$y*3 + $j][0]);
            }
            $this -> resolveNotEmptyCell($data[$x*3 + $i][$y*3 + $j][0]);
            array_push($data[$x*3 + $i][$y*3 + $j], $sprite);
        }

        private function replaceBiomToNew(&$biom, $newSprite) {
            $biom = $newSprite;
        }

        private function checkNotEmptyBiomCell($cell) {
            return $cell[0] >= 25 && $cell[0] <= 40 || $cell[0] == 17 || $cell[0] == 18;
        }

        private function resolveNotEmptyCell(&$cell) {
            if ($cell[0] >= 25 && $cell[0] <= 40 || $cell[0] == 17 || $cell[0] == 18) {
                switch($cell[0]) {
                    case 17:
                    case 18:
                        $cell[0] = 42;
                        break;
                    case 25:
                    case 36:
                        $cell[0] = 41;
                        break;
                    case 26:
                    case 34:
                    case 37:
                        $cell[0] = 23;
                        break;
                    case 27:
                    case 35:
                    case 38:
                        $cell[0] = 24;
                        break;
                    case 28:
                        $cell[0] = 43;
                        break;
                    case 29:
                    case 33:
                    case 40:
                        $cell[0] = 22;
                        break;
                    case 30:
                        $cell[0] = 19;
                        break;
                    case 31:
                        $cell[0] = 20;
                        break;
                    case 32:
                        $cell[0] = 21;
                        break;
                }
            }
        }

        private function smoothBiomCells() {
            for ($x = 0; $x < $this -> template_data["width"]; $x++) {
                for ($y = 0; $y < $this -> template_data["height"]; $y++) {
                    for ($i = 0; $i < 3; $i++) {
                        for ($j = 0; $j < 3; $j++) {
                            $this -> checkAngleNeighbours($x*3 + $i, $y*3 + $j);
                        }
                    }
                }
            }
        }

        private function checkAngleNeighbours($x, $y) {
            for ($i = 0; $i <= 2; $i+=2) {
                for ($j = 0; $j <= 2; $j+=2) {
                    if ($x + $i - 1 < 0 || $x + $i - 1 >= $this -> template_data["width"] * 3
                        || $y + $j - 1 < 0 || $y + $j - 1 >= $this -> template_data["height"] * 3) {
                        continue;
                    }
                    // biom is first sprite - [0]
                    if ($this -> template_data["draw_info"][$x][$y][0] == $this -> template_data["draw_info"][$x + $i - 1][$y + $j - 1][0]) {
                        continue;
                    }
                    $smoothSprite = $this -> prepareSmoothSpriteByNeighbour($this -> template_data["draw_info"][$x + $i - 1][$y + $j - 1][0]);
                    if ($this -> template_data["draw_info"][$x][$y][0] == $this -> template_data["draw_info"][$x][$y + $j - 1][0] 
                        || $smoothSprite != $this -> prepareSmoothSpriteByNeighbour($this -> template_data["draw_info"][$x][$y + $j - 1][0])) {
                        continue;
                    }
                    if ($this -> template_data["draw_info"][$x][$y][0] == $this -> template_data["draw_info"][$x + $i - 1][$y][0] 
                        || $smoothSprite != $this -> prepareSmoothSpriteByNeighbour($this -> template_data["draw_info"][$x + $i - 1][$y][0])) {
                        continue;
                    }
                    if ($i == 0) {
                        array_splice($this -> template_data["draw_info"][$x][$y], 1, 0, array(array($smoothSprite, ($j == 0) ? 0 : 90)));
                    } else {
                        array_splice($this -> template_data["draw_info"][$x][$y], 1, 0, array(array($smoothSprite, ($j == 0) ? 270 : 180)));
                    }
                }
            }
        }

        private function prepareSmoothSpriteByNeighbour($neighbourSprite) {
            switch($neighbourSprite[0]) {
                case 17:
                case 18:
                case 42:
                    return 230;
                    break;
                case 25:
                case 36:
                case 41:
                    return 231;
                    break;
                case 26:
                case 34:
                case 37:
                case 23:
                    return 224;
                    break;
                case 27:
                case 35:
                case 38:
                case 24:
                    return 226;
                    break;
                case 28:
                case 43:
                    return 232;
                    break;
                case 29:
                case 33:
                case 40:
                case 22:
                    return 229;
                    break;
                case 30:
                case 19:
                    return 225;
                    break;
                case 31:
                case 20:
                    return 227;
                    break;
                case 32:
                case 21:
                    return 228;
                    break;
                default:
                    return 0;
            }
        }

        function __destruct() {}
    }
    
    $map = new MapController();
    $map -> main();
    $sprite = (isset($_REQUEST['sprite'])) ? $_REQUEST['sprite'] : "map";
    $mode = (isset($_REQUEST['mode'])) ? $_REQUEST['mode'] : "default";

    include "../views/MapView.php";
?>