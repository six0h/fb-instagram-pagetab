<?php

namespace Telenova\Classes;

class BaseUser {
	
	protected	$id,
				$username,
				$admin,
				$access_token,
				$igid,
				$db;

	public function __construct() {
		$this->db = Database::getInstance();	
	}

	public function loadById(Int $id) {
		$db = $this->db;
		$result = $db->findOne('users', array('_id'=>new MongoId($id)));
		if(!$result) {
			$response = 'Could not find User';
			return new Exception('User not found',404);
		}

		foreach($result as $key=>$value) {
			if(isset($this->$key) || property_exists($this, $key)) {
				$this->$key = $value;
			}
		}

	}

	public function loadByUsername(String $username) {
		$db = $this->db;
		$result = $db->findOne('users', array('username'=>$username));
		if(!$result) {
			$response = 'Could not find User';
			return new Exception('User not found',404);
		}

		foreach($result as $key=>$value) {
			if(isset($this->$key) || property_exists($this, $key)) {
				$this->$key = $value;
			}
		}

	}

	public function flush() {
		$db = $this->db;
		$info = array('username'=>$this->username,'admin'=>$this->admin);
		if(!empty($this->id)) {
			if(!is_object($this->id)) $this->id = new MongoId($this->id);
			$crit = array('_id'=>$id);
			try {
				$db->update('users',$crit,array('$set'=>$info));
			} catch(Exception $e) {
				echo $e->getMessage();
			}
		} else {
			try {
				$db->insert('users',$info);
			} catch(Exception $e) {
				echo $e->getMessage();
			}
		}
				
	}

	public function getId() {
		return $this->id;
	}

	public function getInstagramId() {
		return $this->igid;
	}

	public function setInstagramId($id) {
		$this->igid = $id;
	}

	public function getAdmin() {
		return $this->admin;
	}

	public function setAdmin($admin) {
		$this->admin = $admin;
	}

	public function getAccessToken() {
		return $this->access_token;
	}

	public function setAccessToken($token) {
		$this->access_token = $token;
	}
	
}

?>
