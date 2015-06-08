<?php
	/*
	Daily tasks to be run
	This script is run every day at 10am server time.

	Things it should do...
	
	-reset daily reward to false for all users
	-delete swack images older than 1 day
	-say goodmorning to all the crew
		-if its a holiday say holla
		-check if user's birthday?
	-instate a bonus for the day on some condition
	*/

	require_once("lib/config.class.php");
	require_once("lib/database.class.php");

	// Give a little greeting
	$greetingsList = array(
		"goodmorning!",
		"goodmorning braingale!",
		"hello my companions..!",
		"i-i'm not doin so hot today...",
		"_*sigh*_...",
		"i'm so happy to be alive!!",
		"you'll gamble too much!",
		"how is everyone today?",
		"i'm so glad to be here with you all!",
		"today is gonna be great",
		"you know i'm just not feelin it today",
		"it's a so-good day",
		"you :boutit:? im :boutit::boutit:",
		"i wanna learn some new tricks today!",
		"i can't wait to learn some new things",
		"i'm so glad to be your friend",
		"it's today!",
		"you all can POUND SAND"
	);
	$greeting = $greetingsList[rand(0, sizeof($greetingsList))];
	$text = $greeting."\r\n";

	// Reset daily allowance
	$dailyClaim = 1;
	$db->query("UPDATE `userinfo` SET `dailyreward`=0");
	if ($db->error()) {
		$text .= "i uh couldn't reset your daily allowances, sorry\r\n";
	} else {
		$text .= "make sure to get your daily allowance with `smartdog claim`, i'm giving :bone:".$dailyClaim." today\r\n";
	}

	//place cleanup script here for daily clean up of image folder
	$filesCleaned = cleanup(60*60*24); // 1 day
	if ($filesCleaned > 0) {
		$text .= "i cleaned up ".$filesCleaned." old ".ngettext("file", "files", $filesCleaned).", i hope that's ok...\r\n";
	}

	// check if holiday say happyday
	$holiday = holiday(date("Y-m-d"));
	if ($holiday != false) {
		$text .= "oh and happy ".$holiday." by the way\r\n";
	}

	// check if bonus say theres a bonus
	$bonus = false;
	if ($bonus != false) {
		$text .= "also, i'm givin extra bones for BGMP today...";
	}


	// this is the part that actually posts to slack
	$payload = array("channel"=>"#general", "text"=>$text);
	$fieldsAsString = "payload=".json_encode($payload);

	// Initiate curl
	$ch = curl_init();
	// Disable SSL verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// configure as POST
	curl_setopt($ch, CURLOPT_POST, 1);
	// give it the post options
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsAsString);
	// Set the url
	curl_setopt($ch, CURLOPT_URL, Config::get('slackIncomingURL')."?".$fieldsAsString);
	// Execute
	$data = curl_exec($ch);
	// Closing
	curl_close($ch);

	echo $fieldsAsString;



	// Utility functions for this script below.

	// Determines if today is a holiday (defined in-script)
	function holiday($date) {    
		$year = substr($date, 0, 4); 

		switch($date) {
			case $year.'-01-01':
				$holiday = 'New Year\'s Day';
			break;
			case date("Y-m-d", strtotime("-2 days", (easter_date($year)))):
				$holiday = 'Good Friday';
			break;
			case date("Y-m-d", strtotime(easter_date($year))):
				$holiday = 'Easter Sunday';
			break;
			case $year.'-06-05':
				$holiday = 'Smartdog Day';
			break;
			case $year.'-07-04':
				$holiday = 'US Independence Day';
			break;
			case date("Y-m-d", strtotime($year.'-09-00, first monday')):
				$holiday = 'Labour Day';
			break;
			case date("Y-m-d", strtotime($year.'-11-00, fourth thursday')):
				$holiday = 'Thanksgiving Day';
			break;
			case $year.'-12-24':
				$holiday = 'Christmas Eve';
			break;
			case $year.'-12-25':
				$holiday = 'Christmas';
			break;
			case $year.'-12-31':
				$holiday = 'New Year\'s Eve';
			break;
			default:
				$holiday = false;
			break;
		}

		return $holiday;
	}

	// Cleans up temp files older than a period of time (in seconds)
	// namely those created by commands/swack.php
	// Returns number of files deleted
	function cleanup($period) {
		chdir("img");
		$files = glob("*.png");
		$now = time();

		$i = 0;
		foreach ($files as $file) {
			if (is_file($file)) {
				if ($now - filemtime($file) >= $period) {
					if (unlink($file)) {
						$i++;
					}
				}
			}
		}

		return $i;
	}
