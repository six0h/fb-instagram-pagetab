<?php

class Database {

	private	$m = '',
			$db_name,
			$last_id;

	protected static $instance;

	protected function __construct() {

        $uri = "mongodb://" . DB_USER . ":" . DB_PASS . "@localhost/" . DB_NAME; 
		$this->m = new Mongo($uri);	
		$m = $this->m;
		$db_name = DB_NAME;
		$this->db = $m->$db_name;

	}

	public static function getInstance() {
		if(!self::$instance) {
			self::$instance = new Database();
		}
		
		return self::$instance;
	}

	public function find($collection,$crit = array(),$options = array()) {
		if(isset($options['offset'])) {
			$offset = $options['offset'];
		} else {
			$offset = 0;
		}

		$record = $this->db->$collection->find($crit);
		$record->skip($offset);
		if(isset($options['sort'])) $record->sort($options['sort']);
		if(isset($options['limit'])) $record->limit($options['limit']);
		return $record;
	}

	public function findOne($collection,$crit = array()) {
		$record = $this->db->$collection->findOne($crit);
		return $record;
	}

	public function count($collection,$crit) {
		$count = $this->db->$collection->count($crit);
		return $count;
	}

	public function insert($collection,$data) {
		$this->db->$collection->insert($data);
	}

	public function update($collection,$crit,$data) {
		$this->db->$collection->update($crit,$data);
	}

	public function remove($collection,Array $crit) {
		$this->db->$collection->remove($crit);
	}

	public function getLastId() {
		return $this->last_id;
	}

}

?>
