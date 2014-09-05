<?php 
namespace controller;

// use model\Blog;
use model;
use engine\net\Request;
use engine\core\Controller;
use engine\net\Url;
use engine\cache\SimpleFileCache;

abstract class BaseController extends Controller{

	/**
	 * 应用程序所有action运行前的执行
	 */
	public function start() {
		$this->helper('functions');
		//检测登录
		if(!get_uid()) {
			$this->redirect(new Url('/TeaPHP/login'));
		}

		//检测是否已经登录
		if (!user_exists_online()) {
			$this->redirect(new Url('/TeaPHP/login'));
		}

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
	 * @param array $where
	 * @return object
	 * @throws \Exception
	 */
	public function page($modelName, $where = array()) {
		try{
			$instance = new $modelNmae();
		} catch (\Exception $e) {
			echo $e->getMessage();
		}

		$this->helper('functions');
		if (method_exists($instance, 'pageTotal')) {
			$totalCount = $instance->pageTotal();
		} else {
			throw new \Exception("You must implement the method `total` of ".get_class($instance), 1);
		}

		$listRows = get_cached_config('list_row');
		if (empty($listRows)){
			$listRows = \Tea::app()->config('list_row');
		}
		$Pagination = new \engine\library\Page($totalCount, $listRows);

		if (method_exists($instance, 'pageList')) {
			$pageWhere = array('limit' => array($Pagination->start, $Pagination->end));

			$pageWhere = array_merge($where, $pageWhere);

			$pageInfomation = new \stdClass();
			$pageInfomation->list = $instance->pageList($pageWhere);
			$pageInfomation->page = $Pagination->fpage();
		} else {
			throw new \Exception('You must implement the method `pageList` of '.get_class($instance), 1);
		}

		return $pageInfomation;
	}
}
 ?>