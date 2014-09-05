<?php 
namespace engine\core;
use engine\core\Engine;
use engine\template\View;

abstract class Controller extends Engine {
	/**
	 * @var object http请求解析
	 */
	protected $request;

	/**
	 * @var object http响应器
	 */
	protected $response;

	/**
	 * @var object 视图组件
	 */
	protected $view;

	/**
	 * @var string 设置该控制器是否有缓存
	 */
	protected $cacheId = null;


	/**
	 * 构造方法
	 */
	public function __construct(\engine\net\Request $request, \engine\net\Response $response) {
		$this->request  = $request;
		$this->response = $response;
		$this->view     = new View();

		//the first run method of controller
		if (method_exists($this, 'start')) {
			$this->start();
		}
	}

	public function __get($name) {
		return isset($this->$name) ? $this->$name: null;
	}

	/**
	 * 渲染视图文件
	 * @param string $filename
	 * @param array $variables
	 * @param string $cache
	 */
	public function render($filename, array $variables= array()) {
		if ($this->cacheId) { //如果设置缓存id
			//设置cache目录	
			$this->view->setCacheDir(
					\Tea::app()->config('cache_dir') .DIRECTORY_SEPARATOR.
					\Tea::app()->config('page_cache_dir'));

			if ($this->view->isCached($filename, $this->cacheId)) {//判断是否存在缓存
				echo $this->view->fetchCache($filename, $this->cacheId);
				return;
			} else{//过期
				$this->view->setCaching(true);
				$this->output($filename, $variables, $this->cacheId);
				return;
			}
		}
		$this->output($filename, $variables, $this->cacheId);
	}

	/**
	 * 设置是否缓存页面
	 * @param string $id 缓存标识
	 */
	public function setCacheId($id) {
		$this->cacheId = $id;
	}

	/**
	 * 页面跳转
	 * @param string 跳转url
	 */
	public function redirect($url) {
		if (is_string($url) || 
				(is_object($url) && get_class($url) == 'engine\net\Url')) {
			header('Location:' .$url->url);exit;
		}
	}

	/**
	 * 返回模板文件的目录位置
	 * @return string
	 */
	private function getTemplateDir() {
		$fragments = explode('\\', substr(get_class($this), 0, -10));
		$viewDir   = strtolower(end($fragments));

		return \Tea::app()->config('template_dir') .DIRECTORY_SEPARATOR. $viewDir;
	}

	/**
	 * 输出视图模板
	 * @param string $filename 模板文件名
	 * @param array $variables 模板数据
	 * @param string $cacheId 缓存id
	 */
	private function output($filename, array $variables, $cacheId) {
		if (!empty($variables)) {
			foreach ($variables as $key => $value) {
				$this->view->$key = $value;
			}
		}
		$this->view->setTemplateDir($this->getTemplateDir());
		$this->view->render($filename, $cacheId);
	}

	abstract public function actionIndex();
} 
 ?>