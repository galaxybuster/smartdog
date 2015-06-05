<?php
	function encourage($str, $sender) {
		if (strcmp($str, "me") == 0) {
			$str = $sender;
		}
		
		$list = array(
			"you're the best, ".$str,
			"yo ".$str." there are 7 billion people, and a lot of dogs, and you're easy top 1 million",
			"some days you just feel like the runt tryin to get on momma's teet. keep pushin, ".$str.", you'll get that sweet sweet milk soon.",
			"do you all even realize how often i tell my dogfriends how cool ".$str." is",
			"i would love ".$str." even if they had no food",
			"dog's dont talk, but i do, and i say: ".$str." is just the   best ;_;7",
			"You're not worthless,".$str."! Your organs alone would fetch a fortune on the black market!"
		);
		$response = $list[rand(0, sizeof($list))];
		return $response;
	}