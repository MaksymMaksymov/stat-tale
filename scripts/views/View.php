<script type="text/javascript">
	$(document).ready(function() {
		$('.spoiler_links').click(function() {
			$(this).next('.spoiler_body').slideToggle();
		});

		$('.sort').click(function() {
			var hid_class = document.getElementById('class');
			var hid_direction = document.getElementById('direction');
			var get_class = $(this).attr('class');
			get_class = get_class.substr(0,get_class.indexOf('sort'));
			get_class = get_class.trim();
			if (hid_direction.getAttribute('value') == 'false') {
				$(hid_direction).attr('value', 'true');
			} else {
				if (hid_class.getAttribute('value') == get_class) {
					$(hid_direction).attr('value', 'false');
				} else {
					$(hid_direction).attr('value', 'true');
				}
			}
			$(hid_class).attr('value', get_class);

			document.getElementById('tbl_form').submit();
		});
	});
</script>

<?php
	class View {
		function __construct() {}

		public static function getForm($class,$direction) {
			echo "<form id='tbl_form' action='' method='post'>";
			echo "<div id='hidden_inputs'>";
		 		echo "<input id='class' type='hidden' name='class' value='".$class."' />";
		 		echo "<input id='direction' type='hidden' name='direction' value='".$direction."' />";
 			echo "</div>";
		}

		public static function getFormEnd() {
			echo "<button type='submit' style='display: none;''></button>";
            echo "</form>";
        }

		public static function getHeaderTable($tableName, $arr_headers_classes, $arr_headers) {
			echo "<table class='".$tableName." data_table'>";
            echo "<tr class='thr_".$tableName."'>";
            for ($i = 0; $i < count($arr_headers_classes); $i++) {
            	if (!isset($arr_headers_classes[$i])) continue;
            	echo "<th class='";
            	View::setClasses($arr_headers_classes[$i]);
            	echo "'>";
            	if (isset($arr_headers[$i]))
            		View::getData($arr_headers[$i]);
            	echo "</th>";
            }
            echo "</tr>";
		}

		public static function getModelData($arr_elements, &$result) {
		    foreach ($arr_elements as $k => $v) { 
		    	$element = $v -> getValues();
		    	array_push($result, $element);
		    }
		}

		public static function getTableData($tableName, $arr_classes, $arr_data) {
			for ($k = 0; $k < count($arr_data); $k++) {
		        echo "<tr class='tr_".$tableName."'>";
		        for ($j = 0; $j < count($arr_classes); $j++) {
		        	if (!isset($arr_classes[$j])) continue;
		          	echo "<td class='";
	            	View::setClasses($arr_classes[$j],1);
	            	echo "'>";
		          	if (isset($arr_data[$k][$j])) 
		          		View::getArray($arr_data[$k][$j]);
					echo "</td>";
		        }
		        echo "</tr>";
	      	}
		}

		public static function getData($data) {
			if (is_array($data)) {
				if (isset($data['type'])) {
					if ($data['type'] == "url") {
						$title = (isset($data['title'])) ? $data['title'] : "";
						echo "<a href='".$data['href']."' title='".$title ."'>".$data['value']."</a>";
					} else if ($data['type'] == "img") {
						$src = (isset($data['src'])) ? $data['src'] : "";
						$alt = (isset($data['alt'])) ? $data['alt'] : "";
						echo "<img src='../../img/".$src."' alt='".$alt."' title='".$alt."' class='small_img'>";
					} else if ($data['type'] == "spoiler") {
						echo "<div>";
							echo "<span class='spoiler_links'>";
								echo (isset($data['name'])) ? $data['name'] : "";
							echo "</span>";
							echo "<div class='spoiler_body'>";
								echo (isset($data['value'])) ? $data['value'] : "";
							echo "</div>";
						echo "</div>";
					} else if ($data['type'] == "text") {
						if (isset($data['value'])) {
							if (isset($data['title'])) {
								echo "<font title='".$data['title']."'>".$data['value']."</font>";
							} else 
								echo $data['value'];
						} else
							var_dump($data);
					}
					else
						echo var_dump($data);
				}
			} else 
				echo $data;
		}

		public static function setClasses($classes, $rule = 0) {
			if (is_array($classes)) {
				if ($rule == 1) {
					echo $classes[0];
				} else {
					$first = true;
					foreach ($classes as $key => $value) {
						if ($first) 
							$first = false;
						else
							echo " ";
						echo $value;
					}
				}
			} else 
				echo $classes;
		}

		public static function getArray($data) {
			foreach ($data as $key => $value) {
				View::getData($value);
			}
		}

		public static function getFooterTable() {
            echo "</table>";
        }

        public static function getHeader() {
            include_once("MenuView.php");
        }

        public static function getFooter() {
            echo "<hr/><footer><span>Copyright by</span> <a href='http://the-tale.org/accounts/56706?referral=56706'>Mefi</a> <span>&copy 2018</span></footer>";
        }

        public static function getTablePage($info,$table_name,$arr_classes,$arr_headers) {
		    View::getForm((isset($_REQUEST['class'])) ? $_REQUEST['class'] : "",(isset($_REQUEST['direction'])) ? $_REQUEST['direction'] : "false");
		    View::getHeaderTable($table_name,$arr_classes,$arr_headers);
        	$array_out = array();
		    View::getModelData($info -> model_array,$array_out);
		    View::getTableData($table_name,$arr_classes,$array_out);
		    View::getFooterTable();
		    View::getFormEnd();
        }

        function __destruct() {}
	}
?>