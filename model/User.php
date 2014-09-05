<?php 
namespace model;

class User {
	public $tablename = 'tm_user';

	public function autoLogin() {
		$username = \Tea::app()->request->post()->username;	
		$password = \Tea::app()->request->post()->password;

		if (!$this->validator($username, $password)) {
			$row = @\Tea::model($this->tablename)
				->where(array('name' => $username, 'pass'=>md5($password)))
				->one();
			if ($row) {
				$_SESSION['id'] = $row['id'];
				return true;
			}
		}
		return false;
	}

	public function autoLogout() {
		unset($_SESSION['id']);
		//TO do somethin here
		
		return true;
	}

	public function validator($username, $password) {
		if (empty($username) || empty($password)) {
			return true;
		}
		return false;
	}
}
 ?>