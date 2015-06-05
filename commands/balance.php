<?php
	function balance($str, $sender, $senderId) {
		require_once("lib/database.class.php");
		
		$reponse = "";

		// First, check if user is registered
		$db = Database::getInstance();
		$db->query("SELECT * FROM userinfo WHERE userID=?", array($senderId));
		if ($db->firstResult() != null) {

			$db->query("SELECT money FROM userinfo WHERE userID=?", array($senderId));

			if (!$db->error()) {
				$row = $db->firstResult();
				$response = $sender.", you have :bone:".$row["money"];
			} else {
				$response = "i had an accident. . . try again later, ok?";
			}
		} else {
			$response = "it seems. . . you haven't registered!";
		}

		return $response;
	}