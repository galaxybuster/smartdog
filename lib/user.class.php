<?php
	require_once(__DIR__ . "/config.class.php");
	require_once(__DIR__ . "/database.class.php");

	class User {

		public $userid = null,
			   $username = "",
			   $isRegistered = false,
			   $bones = 0;

		private $db;

		private $userInfoRow;
		
		public function __construct($uid) {
			// Check if user is registered on constructor
			$this->db = Database::getInstance();
			$this->db->query("SELECT * FROM userinfo WHERE userID=?", array($uid));
			if ($this->db->firstResult() != null) {
				// store user row and some other info from it
				$this->userInfoRow = $this->db->firstResult();

				$this->isRegistered = true;

				$this->userid = $this->userInfoRow['userID'];
				$this->username = $this->userInfoRow['username'];
				$this->bones = $this->userInfoRow['money'];
			} else {
				$this->isRegistered = false;
			}
		}

		// Increases the amount of bones of the user by $amount
		// Returns true on success.
		public function give($amount) {
			$this->refreshBalance();

			$db = Database::getInstance();
			$val = $this->bones + $amount;
			$db->query("UPDATE userinfo SET money=? WHERE userID=?", array($val, $this->userid));
			if (!$db->error()) {
				$this->refreshBalance();
				return true;
			} else {
				$this->refreshBalance();
				return false;
			}
		}


		// Decreases the amount of bones of the user by $amount.
		// Returns true on success.
		public function take($amount) {
			$this->refreshBalance();

			$db = Database::getInstance();

			if ($this->bones >= $amount) {
				// good to update
				$val = $this->bones - $amount;
				$db->query("UPDATE userinfo SET money=? WHERE userID=?", array($val, $this->userid));
				if (!$db->error()) {
					$this->refreshBalance();
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}


		// Refreshes the user's money amount by re-reading the database.
		// returns true on success.
		public function refreshBalance() {
			$db = Database::getInstance();
			$db->query("SELECT money FROM userinfo WHERE userID=?", array($this->userid));
			$r = $db->firstResult();
			if ($r != null) {
				// update this instance's money
				$this->bones = $r['money'];
				return true;
			} else {
				return false;
			}
		}



		// If we dont have the user's ID, but we do have their name, we can look them up
		// Aliases will need to take this into account
		public static function getUserByName($username) {
			$db = Database::getInstance();
			$db->query("SELECT * from userinfo WHERE username=?", array($username));
			$r = $db->firstResult();
			if ($r != null) {
				return new User($r['userID']);
			} else {
				return false;
			}
		}
	}
?>