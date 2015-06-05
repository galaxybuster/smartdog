<?php
	function achewood($str, $sender, $senderId) {
		include("lib/simple_html_dom.php");

		$search = str_replace(" ", "+", $str);
		$url = "http://www.ohnorobot.com/index.pl?comic=636&s=".$search."&search=Find";

		//  Initiate curl
		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		curl_setopt($ch, CURLOPT_URL,$url);
		// Execute
		$data = curl_exec($ch);
		// Closing
		curl_close($ch);


		$response = "";
		$d = str_get_html($data);
		foreach ($d->find("a.searchlink") as $e) {
			$response .= str_replace("index.php", "comic.php", $e->href);
			$response .= "\r\n".$e->plaintext;
			break;
		}

		if (!$response) {
			$response = "i couldn't find it......";
		}

		//$response = str_replace("index.php", "comic.php", $response);

		return $response;
	}