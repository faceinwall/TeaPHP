<?php 
namespace engine\net;

use engine\net\Request;
use engine\net\Response;
use engine\core\Dispatcher;

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
}