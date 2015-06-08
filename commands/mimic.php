<?php
	function mimic($str) {
		require_once("lib/markov.class.php");
		function get_all_words_in_file($file) {
			return explode(' ', file_get_contents($file));
		}
		 
		$file = 'data/posts.csv';
		 
		$words = get_all_words_in_file($file);
		$chain = new Chain($words);
		$newSentence = $chain->getChainOfLength($words[rand(0, sizeof($words))], 50);
		return $newSentence;
	}