<?php 
namespace controller;

class MenuController extends \engine\core\Controller { // extends \controller\BaseController {
	public function actionIndex() {
		if (\Tea::app()->request->isPost()) {
			var_dump($_POST);	
			exit;
		}
		$this->render('index.php');
	}

}
 ?>