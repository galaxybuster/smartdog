<?php
	function bgmpSlack($str, $sender, $senderId) {
		require_once("lib/database.class.php");
		require_once("lib/user.class.php");

		$str = ltrim($str, "<");
		$str = rtrim($str, ">");

		$response = "";

		$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';


		// Get user, if theyre registered they earn bones
		$user = new User($senderId);
		
		$db = Database::getInstance();

		// Use regex to trim everything but the ID
		preg_match($pattern, $str, $matches);
		if (isset($matches[1])) {
			$yid = $matches[1];
		} else {
			$response .= "i had a problem with the URL...\r\n";
		}

		// Check to see if it's not already there
		$db->query("SELECT * FROM tracks WHERE youtube_id=?", array($yid));
		if ($db->error()) {
			// unable to check for conflict
			$response .= "i had a error reading database...\r\n";
		} else if ($db->firstResult()) {
			// track already exists
			$response .= "sorry, i already have this one...\r\n";
		} else {
			// track doesn't exist! insert into database and pay out bones
			$db->query("INSERT INTO tracks (youtube_id) VALUES (?)", array($yid));

			if ($db->error()) {
				$response .= "i had a error inserting into database...\r\n";
			} else {
				// successfully inserted. time to reward
				$response .= "i did it! i added ".$yid." to BGMP\r\n";

				if ($user->isRegistered) {

					$reward = 1; // for now, its 1 bone. will increase as necessary. daily bonus may come into effect.

					$bank = new User('bank');
					
					if ($bank->take($reward)) {
						if ($user->give($reward)) {
							$response .= "i gave you :bone:".$reward." for your trouble, you have :bone:".$user->bones;
						} else {
							$response .= "i had trouble paying you though....";
						}
					} else {
						$response .= "i had trouble paying you though...";
					}
					
					
				} else {
					$response .= "by the way, if you register, you can earn bones for putting tracks in BGMP";
				}
			}
			
		}

		return $response;
	}