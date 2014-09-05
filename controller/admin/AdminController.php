<?php 
namespace controller\admin;

use engine\core\Controller;
use engine\template\View;
use engine\cache\SimpleFileCache;
use engine\util\Log;
use engine\net\Url;

class AdminController extends controller\BaseController {
	
	public function actionIndex() {
		$this->render('index.php', array(
				'title'=>'TeaPHP 框架', 
				'content'=>'TeaPHP 是一个简单,可扩展的微框架',//));
		), __FUNCTION__.date('Y-m-d', time()));
	}

	public function actionLogin() {
		$request = \Tea::app()->request;
		if ($request->isPost()) {
			
			$username = $request->post()->username;
			$password = $request->post()->password;

			$simpleFC = new \engine\cache\SimpleFileCache();
			$simpleFC->setCacheDir();

			$userinfo = $simpleFC->get('userinfo');
			if ($userinfo) {
				if ($userinfo['uname'] == $username
					&&$userinfo['password'] == md5($password))
				{
					$row = $userinfo;
				}
			} else {
				$row = \Tea::model('sys_admin')
					->where(array('uname'=>$username, 'password'=>md5($password)))
					->one();
				$simpleFC->set('userinfo', $row);
			}

			if ($row) {
				$this->redirect(new Url('/TeaPHP/admin'));
			}
			$this->redirect(new Url('/TeaPHP/admin/login'));
		}
		$this->setCacheId(__FUNCTION__.date('Y-m-d', time()));
		$this->render('login.php');
	}
}
 ?>