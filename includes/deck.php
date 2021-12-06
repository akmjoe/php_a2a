<?php
require_once DIR_INCLUDES.'card.php';

class deck {
	protected $stack;
	protected $discards;
	protected $hands;
	public $played;
	
	public function __construct($db, $messageStack) {
		$this->db = $db;
		$this->messageStack = $messageStack;
	}
	/**
	 * Shuffle the deck
	 */
	public function shuffle() {
		$this->stack = shuffle($this->stack);
	}
	/**
	 * Get number of cards in deck
	 * @param int 0 = all, 1 = stack, 2 = discards, 3 = in play
	 * @return int number of cards
	 */
	public function cardcount($type = 0) {
		switch($type) {
			case 1:
				return count($this->stack);
			break;
			case 2:
				return count($this->discards);
			break;
			case 3:
				return count($this->hands);
			break;
			case 0:
				return count($this->stack)+count($this->discards)+count($this->hands);
			break;
		}
	}
	/**
	 * Deal 1 card off the deck
	 * @return card object
	 */
	public function deal() {
		if(!count($this->stack)) {
			// no cards left - recycle discards
			if(!count($this->discards)) {
				// no discards - error
				$this->messageStack->add('Out of cards!');
				return false;
			}
			$this->stack = $this->discards;
			$this->discards = array();
			// re-shuffle
			$this->shuffle();
		}
		$card = array_pop($this->stack);
		$this->hands[] = $card;
		return new card($this->db, $this->messageStack, $card);
	}
	
	public function load($data, $type = 'p') {
		$type = substr($type,0,1);
		$where = array('`card_type` = "'.$this->db->escape($type).'"');
		if(isset($data['game_id'])) {
			$where[] = '`game_id` = '.(int)$data['game_id'];
		}
		if(isset($data['restrict'])) {
			$where[] = '`restrict` in ('.$data['restrict'].')';
		}
		$sql = 'select * from '.DB_PREFIX.'cards';
		if(count($where)) {
			$sql .= ' where '.implode(" and ", $where);
		}
		$result = $this->db->query($where);
		foreach($result->rows as $row) {
			if($row['description'] || $this->validate_image($row['image'])) {
				$this->stack[] = $row['card_id'];
			}
		}
		$this->shuffle();
		return $this->cardcount();
	}
	
	protected function validate_image($image) {
		if(file_exists(DIR_IMAGE.$image) && @is_array(getimagesize(DIR_IMAGE.$image))) {
			return true;
		} else {
			return false;
		}
	}
}