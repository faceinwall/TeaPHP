<?php 
namespace model;
use \engine\core\Model;

class Menu extends Model {

	public function fetch() {
		return $this->db->select($this->table, array('name', 'url'));
	}

	public function getMenus() {
		return array();
	}
}
 ?>