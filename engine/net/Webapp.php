<?php 
namespace engine\net;

use engine\net\Request;
use engine\net\Response;
use engine\core\Dispatcher;
use engine\Db\Db;

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
        self::$config = $config;
        $this->initialize();
    }

    /**
     * 初始化
     */
    private function initialize() {
        $this->request = new Request();
        $this->response = new Response();
        $this->dispatcher = new Dispatcher();

        $this->enableSession();
    }

    /**
     * 开启session
     */
    private function enableSession() {
        if (is_bool(self::config('session'))) {
            session_start();
        }
    }

    /**
     * 初始化Db, 调用方式: \Tea::db()
     */
    static public function db() {
        if (self::$db != null) {
            return self::$db;
        }
        $db = new Db(array(
            'db_type'  => self::$config['db_type'],
            'db_port'  => self::$config['db_port'],
            'hostname' => self::$config['hostname'],
            'database' => self::$config['database'],
            'username' => self::$config['username'],
            'password' => self::$config['password'],
            'table_prefix' => self::$config['table_prefix'],
        
            'sqlite_path' => self::$config['sqlite_path'],
        ));
        
        self::$db = $db;
        return $db;
    }

    /**
     * 获取配置文件的配置值, 调用方式 \Tea::config('abc')
     * @param stirng $name  配置名称
     * @param string $value 配置值
     * @return string
     */
    static public function config($name, $value = '') {
        if (array_key_exists($name, self::$config)){
            return self::$config[$name];
        }
        return $value;
    }

    /**
     * 启动应用程序
     */
    public function run() {
        $this->dispatcher->route($this->request, $this->response);
    }
}