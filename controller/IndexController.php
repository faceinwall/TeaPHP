<?php 
namespace controller;
use controller\BaseController;
class IndexController extends \engine\core\Controller { /* extends BaseController { */

	public function actionIndex(){
		header('content-type:text/html;charset=utf-8');
		$data = array(
			'姓名' => array('width'=>'80', 'align' => 'center', 'sort'=>'name', 'order'=>'ASC'),
			'年纪' => array('width'=>'60', 'align' => 'center', 'sort'=>'age', 'order'=>'ASC'),
			'性别' => array('width'=>'90', 'align' => 'center', 'sort'=>'sex', 'order'=>'ASC'),
		);
		$Grid = new \library\Grid();
		$Grid->gridTh = $data;
		$Grid->setCurrentOrder($this->request->get()->sort, $this->request->get()->order);
		echo $Grid->generalGrid();
		// var_dump($Grid);
		// echo $_GET['isd'], '<br/>';
		// $this->vendor('third.Demo');
		// $demo = new \Demo;
		// $demo->demoFunction();
		// var_dump($_SERVER);
		// $row = \Tea::model('tm_user')->where(array('id'=>$_SESSION['id']))->one();

		// echo date('Y-m-d H:i:s', 1409587200   );
		// $this->render('index.php', array('username' => $row['name']));

		// $medoo = new \library\Medoo('test');
		// var_dump($medoo->select('tm_user', array('username', 'password')));

		// header('content-type:text/html;charset=utf-8');
		// $this->vendor('phpQuery.phpQuery');

		// \phpQuery::newDocumentFile('http://www.qq.com');
		// echo pq('div')->html();
		// \phpQuery::$documents = null;

		// $mbox = imap_open("{imap.qq.com:993/imap/ssl}INBOX", '714480119@qq.com', '') 
		// 		or die('cannot connect: '. imap_last_error());

		// $checkArray = imap_check($mbox);

		// echo 'curret recieve:'. $checkArray->Nmsgs.'<br/>';

		// for ($i=1; $i <= $checkArray->Nmsgs; $i++) { 
		// 	$mailStructrue = imap_fetchstructure($mbox, $i);

		// 	$mailInfo = imap_header($mbox, $i);
		// 	$from = $mailInfo->from;

		// 	echo imap_mime_header_decode($mailInfo->subject)[0]->text, '<br/>';

		// 	$fromAddress = $from[0]->mailbox.'@'.$from[0]->host;

		// 	echo $fromAddress, '<br/>'14095224001409500800;
		// }

		// $menu = new \model\Menu();

		// date_default_timezone_set('PRC');
		// var_dump($menu->fetch());
		// // echo date('Y-m-d H:i:s',    1409673600 );
		// $obj = new \stdClass();
		// $obj->name = 'bob';
		// $obj->age = 23;
		// // echo date('Y-m-d H:i:s', strtotime(date('Y-m')));
		// 	echo strtotime('2014-09-01 0:0:0');
		// echo date('Y-m-d H:i:s',1409522400);
	}
}
 ?>