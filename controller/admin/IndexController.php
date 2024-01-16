<?php 
namespace controller\admin;

use engine\core\Controller;
class IndexController extends Controller {
    public function actionIndex() {
        $this->render('index.php', array());
    }
}
 ?>