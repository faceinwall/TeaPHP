<?php 
namespace library;

use \engine\db\Medoo;

class Auth {
	/**
	 * @var boolean 认证开关
	 */
	protected $authOn = true;

	/**
	 * @var int 认证模式
	 */
	protected $authType = 1;

	/**
	 * @var array 权限认证的表 默认
	 */
	protected $tables = array(
			'auth_group'        => 'auth_group',
			'auth_group_access' => 'auth_group_access',
			'auth_rule'         => 'auth_rule',
			'auth_user'         => 'user',
		);

	/**
	 * @var obj 数据操作元
	 */
	protected static $db = null;

	/**
	 * @var object 文件缓存
	 */
	protected static $fc = null;


	/**
	 * 构造函数
	 * @param array $config 配置数组
	 */
	public function __construct(array $config = array()) {
		if (!empty($config)) {
			foreach ($config as $key => $value) {
				//重写认证表
				if (array_key_exists($key, $this->tables)) {
					$this->tables[$key] = $value;
				}
				else if (isset($this->$key)) {
					//重写属性
					$this->$key = $value;
				}
			}
		}

		//如果有表前缀
		$config = new \engine\util\Config();
		$prefix = $config->table_prefix;

		$this->tables['auth_group']        = $prefix .$this->tables['auth_group'];
		$this->tables['auth_rule']         = $prefix .$this->tables['auth_rule'];
		$this->tables['auth_user']         = $prefix .$this->tables['auth_user'];
		$this->tables['auth_group_access'] = $prefix .$this->tables['auth_group_access'];
	}

	
	public static function powerCheck(){return true;}


	/**
	 * 权限检查
	 * @param string|array $ruleName 规则集 rule1,rule2,... OR array(rule1, rule2, rule3,...)
	 * @param int $uid 用户id
	 * @param int $type 认证类型
	 * @param string $mode 认证方式
	 * @param string $relation 关系 or|and
	 * @return boolean
	 */
	public function check($ruleName, $uid, $type = 1, $mode = 'url', $relation = 'or') {
		// 若认证关闭
		if (!$this->authOn) {
			return true;
		}

		$accessRulesList = $this->getAccessRules($uid, $type);

		if (is_string($ruleName)) {
			$ruleName = strtolower($ruleName);
			//含逗号分隔的规列表
			if (strpos($ruleName, ',') !== false) {
				$ruleName = explode(',', $ruleName);	
			} else {
				//只有一个情况下
				$ruleName = array($ruleName);
			}
		}

		//保存验证通过的规则名
		$savedAccessRulesList = array();
		if ($mode == 'url') {
			$REQUEST = unserialize(strtolower(serialize($_REQUEST)));
		}
		foreach ($accessRulesList as $rule) {
			$query = preg_replace('/^.+\?/U', '', $rule);
			if ($mode == 'url' && $query != $rule){
				//解析规则的param
				parse_str($query, $param); 
				$intersect = array_intersect_assoc($REQUEST, $param);
				$rule      = preg_replace('/\?.*$/U', '', $rule);

				//如果节点相符且url参数满足
				if (in_array($rule, $ruleName) && $intersect == $param) {
					$savedAccessRulesList[] = $rule;
				}

			} else if (in_array($rule, $ruleName)) {
				$savedAccessRulesList[] = $rule;
			}
		}
		// var_dump($savedAccessRulesList);

		//or关系
		if ($relation == 'or' and !empty($savedAccessRulesList)) {
			return true;
		}

		//and关系
		$diff = array_diff($ruleName, $savedAccessRulesList);
		if ($relation == 'and' and empty($diff)) {
			return true;
		}
		return false;
	}

	/**
	 * 获取用户的访问列表
	 * @param int $uid 用户id
	 * @param int $type 权限验证类型
	 */
	public function getAccessRules($uid, $type) {
		static $accessRulesList = array();

		$t = implode(',', (array)$type);

		if (isset($accessRulesList[$uid.$t])) {
			return $accessRulesList[$uid.$t];
		}

		if ($this->authType == 2 && isset($_SESSION['auth_list_'.$uid.$t])) {
			return $_SESSION['auth_list_'.$uid.$t];
		}

		//读取用用户所属用户组
		$groups    = $this->getGroups($uid);
		//保存用户所属用户组设置的所有权限规则id
		$ruleIDs = array();
		foreach ($groups as $value) {
			$ruleIDs = array_merge($ruleIDs, explode(',', trim($value['rules'], ',')));
		}

		$ruleIDs = array_unique($ruleIDs);
		//如果权限规则id集为空
		if (empty($ruleIDs)) {
			$accessRulesList[$uid.$t] = array();
			return array();
		}

		//读取用户组所有权限规则
		$accessRules = self::getDb()->select(
			$this->tables['auth_rule'],
			array('condition', 'name'),
			array(
				'AND'=> array('rule_id'=>$ruleIDs, 'type'=>$type, 'status'=>1)
			)
		);

		//遍历规则集, 将符合的的规则集放置在一个数组中
		$tmAccessRulesList = array();
		foreach ($accessRules as $rule) {
			if (!empty($rule['condition'])) {
				$user = $this->getUserInfomation($uid);

				$command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $rule['condition']);
				@(eval('$condition=('. $command .');'));
				if ($condition) {
					$tmAccessRulesList[] = strtolower($rule['name']);
				}
			} else {
				$tmAccessRulesList[] = strtolower($rule['name']);
			}
		}
		$accessRulesList[$uid.$t] = $tmAccessRulesList;

		if ($this->authType == 2) {
			$_SESSION['auth_list_'.$uid.$t] = $tmAccessRulesList;
		}

		return array_unique($tmAccessRulesList);
	}

	/**
	 * 获取用户的用户组
	 * @param int $uid 用户id
	 * @return array
	 */
	public function getGroups($uid) {
		static $groups = array();
		//内存缓存
		if (isset($groups[$uid])) {
			return $groups[$uid];
		}

		$simpleFC         = self::getFC();
		$groupsCacheID    = md5(date('Y-m-d').'_group_'.$uid);
		$groupsCachedData = $simpleFC->get($groupsCacheID);
		//查看文件中是否有缓存
		if (!empty($groupsCachedData)) {
			return $groupsCachedData;
		}else{
			$userGroups = self::getDb()->select(
				$this->tables['auth_group_access'], 
				array("[><]{$this->tables['auth_group']}" => array('group_id'=>'group_id')),
				array('uid', $this->tables['auth_group'].'.group_id', $this->tables['auth_group'].'.title', 'rules'),
				array('AND' =>
					array(
						$this->tables['auth_group_access'].'.uid' => $uid, 
						$this->tables['auth_group'].'.status'     =>"1")
					)
				);
			////设置文件缓存
			$simpleFC->set($groupsCacheID, $userGroups);
			//设内存缓存
			$groups[$uid] = $userGroups ? $userGroups: array();
		}

		return $groups[$uid];
	}

	/**
	 * 获取用户信息
	 * @param int $uid 用户id
	 * @return array
	 */
	public function getUserInformation($uid) {
		static $userinfo = array();

		if (!isset($userinfo[$uid])) {

			//将用户信息作文件缓存
			$simpleFC = self::getFC();

			$userinfoID     = md5(date('Y-m-d').$uid);
			$userinfoCached = $simpleFC->get($userinfoID);

			if (empty($userinfoCached)) {
				$userinfo[$uid] = self::getDb()
					->get($this->tables['auth_user'], '*' , array('user_id'=>$uid));
				//设置缓存
				$simpleFC->set($userinfoID, $userinfo[$uid]);
			}else{
				$userinfo[$uid] = $userinfoCached;
			}
		}
		return $userinfo[$uid];
	}

	/**
	 * 获取数据库操作元
	 * @return mixed
	 */
	public static function getDb() {
		if (self::$db == null) {
			$config = new \engine\util\Config();
			self::$db = new Medoo(array(
					'database_type' => $config->mdbtype,
					'database_name' => $config->database,
					'server'        => $config->hostname,
					'username'      => $config->username,
					'password'      => $config->password,
				));
		}
		return self::$db;
	}

	/**
	 * 获取缓存元
	 * @return object
	 */
	public static function getFC() {
		if (self::$fc == null) {
			self::$fc = new \engine\cache\SimpleFileCache();
			self::$fc->setCacheDir();
		}
		return self::$fc;
	}

	/**
	 * 安装认证的数据库表
	 */
	private static function install() {
		$sqls[] = <<<EOF
		CREATE TABLE IF NOT EXISTS `auth_rule` (
			`rule_id` mediumint(8) NOT NULL AUTO_INCREMENT,
    		`name` char(80) NOT NULL DEFAULT '',  
    		`title` char(20) NOT NULL DEFAULT '',  
    		`type` tinyint(1) NOT NULL DEFAULT '1',    
    		`status` tinyint(1) NOT NULL DEFAULT '1',  
    		`condition` char(100) NOT NULL DEFAULT '',  # 规则附件条件,满足附加条件的规则,才认为是有效的规则
    		PRIMARY KEY (`id`),  
    		UNIQUE KEY `name` (`name`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
EOF;
		
		$sqls[] = <<<EOF
		CREATE TABLE IF NOT EXISTS `auth_group` (
			`group_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT, 
    		`title` char(100) NOT NULL DEFAULT '', 
    		`status` tinyint(1) NOT NULL DEFAULT '1', 
    		`rules` char(80) NOT NULL DEFAULT '', 
    		PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
EOF;

		$sqls[] = <<<EOF
		CREATE TABLE IF NOT EXISTS `auth_group_access` (  
		    `uid` mediumint(8) unsigned NOT NULL,  
		    `group_id` mediumint(8) unsigned NOT NULL, 
			UNIQUE KEY `uid_group_id` (`uid`,`group_id`),  
		    KEY `uid` (`uid`), 
		    KEY `group_id` (`group_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOF;

		//执行sql语句
		foreach ($sqls as $sql) {
			$this->db->query($sql);
		}
	}
}
?>