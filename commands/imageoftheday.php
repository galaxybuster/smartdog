<?php
	function imageoftheday() {
		$url = "http://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1&mkt=en-US";

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

		$response = "http://bing.com".$data["images"][0]["url"];

		return $response;
	}