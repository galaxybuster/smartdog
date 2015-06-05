<?php
	function bgmpSlack($str) {
		require_once("lib/database.class.php");
		$str = ltrim($str, "<");
		$str = rtrim($str, ">");

		//$url = "https://www.youtube.com/watch?v=3eMqPJ_RS0A";
		$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';


		$db = Database::getInstance();
		$q = "INSERT INTO tracks (youtube_id) VALUES ";

		// Use regex to trim everytihng but the ID

		preg_match($pattern, $str, $matches);
		if (isset($matches[1])) {
			//file_put_contents('playlist.txt', trim($matches[1]).PHP_EOL, FILE_APPEND);
			$q .= "('" . $matches[1] . "')";
			$err .= "OK, I've added " . $matches[1] . " to BGMP.\r\n";
		} else {
			$err .= "Error - Try another URL\r\n";
		}
		$db->query($q);

		if ($db->error()) {
			$err .= "Error inserting into database\r\n";
		}

		return $err;
	}