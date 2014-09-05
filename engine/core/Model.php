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

	protected $table_prefix = '';

	protected $db = null;

	/**
	 * 若表名为空, 默认为类名
	 * @access public
	 * @param string $table 表名
	 *
	 */
	public function __construct($table = '') {
		//db config
		$config = $this->getConfig();

		if (empty($table)) {
			//get class model name
			$classname = end((explode('\\', get_class($this))));

			$this->table = $config['table_prefix']? 
				$config['table_prefix'].strtolower($classname) :
				strtolower($classname);
		}

		unset($config['table_prefix']);
		$this->db = new Medoo($config);
	}

	/**
	 * 获取数据库的配置信息
	 * @access private
	 * @return array
	 */
	private function getConfig() {
		$config = new Config();

		return array(
				'table_prefix'  => $config->table_prefix,
				'database_type' => $config->mdbtype,
				'database_name' => $config->database,
				'server'        => $config->hostname,
				'username'      => $config->username,
				'password'      => $config->password,
			);
	}

	/** 默认必须要实现的方法 **/
	// abstract public function validator();
	// abstract public function save();
}
 ?>