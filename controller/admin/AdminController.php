<?php 
namespace controller\admin;

use engine\core\Controller;
class AdminController extends Controller {
	public function actionIndex() {
		$this->render('index.php', array());
	}
}
 ?>