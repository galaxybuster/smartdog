<?php
	/*
	 * smartdog give @user number
	 *
	 */
	function slots($str, $sender, $senderId) {
		require_once("lib/database.class.php");
		require_once("lib/user.class.php");

		$db = Database::getInstance();
		$response = "";

		// $str should be "(bones)"		
		$amount = $str;

		// Check that the sender is registered
		$user = new User($senderId);
		if (!$user->isRegistered) {
			return "i don't think you're registered, i-if that's ok..";
		}
		
		// Check that its a positive bet
		if ($amount <= 0) {
			return "p-please bet a positive amount...";
		}
		// Check that its an integer
		if (fmod($amount, 1) != 0) {
			return "p-please no decimals, i can't understand them...";
		}

		// Make sure sender has enough
		if ($user->bones < $amount) {
			return "don't make me come after you tryin to gamble what you aint got";
		}

		// pull that lever

		/*
		slotValues[0] - names as key, percent as value
		slotValues[1] - payout multiplier for this panel
		*/
		$slotValues = array(
			array(":boutit:"=>1, ":zippy:"=>5, ":wario:"=>7, ":eyepop:"=>2.4, ":mega:"=>4),
			array(":boutit:"=>1.33, ":zippy:"=>0.2, ":wario:"=>0, ":eyepop:"=>0.8, ":mega:"=>0.25));
		
		/*
		the score will be tallied like so:
		
		repeat 3
			weighted random select the slot accoring to slotValues[1][i]
		payout = wager * sum(slotValue[2][roll])

		max payout: 4x wager
		min payout: 0
			
		As you can see this system is quite simple, so there will be vulnerabilities
		*/

		$payout = 0;
		$slotString = "| ";
		for ($i = 0; $i < 3; $i++) {
			$v = getRandomWeightedElement($slotValues[0]);
			// $v is the emoiji name
			$slotString .= $v . " | ";

			// get that multiplier
			$payout += $slotValues[1][$v];
		}

		$payout = round($amount * $payout);

		// Money has to come from somewhere, let's get it from the bank
		$bank = new User('bank');
		
		$bg = $bank->give($amount);
		$ut = $user->take($amount);
		$bt = $bank->take($payout);
		$ug = $user->give($payout);

		// if that all went smooth
		if ($bg && $ut && $bt && $ug) {
			$response = $slotString . "\r\n" . "You get :bone:" . $payout . " in return. You have :bone:" .$user->bones . ".";
			return $response;
		} else {
			return "sorry, i fricked up";
		}
		
		

	}



/**
  * getRandomWeightedElement()
  * Utility function for getting random values with weighting.
  * Pass in an associative array, such as array('A'=>5, 'B'=>45, 'C'=>50)
  * An array like this means that "A" has a 5% chance of being selected, "B" 45%, and "C" 50%.
  * The return value is the array key, A, B, or C in this case.  Note that the values assigned
  * do not have to be percentages.  The values are simply relative to each other.  If one value
  * weight was 2, and the other weight of 1, the value with the weight of 2 has about a 66%
  * chance of being selected.  Also note that weights should be integers.
  * 
  * @param array $weightedValues
*/
function getRandomWeightedElement(array $weightedValues) {
	$rand = mt_rand(1, (int) array_sum($weightedValues));

	foreach ($weightedValues as $key => $value) {
		$rand -= $value;
		if ($rand <= 0) {
			return $key;
		}
	}
}