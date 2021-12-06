<?php
class install extends base {
	public $db_host = 'localhost';
	public $db_prefix = 'a2a_';
	public $db_port = '3306';
	public $db_user, $db_password, $db_name;
	
	public function __construct() {

		$this->messageStack = new messageStack();
		
		if(isset($_POST['db_host'])) {
			$this->db_host = $_POST['db_host'];
		}
		if(isset($_POST['db_user'])) {
			$this->db_user = $_POST['db_user'];
		}
		if(isset($_POST['db_password'])) {
			$this->db_password = $_POST['db_password'];
		}
		if(isset($_POST['db_name'])) {
			$this->db_name = $_POST['db_name'];
		}
		if(isset($_POST['db_prefix'])) {
			$this->db_prefix = $_POST['db_prefix'];
		}
		if(isset($_POST['db_port'])) {
			$this->db_port = $_POST['db_port'];
		}
		if(isset($_POST['email'])) {
			$this->email = $_POST['email'];
		}
		if(isset($_POST['password'])) {
			$this->password = $_POST['password'];
		}
	}
	
	public function save() {
		global $messageStack;
		// validate information
		if(!$this->db_host) {
			$error = $messageStack->add('You must provide a host name!');
		}
		if(!$this->db_user) {
			$error = $messageStack->add('You must provide a user name!');
		}
		if(!$this->db_password) {
			$error = $messageStack->add('You must provide a password!');
		}
		if(!$this->db_name) {
			$error = $messageStack->add('You must provide a database name!');
		}
		if(!$this->db_port) {
			$error = $messageStack->add('You must provide a port number!');
		}
		if($error) {
			return false;
		}
		try{
			define('DB_HOST',$this->db_host);
			define('DB_USER',$this->db_user);
			define('DB_PASSWORD',$this->db_password);
			define('DB_PREFIX',$this->db_prefix);
			define('DB_NAME',$this->db_name);
			define('DB_PORT',$this->db_port);
			// connect to db
			$this->db_connect();
			// create tables
			$this->create_tables();
			// initial configuration
			$this->db->query('insert into '.$this->db_prefix.'config (`key`, `value`) values ("version", "1")');
			// add admin user
			$this->admin = new admin($this->db, $this->messageStack);
			$data = array('email' => $this->email, 'password' => $this->password);
			$this->admin->add($data);
			// save config file
			$config_file = fopen('config.php', 'w');
			fwrite($config_file, '<?php'.PHP_EOL);
			fwrite($config_file, "define('DIR_ROOT','".getcwd()."/');".PHP_EOL);
			fwrite($config_file, "define('DIR_ADMIN',DIR_ROOT.'admin/');".PHP_EOL);
			fwrite($config_file, "define('DIR_INCLUDES',DIR_ROOT.'includes/');".PHP_EOL);
			fwrite($config_file, "define('DIR_IMAGE',DIR_ROOT.'image/');".PHP_EOL);
			fwrite($config_file, "define('DIR_TEMPLATES',DIR_ROOT.'templates/');".PHP_EOL);
			fwrite($config_file, "define('DB_HOST','".$this->db_host."');".PHP_EOL);
			fwrite($config_file, "define('DB_USER','".$this->db_user."');".PHP_EOL);
			fwrite($config_file, "define('DB_PASSWORD','".$this->db_password."');".PHP_EOL);
			fwrite($config_file, "define('DB_PREFIX','".$this->db_prefix."');".PHP_EOL);
			fwrite($config_file, "define('DB_NAME','".$this->db_name."');".PHP_EOL);
			fwrite($config_file, "define('DB_PORT','".$this->db_port."');".PHP_EOL);
			fwrite($config_file, "define('IS_ADMIN', false);".PHP_EOL);
			fclose($config_file);
			// save admin config (loads standard config)
			$config_file = fopen(DIR_ADMIN.'config.php', 'w');
			fwrite($config_file, '<?php'.PHP_EOL);
			fwrite($config_file, "define('IS_ADMIN', true);".PHP_EOL);
			fwrite($config_file, 'require_once "'.DIR_ROOT.'config.php";');
			fclose($config_file);
			
		} catch(Exception $e) {
			$messageStack->add($e->getMessage);
			return false;
		}
	}
	
	protected function create_tables() {
		global $db;
		$this->db->query('DROP TABLE IF EXISTS '.$this->db_prefix.'config');
		$this->db->query('create table '.$this->db_prefix.'config (
					 `key` varchar(128) NOT NULL,
					 `value` text NOT NULL,
					 PRIMARY KEY (`key`)
					 ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci');
		$this->db->query('DROP TABLE IF EXISTS '.$this->db_prefix.'restrict');
		$this->db->query('create table '.$this->db_prefix.'restrict (
					 `restrict_id` int(11) NOT NULL AUTO_INCREMENT,
					 `name` text NOT NULL,
					 PRIMARY KEY (`restrict_id`)
					 ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci');
		$this->db->query('DROP TABLE IF EXISTS '.$this->db_prefix.'games');
		$this->db->query('create table '.$this->db_prefix.'games (
					 `game_id` int(11) NOT NULL AUTO_INCREMENT,
					 `name` text NOT NULL,
					 `description` text NOT NULL,
					 `defaults` text NOT NULL,
					 `restrict` int(11) NOT NULL default 0,
					 PRIMARY KEY (`game_id`)
					 ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci');
		$this->db->query('DROP TABLE IF EXISTS '.$this->db_prefix.'cards');
		$this->db->query('create table '.$this->db_prefix.'cards (
					 `card_id` int(11) NOT NULL AUTO_INCREMENT,
					 `game_id` int(11) NOT NULL,
					 `description` text NOT NULL,
					 `image` varchar(255) NOT NULL DEFAULT "",
					 `card_type` enum("p","m") NOT NULL DEFAULT "p",
					 `restrict` int(11) NOT NULL DEFAULT 0,
					 PRIMARY KEY (`card_id`)
					 ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci');
		$this->db->query('DROP TABLE IF EXISTS '.$this->db_prefix.'users');
		$this->db->query('create table '.$this->db_prefix.'users (
					 `user_id` int(11) NOT NULL AUTO_INCREMENT,
					 `name` text NOT NULL,
					 `email` varchar(96) NOT NULL,
					 `password` varchar(40) NOT NULL,
					 `permissions` text NOT NULL,
					 PRIMARY KEY (`user_id`)
					 ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci');
	}
}