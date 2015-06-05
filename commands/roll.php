<?php
	function roll($str) {
		// $str looks something like d5, d10, d[integer]
		// make sure it starts with d

		$response = "";

		if (substr($str, 0, 1) == "d") {
			$intStr = substr($str, 1); // This returns everything after 'd'
			$int = intval($intStr);

			$response = "i rolled a " . rand(1, $int) . "!";
		} else {
			$response = "please, tell me \"d[number]\"!";
		}

		return $response;
	}