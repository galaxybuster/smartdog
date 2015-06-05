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
	}
?>