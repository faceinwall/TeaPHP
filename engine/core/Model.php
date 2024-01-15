<?php 
namespace engine\core;

use engine\core\Engine;
use engine\db\Medoo;
use engine\util\Config;

class Model extends Engine {
	/**
	 * @var string 表名
	 */
	protected $table = '';

	/**
	 * @var string 前缀
	 */
	protected $table_prefix = '';

	/**
	 * @var pdo 
	 */
	protected $db = null;

	/**
	 * @var string 主键
	 */
	protected $primaryKey = 'id';

	/**
	 * 若表名为空, 默认为类名
	 * @access public
	 */
	public function __construct() {
	}

	/**
	 * 保存到数据库
	 */
	public function save() {
	}

	public function query($sql) {
	}
}
 ?>