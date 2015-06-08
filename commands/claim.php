<?php
	function claim($str, $sender, $senderId) {
		require_once("lib/user.class.php");
		require_once("lib/database.class.php");
		
		$reponse = "";

		$amount = 1; // maybe make the daily reward more on some days.

		// First, check if user is registered
		$user = new User($senderId);
		if (!$user->isRegistered) {
			return "it seems you haven't registered...!";
		}

		// Check if the sender has claimed their daily reward
		$db = Database::getInstance();
		$db->query("SELECT `dailyreward` FROM `userinfo` WHERE userID=?", array($user->userid));
		$r = $db->firstResult();
		if ($r) {
			if ($r['dailyreward'] == false) {
				// Not claimed, take from bank and set it to true
				$bank = new User('bank');
				
				$bt = $bank->take($amount);
				$ug = $user->give($amount);

				$db->query("UPDATE `userinfo` SET `dailyreward`=1 WHERE userID=?", array($user->userid));
				if (!$db->error()) {
					return "you claimed your daily allowance of :bone:".$amount.". Balance: :bone:".$user->bones;
				} else {
					return "i had an accident...";
				}
			} else {
				return "you already claimed your allowance today come on ya greedy slob";
			}
		}
	}