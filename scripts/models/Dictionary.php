<?php
	class Dictionary
    {
        public static function getProfession($pro_id) { 
            switch ($pro_id) {
                case 0:
                    return "кузнец";
                case 1:
                    return "рыбак";  
                case 2:
                    return "портной";
                case 3:
                    return "плотник";
                case 4:
                    return "охотник";
                case 5:
                    return "стражник";
                case 6:
                    return "торговец"; 
                case 7:
                    return "трактирщик";  
                case 8:
                    return "вор";
                case 9:
                    return "фермер";  
                case 10:
                    return "шахтер";
                case 11:
                    return "священник";   
                case 12:
                    return "лекарь";    
                case 13:
                    return "алхимик";
                case 14:
                    return "палач";
                case 15:
                    return "волшебник";
                case 16:
                    return "ростовщик";
                case 17:
                    return "писарь";
                case 18:
                    return "магомеханик";
                case 19:
                    return "бард";
                case 20:
                    return "дрессировщик";                 
                case 21:
                    return "скотовод";
            }         
        }
         
        public static function getSpecialization($specialization) {
            switch ($specialization) {
                case 0:
                    return "Торговый центр";
                case 1:
                    return "Город ремёсел";
                case 2:
                    return "Форт";
                case 3:
                    return "Политический центр";
                case 4:
                    return "Полис";
                case 5:           
                    return "Курорт";          
                case 6:
                    return "Транспортный узел";      
                case 7:
                    return "Вольница";     
                case 8:
                    return "Святой город";     
                case 9:
                    return "Обычный город";
            }  
        }

        public static function getSpecId($specialization) {
            switch ($specialization) {
                case 0:
                    return "spec_tc";
                case 1:
                    return "spec_gr";
                case 2:
                    return "spec_f";
                case 3:
                    return "spec_pc";
                case 4:
                    return "spec_p";
                case 5:           
                    return "spec_k";          
                case 6:
                    return "spec_tu";      
                case 7:
                    return "spec_v";     
                case 8:
                    return "spec_sg";     
                case 9:
                    return "-1";
            }  
        }

        public static function getRace($gender, $race) {         
            if ($gender == 0) {
                if ($race == 0) {                
                    return "Мужчина";              
                } else if ($race == 1) {                
                    return "Эльф";                             
                } else if ($race == 2) {                
                    return "Орк";              
                } else if ($race == 3) {                
                    return "Гоблин";                               
                } else if ($race == 4) {                
                    return "Дварф";             
                } else {                
                    return "Он";               
                }                
            } else if ($gender == 1) {            
                if ($race == 0) { 
                    return "Женщина";
                } else if ($race == 1) {
                    return "Эльфийка";
                } else if ($race == 2) {
                    return "Оркесса";
                } else if ($race == 3) {
                    return "Гоблинша"; 
                } else if ($race == 4) {
                    return "Дварфийка";
                } else {
                    return "Она";
                }
            } else if ($gender == 2) {
                if ($race == 0) {
                    return "Оно - Человек>";
                } else if ($race == 1) {
                    return "Оно - Эльф";                               
                } else if ($race == 2) {
                    return "Оно - Орк";
                } else if ($race == 3) {
                    return "Оно - Гоблин";
                } else if ($race == 4) {
                    return "Оно - Дварф";
                } else {
                    return "Он";
                }
            } else {
                if ($race == 0) {
                    return "Люди";
                } else if ($race == 1) {
                    return "Эльфы";                               
                } else if ($race == 2) {
                    return "Орки";
                } else if ($race == 3) {
                    return "Гоблины";
                } else if ($race == 4) {
                    return "Дварфы";
                } else {
                    return "Монстр";
                }
            }
        }

        public static function getRaceAndGender($race_title) {
            $race_title = trim($race_title);
            if ($race_title == "мужчина") {
                $tmp["race"] = 0;
                $tmp["gender"] = 0;
                return $tmp;
            }
            if ($race_title == "эльф") {
                $tmp["race"] = 1;
                $tmp["gender"] = 0;
                return $tmp;
            }
            if ($race_title == "орк") {
                $tmp["race"] = 2;
                $tmp["gender"] = 0;
                return $tmp;
            }
            if ($race_title == "гоблин") {
                $tmp["race"] = 3;
                $tmp["gender"] = 0;
                return $tmp;
            }
            if ($race_title == "дварф") {
                $tmp["race"] = 4;
                $tmp["gender"] = 0;
                return $tmp;
            }
            if ($race_title == "женщина") {
                $tmp["race"] = 0;
                $tmp["gender"] = 1;
                return $tmp;
            }
            if ($race_title == "эльфийка") {
                $tmp["race"] = 1;
                $tmp["gender"] = 1;
                return $tmp;
            }
            if ($race_title == "оркесса") {
                $tmp["race"] = 2;
                $tmp["gender"] = 1;
                return $tmp;
            }
            if ($race_title == "гоблинша") {
                $tmp["race"] = 3;
                $tmp["gender"] = 1;
                return $tmp;
            }
            if ($race_title == "дварфийка") {
                $tmp["race"] = 4;
                $tmp["gender"] = 1;
                return $tmp;
            }
            return false;
        }

        public static function getRaceByDemografic($race_title) {
            $race_title = trim($race_title);
            if ($race_title == "Люди") {
                return 0;
            }
            if ($race_title == "Эльфы") {
                return 2; // tile number
            }
            if ($race_title == "Орки") {
                return 4;
            }
            if ($race_title == "Гоблины") {
                return 3;
            }
            if ($race_title == "Дварфы") {
                return 1;
            }
        }

        public static function getJobEffect($effect) {
            switch ($effect) {
                case 1:
                    return "Производство";
                case 2:
                    return "Безопасность";
                case 3:
                    return "Транспорт";
                case 4:
                    return "Свобода";
                case 5:           
                    return "Стабильность";          
                case 6:
                    return "Золото";      
                case 7:
                    return "Артефакты";     
                case 8:
                    return "Опыт";     
                case 9:
                    return "Карты";
                case 10:
                    return "Культура";
            }  
        }

        public static function getHonor($tmp) { 
            switch ($tmp) {
                case 'бесчестный':
                case 'бесчестная':
                case 'бесчестное':
                    return 1;
                case 'подлый':
                case 'подлая':
                case 'подлое':
                    return 2;  
                case 'порочный':
                case 'порочная':
                case 'порочное':
                    return 3;
                case 'себе на уме':
                    return 4;
                case 'порядочный':
                case 'порядочная':
                case 'порядочное':
                    return 5;
                case 'благородный':
                case 'благородная':
                case 'благородное':
                    return 6;
                case 'хозяин своего слова':
                case 'хозяйка своего слова':
                    return 7; 
                 
                default:
                    return 0;
            }         
        }

        public static function getPeace($tmp) { 
            switch ($tmp) {
                case 'скорый на расправу':
                case 'скорая на расправу':
                case 'скорое на расправу':
                    return 1;
                case 'вспыльчивый':
                case'вспыльчивая':
                case 'вспыльчивое':
                    return 2;  
                case 'задира':
                    return 3;
                case 'сдержанный':
                case 'сдержанная':
                case 'сдержаное':
                    return 4;
                case 'доброхот':
                    return 5;
                case 'миролюбивый':
                case 'миролюбивая':
                case 'миролюбивое':
                    return 6;
                case 'гуманист':
                    return 7; 
                 
                default:
                    return 0;
            }         
        }

        public static function getSizeCoef($size) {
            switch ($size) {
                case '1':
                    return 0.59;
                case '2':
                    return 1.18;
                case '3':
                    return 1.52;
                case '4':
                    return 1.76;
                case '5':
                    return 1.95;
                case '6':
                    return 2.11;
                case '7':
                    return 2.24;
                case '8':
                    return 2.35;
                case '9':
                    return 2.45;
                case '10':
                    return 2.54;

                default:
                    return 0.59;
            }
        }
    }

    class Messages {
    	public static function showNoData($msg) {
    		echo "<h1 class='error_msg'>".$msg." data is not available</h1>";
    	}

    	public static function showWrongData($msg) {
    		echo "<h1 class='error_msg'>The error was occurred:".$msg."</h1>";
    	}

    	public static function showMsg($msg) {
    		echo "<h1 class='error_msg'>".$msg."</h1>";
    	}
    }

    class CheckStatus {

    	public static function check($arr) {
    		if (isset($arr['status'])) {
				if ($arr['status'] != "ok") {
					if ($arr['status'] == "error") {
						if (isset($arr['error'])) {
							Messages::showWrongData($arr['error']);
						} else if(isset($arr['errors'])) {
							Messages::showWrongData("more than one");
							var_dump($arr['errors']);
						}
					} else {
						if ($arr['status'] == "processing") {
							Messages::showMsg("Server is sleeping. Reload the page");
						} else {
							Messages::showWrongData(isset($arr['status']));
						}
					}
					return false;
				}
				else return true;
			} else {
				Messages::showMsg("The status wasn't caught");
				return false;
			}
    	}
    }
?>