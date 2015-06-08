<?php
	class Link {
		private $nexts = array();
		public function addNextWord($word) {
			if (!is_string($word)) {
				throw new Exception('addNextWord method in Link class is run with an string parameter');
			}
			if (!isset($this->nexts[$word])) {
				$this->nexts[$word] = 0;
			}
			$this->nexts[$word]++;
		}
		public function getNextWord() {
			$total = 0;
			foreach($this->nexts as $word => $count) {
				$total += $count;
			}
			$randomIndex = rand(1, $total);
			$total = 0;
			foreach($this->nexts as $word => $count) {
				$total += $count;
				if ($total >= $randomIndex) {
					return $word;
				}
			}
		}
	}
	 
	class Chain {
		private $words = array();
		function __construct($words) {
			if (!is_array($words)) {
				throw new Exception('Chain class is instantiated with an array');
			}
			 
			for($i = 0; $i < count($words); $i++) {
				$word = (string) $words[$i];
				if (!isset($this->words[$word])) {
					$this->words[$word] = new Link();
				}
				if (isset($words[$i + 1])) {
					$this->words[$word]->addNextWord($words[$i + 1]);
				}
			}
		}
		public function getChainOfLength($word, $i) {
			if (!is_string($word)) {
				throw new Exception('getChainOfLength method in Chain class is run with an string parameter');
			}
			if (!is_integer($i)) {
				throw new Exception('getChainOfLength method should be called with an integer');
			}
			if (!isset($this->words[$word])) {
				return '';
			} else {
				$chain = array($word);
				for ($j = 0; $j < $i; $j++) {
					$word = $this->words[$word]->getNextWord();
					$chain[] = $word;
				}
				return implode(' ', $chain);
			}
		}
	}