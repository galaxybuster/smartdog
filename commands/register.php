<?php
	function register($str, $sender, $senderId) {
		require_once("lib/database.class.php");
		

		$reponse = "";

		// First, check if these punks are trying to double-register
		$db = Database::getInstance();
		$db->query("SELECT * FROM userinfo WHERE userID=?", array($senderId));
		if ($db->firstResult() != null) {
			$response = "i already know you!";
		} else {
			$db->query("INSERT INTO userinfo (userID, username) VALUES (?, ?)", array($senderId, $sender));

			if (!$db->error()) {
				$response = "i registered ".$sender."!";
			} else {
				$response = "i had an accident. . . try again later, ok?";
			}
		}

		return $response;
	}