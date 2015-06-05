<?php
	function balance($str, $sender, $senderId) {
		require_once("lib/user.class.php");
		require_once("lib/database.class.php");
		
		$reponse = "";

		// First, check if user is registered
		$user = new User($senderId);
		if (!$user->isRegistered) {
			return "it seems you haven't registered...!";
		}

		return $sender.", you have :bone:".$user->bones;
	}