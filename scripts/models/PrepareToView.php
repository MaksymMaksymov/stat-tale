<?php
	class PrepareToView {
		function __construct() {}

		public static function createImg($src,$alt) {
			$result = array();
			$result['type'] = "img";
            $result['src'] = $src;
            $result['alt'] = $alt;
            return $result;
		}

		public static function createText($title, $value) {
			$result = array();
			$result['type'] = "text";
            $result['title'] = $title;
            $result['value'] = $value;
            return $result;
		}

		public static function createUrl($href, $value, $title = "") {
			$result = array();
			$result['type'] = "url";
            $result['href'] = $href;
            $result['title'] = $title;
            $result['value'] = $value;
            return $result;
		}

		public static function createSpoiler($name, $value) {
			$result = array();
			$result['type'] = "spoiler";
            $result['name'] = $name;
            $result['value'] = $value;
            return $result;
		}

		function __destruct() {}
	}
?>