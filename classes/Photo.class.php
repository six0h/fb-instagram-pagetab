<?php

namespace Telenova\Classes;

class Photo {
	
	protected $id,
			$_id,
			$low,
			$high,
			$thumb,
			$username,
			$caption,
			$type,
			$db;

	public function __construct() {
		$this->db = Database::getInstance();	
	}

	public function findAllByType($type = 'tag') {
		$db = $this->db;

		$results = array();
		$query = $db->find('photos', array('type'=>$type));

		foreach($query as $photo) {
			$this->set_Id($photo->_id);
			$this->setId($photo->id);
			$this->setLow($photo->images->low_resolution->url);
			$this->setHigh($photo->images->standard_resolution->url);
			$this->setThumb($photo->images->standard_resolution->url);
			$this->setUsername($photo->user->username);

		}
	}

	public function get_Id() {
		return $this->_id;
	}

	public function set_Id($id) {
		$this->_id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}
	
	public function getHigh() {
		return $this->getHigh;
	}

	public function setHigh($high) {
		$this->high = $high;
	}

	public function getLow() {
		return $this->getLow;
	}

	public function setLow($low) {
		$this->low = $low;
	}

	public function getThumb() {
		return $this->thumb;
	}

	public function setThumb($url) {
		$this->thumb = $url;
	}

	public function getUsername() {
		return $this->username;
	}

	public function setUsername($username) {
		$this->username = $username;
	}
	
	public function getCaption() {
		return $this->caption;
	}

	public function setCaption($caption) {
		$this->caption = $caption;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}
}

?>

