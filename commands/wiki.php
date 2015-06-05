<?php
	function wiki($str) {
		$str = str_replace(" ", "%20", $str);

		$url = "https://en.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&explaintext=&redirects=&exchars=500&titles=".$str;

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

		$key = array_keys($data["query"]["pages"]);

		$response = "i managed to dig this up...\r\n```";
		$response.= $data["query"]["pages"][$key[0]]["extract"];
		$response.= "``` ";
		$response.= "from https://en.wikipedia.org/wiki/". str_replace(" ", "_", $data["query"]["pages"][$key[0]]["title"]);


		return $response;
	}