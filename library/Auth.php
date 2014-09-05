<?php 
namespace library;

use \engine\net\Request;
use \engine\util\DbBuilder;

class Auth {
	/**
	 * @var array 权限认证的表 默认
	 */
	protected $tables = array(
			'auth_on'           => true,
			'auth_type'         => 1,
			'auth_group'        => 'auth_group',
			'auth_group_access' => 'auth_group_access',
			'auth_rule'         => 'auth_rule',
			'auth_user'         => 'user',
		);

	/**
	 * @var obj 数据操作元
	 */
	protected static $db = null;


	public function __construct(array $config = array()) {
		//重写认证表
		if (!empty($config)) {
			foreach ($config as $key => $value) {
				if (array_key_exists($key, $this->tables)) {
					$this->tables[$key] = $value;
				}
			}
		}

		//如果有表前缀
		$config = new \engine\util\Config();
		$prefix = $config->table_prefix;

		$this->tables['auth_group'] = $prefix .$this->tables['auth_group'];
		$this->tables['auth_type']  = $prefix .$this->tables['auth_type'];
		$this->tables['auth_rule']  = $prefix .$this->tables['auth_rule'];
		$this->tables['auth_user']  = $prefix .$this->tables['auth_user'];
	}

	
	public static function powerCheck(){return true;}
	public static function check($ruleName, $uid, $type = 1) {
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