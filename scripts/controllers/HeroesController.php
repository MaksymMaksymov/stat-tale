<?php
	include_once("config/cfg.php");
	include_once("scripts/controllers/Controller.php");
    include_once("scripts/models/PlacesModel.php");
	include_once("scripts/models/HeroModel.php");
	include_once("scripts/models/GetInfoByURLModel.php");

	class HeroesController extends Controller {
		public $heroes;

        function __construct() {}
           
	    public function getArrayToParse($array_of_ids = null) {
            if ($array_of_ids == null)  {
                Messages::showNoData("Heroes");
            } else {
                $curlGet = new GetInfoByURLModel();
                $this -> heroes = array();
                foreach ($array_of_ids as $key => $value) {
                    $hero = new HeroModel();
                    $tmp_url =  str_replace("<account>", $value, $GLOBALS["HEROES_URL"]);
                    $arr_heroes = $curlGet -> getInformation($tmp_url); 
                    if (!$hero -> setValues($arr_heroes)) continue;

                    $angel = new AngelModel();
                    $tmp_url =  str_replace("<account>", $value, $GLOBALS["ANGELS_URL"]);
                    $arr_angels = $curlGet -> getInformation($tmp_url); 
                    if (!$angel -> setValues($arr_angels)) continue;
                    if ($angel != null) {
                        $hero -> setAngel($angel);
                    }
                    array_push($this -> heroes, $hero);
                    unset($hero);
                }
                unset($curlGet);
            }
        }

        public function getPlacesDictionary() {
            $curlGetDict = new GetInfoByURLModel(); 
            $arr_all_places = $curlGetDict -> getInformation($GLOBALS["PLACES_URL_LIST"]); 

            $arr_places = new PlacesModel();
            if (!$arr_places -> setValues($arr_all_places)) 
                return NULL;
            $arr_make_places = array();
            for ($i = 0; $i < $arr_places -> countValues(); $i++) {
                $arr_make_places[$arr_places -> getId($i)] = $arr_places -> getName($i);
            }
            return $arr_make_places;
        }

        public function sortByClass($class, $direction = "false") {
            if ($class == "") return;
            $arr_to_sort = array();
            foreach ($this -> heroes as $key => $value) {
                switch ($class) {
                    case "angel": 
                        $arr_to_sort[$key] = $value -> getAngel();
                        break;
                    case "might": 
                        $arr_to_sort[$key] = $value -> getMight();
                        break;
                    case "clan": 
                        $arr_to_sort[$key] = $value -> getClan();
                        break;
                    case "hero": 
                        $arr_to_sort[$key] = $value -> getHero();
                        break;
                    case "race": 
                        $arr_to_sort[$key] = $value -> getRace();
                        break;
                    case "level": 
                        $arr_to_sort[$key] = $value -> getLevel();
                        break;
                    case "power": 
                        $arr_to_sort[$key] = $value -> getPower();
                        break;
                    case "companion": 
                        $arr_to_sort[$key] = $value -> getCompanion();
                        break;
                    case "habits": 
                        $arr_to_sort[$key] = $value -> getHabits();
                        break;
                    case "money": 
                        $arr_to_sort[$key] = $value -> getMoney();
                        break;
                    case "strength": 
                        $arr_to_sort[$key] = $value -> getStrength();
                        break;
                    case "physic": 
                        $arr_to_sort[$key] = $value -> getPhysic();
                        break;
                    case "magic": 
                        $arr_to_sort[$key] = $value -> getMagic();
                        break;
                    case "equiment": 
                        $arr_to_sort[$key] = $value -> getEquiment();
                        break;
                    case "preference": 
                        $arr_to_sort[$key] = $value -> getPreference();
                        break;
                    case "speed": 
                        $arr_to_sort[$key] = $value -> getSpeed();
                        break;
                    case "initiative": 
                        $arr_to_sort[$key] = $value -> getInitiative();
                        break;
                }    
            }
            if ($direction == "false")
                asort($arr_to_sort);
            else 
                arsort($arr_to_sort);
            $new_heroes = array();
            foreach ($arr_to_sort as $key => $value) {
                $hero = $this -> heroes[$key];
                array_push($new_heroes, $hero);
            }
            unset($this -> heroes);
            $this -> heroes = $new_heroes;
        }

        function __destruct() {}
    }
?>