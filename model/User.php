<?php 
namespace model;

use engine\core\Model;

class User extends Model {
    public $table = 'user';

    public function autoLogin() {
        $username = \Tea::app()->request->post()->username;	
        $password = \Tea::app()->request->post()->password;

        if (!$this->validator($username, $password)) {
            $rs = \Tea::db()->prepare('select * from {user} where username =? limit 1')->bind([$username])->execute();
            if ($rs->row) {
                $_SESSION['id'] = $rs->row['user_id'];
                return true;
            }
        }
        return false;
    }

    public function autoLogout() {
        unset($_SESSION['id']);
        return true;
    }

    public function validator($username, $password) {
        if (empty($username) || empty($password)) {
            return true;
        }
        return false;
    }
}