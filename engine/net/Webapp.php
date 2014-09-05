<?php 
namespace engine\net;

use engine\net\Router;
use engine\net\Request;
use engine\net\Response;
use engine\core\Dispatcher;
use engine\util\DbBuilder;

class Webapp extends \engine\core\Engine {
	/**
	 * @var array 应用程序配置数组
	 */
	protected static $config = array();

    /**
     * @var object db操作组件
     */
    protected static $db = null;

	/**
	 * @var object request组件, 解析http的请求
	 */
	protected $request;

	/**
	 * @var object response组件, 响应http的请求
	 */
	protected $response;

	/**
	 * @var object 调度器, 用于分发路由请求
	 */
	protected $dispatcher;


	/**
	 * @param array $config
	 */
	public function __construct(array $config) {
		self::$config     = $config;
		$this->initialize();
	}

	/**
	 * 初始化
	 */
	private function initialize(){
		$this->request    = new Request();
		$this->response   = new Response();
		$this->dispatcher = new Dispatcher();

		$this->enableSession();
	}

	/**
	 * 开启session
	 */
	private function enableSession() {
		if (is_bool($this->config('session'))) {
			session_start();
		}
	}

    /**
     * 获取配置文件的配置值
     * @param stirng $name  配置名称
     * @param string $value 配置值
     * @return string
     */
    public function config($name, $value = '') {
		if (array_key_exists($name, self::$config)){
			if (empty($value)){
				return self::$config[$name];
			}else{
				self::$config[$name] = $value;
			}
		}
	}

	/**
	 * 启动应用程序
	 */
	public function run() {
		$this->dispatcher->route($this->request, $this->response);
	}

    /**
     * 快捷模型操作
     * @param $tablename    数据表名
     * @return object       返回DbBuilder对象
     */
    public static function model($tablename = '') {
        if (self::$db == null) {
            self::$db = new DbBuilder();
            self::$db->setDb(array(
                'type' => self::$config['dbtype'],
                'hostname' => self::$config['hostname'],
                'database' => self::$config['database'],
                'username' => self::$config['username'],
                'password' => self::$config['password'],
            ));
        }
        return !$tablename ? self::$db : self::$db->from($tablename);
    }

}
 ?>