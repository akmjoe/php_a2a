<?php
class game {
	public function __construct($db, $messageStack, $admin) {
		$this->db = $db;
		$this->messageStack = $messageStack;
		$this->admin = $admin;
	}
	
	public function load($id) {
		$result = $this->db->query('select * from '.DB_PREFIX.'games where game_id = '.(int)$id);
		foreach($result->row as $key => $value) {
			$this->$key = $value;
		}
	}
	
	public function start() {
		
	}
}