<?php
class card {
	public function construct($db, $messageStack, $id) {
		$this->db = $db;
		$this->messageStack = $messageStack;
		
		$this->load($id);
	}
	
	public function load($id) {
		$result = $this->db->query('select * from '.DB_PREFIX.'cards where card_id = '.(int)$id);
		foreach($result->row as $key => $value) {
			$this->$key = $value;
		}
	}
	
	public function save($data, $id) {
		$this->db->query('update '.DB_PREFIX.'cards set `game_id` = "'.(int)$data['game_id'].'", `description` = "'.$this->db->escape($data['description']).'", `image` = "'.$this->db->escape($data['image']).'", `card_type` = "'.$data['card_type'].'", `restrict` = '.(int)$data['restrict'].' where card_id = '.(int)$id);
	}
	
	public function add($data) {
		$this->db->query('insert into '.DB_PREFIX.'cards set `game_id` = "'.(int)$data['game_id'].'", `description` = "'.$this->db->escape($data['description']).'", `image` = "'.$this->db->escape($data['image']).'", `card_type` = "'.$data['card_type'].'", `restrict` = '.(int)$data['restrict']);
		return $this->db->getLastId();
	}
	
	public function delete($id) {
		$this->db->query('delete from '.DB_PREFIX.'cards where `card_id` = '.(int)$id);
		return true;
	}
}