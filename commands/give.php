<?php
	/*
	 * smartdog give @user number
	 *
	 */
	function give($str, $sender, $senderId) {
		require_once("lib/user.class.php");
		require_once("lib/database.class.php");
		$db = Database::getInstance();

		$response = "";

		// $str should be "give @user number (...)"
		$token = explode(" ", $str, 3);
		$target = $token[0];
		$amount = $token[1];

		// Check that the sender and user are registered
		$senderUser = new User($senderId);
		if (!$senderUser->isRegistered) {
			return "it seems... you haven't registered!!";
		}
		$targetUser = User::getUserByName($target);
		if (!$targetUser->isRegistered) {
			return "it seems... " . $target . " hasn't registered!";
		}


		// Make sure its not the sender giving to themselves
		if ($senderUser->userid == $targetUser->userid) {
			return "hey! you can't give to yourself!";
		}

		// make sure it's an integer we want to keep these things ints
		if (fmod($amount, 1) != 0) {
			return "please, no decimals...! _my brain can't do that........_";
		}

		// another trick: giving negative money to take
		if ($amount < 0) {
			return "h-hey, you can't take from them. . .";
		}

		// make sure the sender is able to give that much
		if ($senderUser->bones < $amount) {
			return "you uh don't have enough, sorry if that's ok";
		}

		// Okay now for the real transaction
		if ($senderUser->take($amount)) {
			if ($targetUser->give($amount)) {
				return "i did it! " .$sender." gave ".$target." :bone:".$amount;
			}
		}
		
		

		return "please use `smartdog give [user] [amount]`.";
	}