<?php

namespace Telenova\Classes;

class Admin extends BaseUser {

	protected $password;


	public function flush() {
		$db = $this->db;
		$info = array(
				'username'=>$this->username,
				'admin'=>$this->admin,
		if(!empty($this->id)) {
			if(!is_object($this->id)) $this->id = new MongoId($this->id);
			$crit = array('_id'=>$id);
			try {
				$db->update('users',$crit,array('$set'=>$info));
			} catch(Exception $e) {
				echo $e->getMessage();
			}
		} else {
			$db->insert('users',$info);
		}
				
	}

	public function checkLogin($username, $password) {
		$db = $this->db;

		try {
			$result = $db->count('users', array('username'=>$username,'password'=>md5($password)));
		} catch (Exception $e) {
			return $e->getMessage();
		}

		if($result != 1) {
			return false;
		} else {
			$user = new self();
			$user->loadByUsername($username);
			return $user;
		}
	}

}

?>
