<?php 
namespace controller;

class PublicController extends \engine\core\Controller {
	public function actionIndex(){}

	public function actionLogin() {
		$this->helper('functions');

		if (get_uid()) {
			$this->redirect(new \engine\net\Url('/index'));
		}
		if (\Tea::app()->request->isPost()) {
			$User = new \model\User();
			if ($User->autoLogin()) {
				$this->redirect(new \engine\net\Url('/index'));
			}
			$this->redirect(new \engine\net\Url('/public/login'));
		}

		$this->render('login.php');
	}

	/**
	 * 用户注销
	 */
	public function actionLogout() {
		$User = new \model\User();
		if ($User->autoLogout()) {
			$this->redirect(new \engine\net\Url('/index'));
		}
	}

}
 ?>