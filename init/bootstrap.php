<?php 
class Tea {
	/**
     * 应用程序程序实例
     * @var $app
	 */
	private static $app = null;

    /**
     * 创建web应用程序实例
     * @param array $config         应用程序配置
     * @return \engine\net\Webapp   返回应用程序实例
     */
    public static function createWebApplication($config) {
		static $initialized = false;

		if (!$initialized) {
			require_once __DIR__.'/autoload.php';

			self::$app = new \engine\net\Webapp($config);

			$initialized = true;
		}
		return self::$app;
	}

	/**
     * 获取应用程序实例
	 * @return object
	 */
	public static function app() {
		return self::$app;
	}

    /**
     * 调用webapp的静态方法
     * @param $name     方法名称
     * @param $params   方法参数
     * @return mixed
     */
    public static function __callStatic($name, $params) {
        require_once __DIR__.'/autoload.php';

        return \engine\core\Dispatcher::invokeMethod(array(self::$app, $name), $params);
    }

    //不充许该类实例化
	private function __construct() {}
	private function __destruct() {}
	private function __clone() {}
}
 ?>