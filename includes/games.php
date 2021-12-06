<?php
class games {
	public function __construct($db, $messageStack, $admin) {
		$this->db = $db;
		$this->messageStack = $messageStack;
		$this->admin = $admin;
	}
	
	public function game_list($filter) {
		$sql = 'select * from '.DB_PREFIX.'games';
		$where = array();
		if(!IS_ADMIN) {
			// get user permissions
			$user = $this->admin->get($_SESSION['user']);
			if(strlen($user['permission']['restrict'])) {
				$where[] = '`restrict` in(0,'.$user['permission']['restrict'].')';
			}
		}
		foreach($filter as $key => $value) {
			$where[] = '`'.$key.'` = "'.$this->db->query($value).'"';
		}
		if(count($where)) {
			$sql .= ' where '.implode(' and ', $where);
		}
		$result = $this->db->query($sql);
		return $result->rows;
	}
}