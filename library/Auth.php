<?php 
namespace library;

use \engine\net\Request;
use \engine\util\DbBuilder;

class Auth {
	private static $db;

	public static function powerCheck($action) {
		$request = new Request();
		return $request->get()->r;
	}

	public static function getAccessRules() {
		return array();
	}

	public static function getDb() {
		if (self::$db == null) {
		}
	}
}
 ?>