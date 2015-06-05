<?php
	/*
	 * smartdog give @user number
	 *
	 */
	function give($str, $sender, $senderId) {
		require_once("lib/database.class.php");
		$db = Database::getInstance();
		$response = "";

		// $str should be "give @user number (...)"
		$token = explode(" ", $str, 3);
		//array_shift($token); // this should be 'give'
		$user = $token[0];
		$amount = $token[1];

		// Check that the sender is registered
		$db->query("SELECT * FROM userinfo WHERE userID=?", array($senderId));
		if ($db->firstResult() != null) {
			// while we're at it, get the amount of money they have
			$db->query("SELECT money FROM userinfo WHERE userID=?", array($senderId));
			if (!$db->error()) {
				$row = $db->firstResult();				
				$senderMoney = $row["money"];
			} else {
				$response = "i had an accident. . . try again later, ok?";
			}
		} else {
			$response = "it seems. . . you haven't registered!";
			return $response;
		}

		// now check if user is registered
		$db->query("SELECT * FROM userinfo WHERE username=?", array($user));
		if (!is_null($db->firstResult())) {
			// while we're at it, get the amount of money they have
			$db->query("SELECT money, userID FROM userinfo WHERE username=?", array($user));
			if (!$db->error()) {
				$row = $db->firstResult();
				$recipientMoney = $row["money"];
				$userID = $row['userID'];
			} else {
				$response = "i had an accident. . . try again later, ok?";
				return $response;
			}
		} else {
			$response = "it seems. . . ".$user." hasn't registered!";
			return $response;
		}

		if ($user !== $sender) {
			if ($amount < 0) {
				$response = "h-hey, you can't take from them. . .";
				return $response;
			} else {
				if ($senderMoney >= $amount) {
					$senderMoney -= $amount;
					$recipientMoney += $amount;
					// Update values
					$db->query("UPDATE userinfo SET money=".$senderMoney." WHERE userID=?", array($senderId));
					if ($db->error()) {
						$response = "i had an accident. . . try again later, ok?";
						return $response;
					} else {
						$db->query("UPDATE userinfo SET money=".$recipientMoney." WHERE userID=?", array($userID));
						if ($db->error()) {
							$response = "i had an accident. . . try again later, ok?";
							return $response;
						} else {
							$response = "i did it! ".$sender." gave :bone:".$amount." to ".$user."!";
							return $response;
						}
					}
				} else {
					$response = "you uh don't have enough :bone:...sorry";
					return $response;
				}
			}
		} else {
			$response = "you can't give to yourself. . .";
			return $response;
		}
		
		

		return $response;
	}