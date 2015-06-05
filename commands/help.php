<?php
	function help($str) {
		$reponse = "";

		switch ($str) {
			case "achewood":
				$response = "`achewood [terms]`: im gonna search for an achewood comic like that\r\n";
			break;

			case "balance":
				$response = "`balance`: check how much bones you got\r\n";
			break;

			case "bgmp":
				$response = "`bgmp [youtube]`: adds the youtube url to BGMP.\r\n";
			break;

			case "encourage":
				$response = "`encourage [someone]`: i'll say a kind word to someone.\r\n";
			break;

			case "give":
				$response = "`give [someone] [number]`: give someone some bones\r\n";
			break;

			case "register":
				$response = "`register`: i'm gonna make sure to keep track of you!\r\n";
			break;

			case "roll":
				$response = "`roll [dN]`: i roll an N-sided dice\r\n";
			break;

			case "slots":
				$response = "`slots [# of bones]`: test your luck to win it all. (use #smartdog to not be a bother...)\r\n";
			break;

			case "swack":
				$response = "`swack [text]`: swack's up your text\r\n";
			break;

			case "weather":
				$response = "`weather [city]`: im gonna run and find out the weather in that city\r\n(if you put a country code i wont get confused)";
			break;

			case "wiki":
				$response = "`wiki [subject]`: fetches (heh) the wikipedia link for that topic\r\n";
			break;

			default:
				$response = "here are the tricks i know:\r\n`achewood` `balance` `bgmp` `encourage` `give` `register` `roll` `slots` `swack` `weather` `wiki`\r\nuse `smartdog help [command]` to find more information.";
			break;
		}

		return $response;

	}