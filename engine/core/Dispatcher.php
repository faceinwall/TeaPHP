<?php 
namespace engine\core;
use engine\net\Router;

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
	public function route(\engine\net\Request $request, \engine\net\Response $response) {
        $router = new \engine\net\Router();
        
        if ($router->callback){
            $params = !empty($router->params)? array_values($router->params): array();
            $params[] = $response;
            $params[] = $request;

            $this->execute($router->callback, $params);
        } else{
            // 若路由不匹配, 发送404
            $response->status(404)
                ->write(
                    '<h1>404 Not Found</h1>'.
                    '<h3>Tha page you have requested could not found.</h3>'
                )
                ->send();
        }
	}

    /**
     * 执行回调处理
     * @param string $callback 回调的处理函数 eg: \controller\Blog@actionIndex
     * @param array $params    回调函数的参数
     */
    public function execute($callback, array &$params = array()) {
        list($class, $method) = is_array($callback) ? 
            $callback: explode('@', $callback);

        if (class_exists($class)){
            // new instance
            $instance = new $class(array_pop($params), array_pop($params));

            if (is_callable(array($instance, $method))) {
                self::invokeMethod(array($instance, $method), $params);
            } else {
                throw new \Exception("method `$method` of `$class` not callable!", 1);
            }
        } else {
            throw new \Exception("Class `$class` not found!", 1);
        }
    }

    /**
     * Invokes a method.
     *
     * @param mixed $func Class method
     * @param array $params Class method parameters
     * @return mixed Function results
     */
    public static function invokeMethod($func, array &$params = array()) {
        list($class, $method) = $func;

		$instance = is_object($class);
		
        switch (count($params)) {
            case 0:
                return ($instance) ?
                    $class->$method():
                    $class::$method();
            case 1:
                return ($instance) ?
                    $class->$method($params[0]):
                    $class::$method($params[0]);
            case 2:
                return ($instance) ?
                    $class->$method($params[0], $params[1]):
                    $class::$method($params[0], $params[1]);
            case 3:
                return ($instance) ?
                    $class->$method($params[0], $params[1], $params[2]) :
                    $class::$method($params[0], $params[1], $params[2]);
            case 4:
                return ($instance) ?
                    $class->$method($params[0], $params[1], $params[2], $params[3]) :
                    $class::$method($params[0], $params[1], $params[2], $params[3]);
            case 5:
                return ($instance) ?
                    $class->$method($params[0], $params[1], $params[2], $params[3], $params[4]) :
                    $class::$method($params[0], $params[1], $params[2], $params[3], $params[4]);
            default:
                return call_user_func_array($func, $params);
        }
    }
}
 ?>