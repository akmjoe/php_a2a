<?php
class base {
	public function __construct() {
		$this->db_connect();
		$this->load_config();
		require_once DIR_INCLUDES.'messagestack.php';
		$this->messageStack = new messageStack($this->db);
		require_once  DIR_INCLUDES . 'admin.php';
		$this->admin = new admin($this->db, $this->messageStack);
	}
	
	public function db_connect() {
		require_once DIR_INCLUDES.'mysqli.php';
		$this->db = new DB\MySQLi(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
	}
	
	public function load_config() {
		$result = $this->db->query('select * from '.DB_PREFIX.'config');
		foreach($result->rows as $row) {
			define($row['key'], $row['value']);
		}
	}
	
	public function redirect($page = '', $parameters = '') {
		header('location: index.php?'.($page?'page='.$page.'&':'').$parameters);
	}
}