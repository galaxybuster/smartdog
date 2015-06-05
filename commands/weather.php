<?php
	function weather($str) {
		$searchstr = str_replace(" ", "%20", $str);

		$url = "http://api.openweathermap.org/data/2.5/weather?q=".$searchstr."&units=metric";

		//  Initiate curl
		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		curl_setopt($ch, CURLOPT_URL,$url);
		// Execute
		$data=curl_exec($ch);
		// Closing
		curl_close($ch);

		$data = json_decode($data, true);

		if ($data["cod"] == "404") {
			$response = "i ran and ran but i couldn't find ".$str;
		} else {
			$dC = round($data["main"]["temp"]);
			$dF = round($dC * 9/5 + 32);

			$response = "it was hard to run all the way over there...\r\n";
			$response.= "weather in ".$data["name"].": \r\n";
			$response.= $data["weather"][0]["description"].", ".$dC."C/".$dF."F";

			
		}
		return $response;
	}