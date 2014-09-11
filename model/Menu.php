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

	public function total(array $condition) {
		$where = isset($condition['where']) ? $condition['where'] : array();

		return \Tea::app()->model($this->table)
			->where($where)
			->count();
	}

	/**
	 * $where = array(
	 *  'order' => array('name', 'desc'),
	 *  'limit' => array('1', '20'),
	 *  'where' => array(),
	 * )
	 */
	public function pageList(array $condition) {

		$order = isset($condition['order'][0]) ? $condition['order'][0]: null;
		$byway = isset($condition['order'][1]) ? $condition['order'][1]: null;
		$where = isset($condition['where']) ? $condition['where']: array();
		$limit = $condition['limit'];

		if ($order && $byway) {
			return \Tea::app()->model($this->table)
				->where($where)
				->orderBy($order, $byway)
				->limit($limit[1])
				->offset($limit[0])
				->many();
		} else {
			return \Tea::app()->model($this->table)
				->where($where)
				->limit($limit[1])
				->offset($limit[0])
				->many();
		}
	}
}
 ?>