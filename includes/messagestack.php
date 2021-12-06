<?php
class messageStack {
	private $messages = array();
	private $debug_text;
	protected $db;
	
	public function __construct($db=null) {
		$this->db = $db;
	}
	
	public function add($message, $level = 'error') {
		$this->messages[] = array($message, $level);
	}
	
	public function output() {
		foreach($this->messages as $message) {
			echo '<div class="error_'.$message[1].'">'.$message[0].'</div>';
		}
	}
	
	public function debug($message) {
		$this->debug_text .= $message . PHP_EOL;
	}
	
	public function write_debug() {
		file_put_contents(DIR_ROOT.'error.log',$this->debug_text);
	}
}