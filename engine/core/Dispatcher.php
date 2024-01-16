<?php 
namespace engine\core;
use engine\net\Router;
use engine\net\Request;
use engine\net\Response;

class Dispatcher {
    /**
     * 构造函数
     */
    public function __construct(){}

    /**
     * 路由调度
     * @param array $url    url配置数组
     * @param Request $request
     * @param Response $response
     * @throws Exception
     */
    public function route(Request $request, Response $response) {
        $router = new Router();
        
        if ($router->callback) {
            $params = !empty($router->params)? array_values($router->params): array();
            $params[] = $response;
            $params[] = $request;

            $this->execute($router->callback, $params);
        } else{
            // 若路由不匹配, 发送404
            $response->status(404)->write(
                '<h1>404 Not Found</h1>'.
                '<h3>Tha page you have requested could not found.</h3>'
            )->send();
        }
    }

    /**
     * 执行回调处理
     * @param string $callback 回调的处理函数 eg: \controller\Blog@actionIndex
     * @param array $params    回调函数的参数
     * @throws Exception
     * @return mixed
     */
    public function execute($callback, array &$params = array()) {
        list($class, $method) = is_array($callback) ?  $callback: explode('@', $callback);
        if (class_exists($class)) {
            // new instance
            $instance = new $class(array_pop($params), array_pop($params));

            if (is_callable(array($instance, $method))) {
                return self::invokeMethod(array($instance, $method), $params);
            }
            throw new \Exception("method `$method` of `$class` not callable!", 1);
        }
        throw new \Exception("Class `$class` not found!", 1);
    }

    /**
     * Invokes a method.
     *
     * @param mixed $func Class method
     * @param array $params Class method parameters
     * @return mixed Function results
     */
    public static function invokeMethod($func, array &$params = array()) {
        return call_user_func_array($func, $params);
    }
}