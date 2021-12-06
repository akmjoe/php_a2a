<?php
class admin {
	protected $db, $messageStack;
	
	public function __construct($db, $messageStack) {
		$this->db = $db;
		$this->messageStack = $messageStack;
	}
	
	public function add($data) {
		$this->db->query('insert into '.DB_PREFIX.'users (`name`,`email`,`password`,`permissions`) values ("'.$this->db->escape($data['name']).'", "'.$this->db->escape($data['email']).'", "'.$this->encode($data['password']).'","'.$this->db->escape(serialize($data['permissions'])).'"');
	}
	
	public function save($data, $id) {
		$this->db->query('update '.DB_PREFIX.'users set `name` = "'.$this->db->escape($data['name']).'", `email` = "'.$this->db->escape($data['email']).'", `permissions` = "'.$this->db->escape(serialize($data['permissions'])).'" where id = '.(int)$id);
		$this->db->query('update '.DB_PREFIX.'users set `password` = "'.$this->encode($data['password']).'" where user_id = '.(int)$id);
	}
	
	public function get($id) {
		$result = $this->db->query('select * from '.DB_PREFIX.'users where id = '.(int)$id);
		unset($result->row['password']);
		$result->row['permission'] = unserialize($result->row['permission']);
		return $result->row;
	}
	
	public function login($email, $password) {
		$result = $this->db->query('select user_id, password from '.DB_PREFIX.'users where email = "'.$this->db->escape($email).'"');
		if($result->num_rows && password_verify($password, $result->row['password'])) {
			setcookie('user', $result->rows['user_id'], 3600);
			$_SESSION['user'] = $result->rows['user_id'];
			return true;
		}
		return false;
	}
	
	public function encode($password) {
		return password_hash($password);
	}
}