<?php
require_once DIR_INCLUDES.'base.php';
class play extends base {
	public $template;
	public function __construct() {
		parent::__construct();
		// check if session active
		if(!$_SESSION['user']) {
			// load login page
			$this->login();
			return;
		}
		// handle the request
		$this->request();
	}
	
	public function login() {
		$template = 'login.php';
		if($_POST['login'] && $this->admin->login($_POST['email'], $_POST['password'])) {
			$this->redirect();
		}
	}
	/**
	 * Request handler
	 *
	 */
	public function request() {
		switch($_GET['action']) {
			
		}
	}
	
	public function begin() {
		// load possible games
		$this->games = new games();
		$this->games->game_list();
		$this->template = 'list.php';
	}
}