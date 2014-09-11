<?php 
namespace controller;

// use model\Blog;
use model;
use engine\net\Url;
use engine\net\Request;
use engine\core\Controller;
use engine\cache\SimpleFileCache;

abstract class BaseController extends Controller{

	/**
	 * 应用程序所有action运行前的执行
	 */
	public function start() {
		//加载辅助functions
		$this->helper('functions');

		// define('UID', get_uid());
		// //检测登录
		// if(!UID) {
		// 	$this->redirect(new Url('public/login'));
		// }

		// //检测是否已经登录
		// if (!user_exists_online()) {
		// 	$this->redirect(new Url('public/login'));
		// }

		//是否是超级管理员
		// define('IS_ROOT', is_administrator());


		$this->view->menus    = $this->getMenus();
		$this->view->username = 'Bob';
	}


	/**
	 * 返回菜单
	 * @return $array
	 */
	public function getMenus() {
		$simpleFC = new SimpleFileCache();
		$simpleFC->setCacheDir();

		$menusIdentify = md5(date('Y-m-d').get_uid());
		$menusCached   = $simpleFC->get($menusIdentify);

		if (empty($menusCached)) {
			$MenuModel  = new \model\Menu();
			$flashMenus = $MenuModel->getMenus();
			$simpleFC->set($menusIdentify, $flashMenus);
		}
		return $flashMenus;	
	}

	/**
	 * 
	 * 缓存系统配置信息 
	 */
	public function initialConfiguration() {
		$simpleFC = new SimpleFileCache();
		$simpleFC->setCacheDir();
		$simpleFC->setCacheTime(4000);//4000 seconds

		$configsCacheIdentify = md5('config_'.date('Y-m-d'));
		$configsCached        = $simpleFC->get($menusIdentify);

		if (empty($configsCached)) {
			$ConfigModel = new \model\Config();
			$configs     = $ConfigModel->config();
			$simpleFC->set($configsCached, $configs);
		}
	}

	/**
	 * 系统分页
	 * @param string $modelName
	 * @param array $condition
	 * @return object
	 * @throws \Exception
	 */
	public function page($modelName, $condition = array()) {
		//检查model	
		if (class_exists($modelName)) {
			$instance = new $modelName();
		} else {
			throw new \Exception(" class not found `$modelName`", 1);
		}


		$this->helper('functions');

		if (method_exists($instance, 'total')) {
			$totalCount = $instance->total((isset($condition['where'])? $condition['where']:array()));
		} else {
			throw new \Exception("You must implement the method `total` of ".get_class($instance), 1);
		}

		$listRows = get_cached_config('list_row');
		if (empty($listRows)){
			$listRows = \Tea::app()->config('list_row');
		}
		$Pagination = new \library\Page($totalCount, $listRows);

		if (method_exists($instance, 'pageList')) {
			$pagecondition = array('limit' => array($Pagination->begin, $Pagination->offset));
			$pagecondition = array_merge($condition, $pagecondition);

			$pageInfomation = new \stdClass();
			$pageInfomation->list = $instance->pageList($pagecondition);
			$pageInfomation->page = $Pagination->showPage();
		} else {
			throw new \Exception('You must implement the method `pageList` of '.get_class($instance), 1);
		}

		return $pageInfomation;
	}

	/**
	 * 检查规则
	 * @param string|array $rule 规则集
	 * @param int $type 认证类型
	 * @param string $mode 认证方式
	 * @return boolean
	 */
	final protected function checkRule($rule, $type = 1, $mode = 'url') {
		if (IS_ROOT) {
			return true; //管理员允许访问任何页面
		}
		 static $Auth = null;

		 if (!$Auth) {
		 	$Auth = new \library\Auth();
		 }
		 if (!$Auth->check($rule, UID, $type, $mode)) {
		 	return false;
		 }
		 return true;
	}
}
 ?>