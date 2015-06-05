<?php
	require_once("lib/config.class.php");

	$response = "";
	$err = "";
	if (isset($_POST['token'])) {
		if ($_POST['token'] == Config::get('slackToken')) {
			// Request came from braingale slack team

			// Get the keyword off
			$commandList = explode(" ", $_POST['text'], 3);
			array_shift($commandList); // This returns the webhook keyword
			// Our array is now two elements:
			// $commandList[0]: The subcommand (eg BGMP, HELP, etc)
			// $commandList[1]: The rest of the string (sometimes non-existant)

			// At this point, commandList[0] should be the unique command and commandList[1] should be the remaining text
			switch ($commandList[0]) {
				case "achewood": {
					include("commands/achewood.php");
					$response = achewood($commandList[1], $_POST['user_name'], $_POST['user_id']);
				}
				break;

				case "balance": {
					include("commands/balance.php");
					$response = balance($commandList[1], $_POST['user_name'], $_POST['user_id']);
				}
				break;

				case "bgmp": {
					include("commands/bgmpSlack.php");
					$response = bgmpSlack($commandList[1], $_POST['user_name'], $_POST['user_id']);
				}
				break;

				case "encourage": {
					include("commands/encourage.php");
					$response = encourage($commandList[1], $_POST['user_name']);
				}
				break;

				case "give": {
					include("commands/give.php");
					$response = give($commandList[1], $_POST['user_name'], $_POST['user_id']);
				}
				break;

				case "help": {
					include("commands/help.php");
					$response = help($commandList[1]);
				}
				break;

				case "iotd": {
					include("commands/imageoftheday.php");
					$response = imageoftheday($commandList[1]);
				}
				break;

				case "register": {
					include("commands/register.php");
					$response = register($commandList[1], $_POST['user_name'], $_POST['user_id']);
				}
				break;

				case "roll": {
					include("commands/roll.php");
					$response = roll($commandList[1]);
				}
				break;

				case "slots": {
					include("commands/slots.php");
					$response = slots($commandList[1], $_POST['user_name'], $_POST['user_id']);
				}
				break;

				case "swack": {
					include("commands/swack.php");
					$response = swackText($commandList[1]);
				}
				break;				

				case "weather": {
					include("commands/weather.php");
					$response = weather($commandList[1]);
				}
				break;

				case "wiki": {
					include("commands/wiki.php");
					$response = wiki($commandList[1]);
				}
				break;

				default: {
					$response = "i-i don't understand \"" . $commandList[0] . " " . $commandList[1] . "\"... maybe you should train me...";
				}
				break;
			}



		} else {
			$err = "Token invalid.";
		}
	} else {
		echo "No token specified.";
	}

	$r = array("text"=>$response."\r\n".$err);
	echo json_encode($r);