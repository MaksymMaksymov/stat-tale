<?php
	if (isset($_REQUEST['controller'])) {
		if ($_REQUEST['controller'] == "Heroes") {
			if (isset($_REQUEST['ids']))
	    		$arr_ids = $_REQUEST['ids'];
	    	else 
	    		$arr_ids = array(56706,8157,47855,23535,32168,1819,34234,35459,13714,19149,17118,18330,11846,2795,2796,8117,6370,6014,5337,6091,12234,18776,17927,43113,13961,9959,12218,29435,10170,10745,15525,4344);
		} else if ($_REQUEST['controller'] == "Masters") {
			if (isset($_REQUEST['ids']))
	    		$arr_ids = $_REQUEST['ids'];
	    	else 
	    		$arr_ids = array(1462,2910,2472,3284,1682,1931,1875,3403,2966,2964,3203,3384,2353,2905,2942,3381,2470,3195,2577,1776,3389,1719,3355,2914,3135,1972,2922,3391,2929,2292,1826,1923,3304,2232,3400,3329,3263,1851,3301,3413,3324,3052,2940,3357,2804,3388,3395,3023,3128,1925,3362,2330,2094,3339,2845,1908,2165,2408,3092,3358,3356,3274,1550,1091,3009,2573,2085,1831,1178,2879,3392,3255,1706,3316,2459,3363,1107,2691,4,2096,3374,3369,3214,3410,3405,3303,3408,3399,3406,3335,3412,1732,3317,3115,3340,2121,3375,3333,3293,3372,3291,2130,3383,2421,3354,2850,3142,3250,3347,3199,2766,3380,3361,3287,3330,3365,3366,3105,2967,1836,3278,75,1867,3368,3359,1914,3016,2705,2725,2054,3373,3185,3024,1521,899,3275,3239,3407,3036,3093,2606,1829,2665,2654,2646,3201,1164,3332,3370,3223,906,2442,3411,3238,3371,2815,3360,2856,3396,3074,3244,3390,3398,3314,2291,3351,2949,1981,2366,3402,2988,1876,3141,2726,3124,2424,3394,2506,3248,3404,3409,3242,3397,2641,3387,3236,2795,2938,3376,1926,1974,3229,1587,2365,2496,2454,3393,3050,3367,3328,3377,1064,3025,2968,3210,2370,3277,1962,2494,1410,3153,2548,785,3040,1644,3401,3382,2869,2658,2041,1845,1606,1864,2975,3054,3179,993,2036,3346,3385,3343,3116,3378,3386,1832,3197,2648,2931,2142,1909,3379,3034);
		} else if ($_REQUEST['controller'] == "Places") {
			if (isset($_REQUEST['ids']))
	    		$arr_ids = $_REQUEST['ids'];
		}
		if (!isset($arr_ids))
			$arr_ids = null;

		$data = $_REQUEST['controller']."_data";

		include "controllers/".$_REQUEST['controller']."Controller.php";

		session_start();
	    if (isset($_REQUEST['refresh'])) {
	        if ($_REQUEST['refresh'] == "true")
	        	if (isset($data))
	            	unset($_SESSION[$data]);
	    }

    	$class_sorted = (isset($_REQUEST['class'])) ? $_REQUEST['class'] : "";
    	$sort_direction = (isset($_REQUEST['direction'])) ? $_REQUEST['direction'] : "false";

	    if (isset($_SESSION[$data])) {
	        $get_info = $_SESSION[$data];
	    } else {
	    	if ($_REQUEST['controller'] == "Heroes") {
	        	$get_info = new HeroesController();
	        } else if ($_REQUEST['controller'] == "Masters") {
	        	$get_info = new MastersController();
	        } else if ($_REQUEST['controller'] == "Places") {
	        	$get_info = new PlacesController();
	        }
	        $get_info -> getArrayToParse($arr_ids);
	        $_SESSION[$data] = $get_info;
	    }
	    if (isset($get_info))
	    	$get_info -> sortByClass($class_sorted,$sort_direction);

	    if ($_REQUEST['controller'] == "Heroes") 
	    	$arr_dict_places = $get_info -> getPlacesDictionary();
	    
	    include "views/".$_REQUEST['controller']."View.php";
	}
?>