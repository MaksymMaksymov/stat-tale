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