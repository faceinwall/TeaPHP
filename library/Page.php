<?php 
namespace library;

class Page {
	/**
	 * @var int 总记录数
	 */
	private $total;

	/**
	 * @var int 每页总行数
	 */
	private $pageRows;

	/**
	 * @var int 分面游标
	 */
	private $begin;

	/**
	 * @var int 偏移量
	 */
	private $offset;

	/**
	 * @var int 页数
	 */
	private $pageNums;

	/**
	 * @var int 1,2,3,4,5页码
	 */
	private $listNums = 8;

	/**
	 * @var array 输出的配置参数
	 */
	private $config =array(
			'header' => 'Records',
			'prev'   => 'Prev',
			'next'   => 'Next',
			'first'  => 'First',
			'last'   => 'Last',
		);

	/**
	 * @param int $total 总记录数
	 * @param int $pageRows 每页总行数
	 * @param int $params 额外uri　query string参数
	 */
	public function __construct($total, $pageRows = 15, $params=null) {
		$this->total    = $total;
		$this->pageRows = $pageRows;
		$this->uri      = $this->getUir($params);
		$this->page     = !empty($_GET['page']) ? $_GET['page'] : 1;
		$this->pageNums = ceil($this->total / $this->pageRows);

		list($this->begin, $this->offset) = $this->getLimit();
	}

	/**
	 * @param string $name 属性
	 * @return mixed
	 */
	public function __get($name) {
		return in_array($name, array('begin', 'offset')) ? $this->$name : null;
	}

	/**
	 * @param array $config 配置参数
	 */
	public function setConfig(array $config) {
		$this->config = $config;
	}

	/**
	 * 输出分面结果
	 * @param array $display 页码
	 * @return array
	 */
	public function showPage(array $display = array(0, 1, 2, 3, 4, 5, 6, 7, 8)) {
		return '<span class="page-records">'.
				$this->total. 
				$this->config['header'].'</span>'.'<span class="page-lists">'.
				$this->first().
				$this->prev().
				$this->pageList().
				$this->next().
				$this->last().'</span>'.
				$this->goPage();	
	}

	/**
	 * 获取url
	 * @param string $params
	 */
	private function getUir($params) {
		$url   = $_SERVER['REQUEST_URI']. (strpos($_SERVER['REQUEST_URI'], '?') ? '' : '?').$params;
		$parse = parse_url($url);

		if (isset($parse['query'])) {
			parse_str($parse['query'], $queryParams);
			unset($queryParams['page']);

			$url = $parse['path'] . '?'.http_build_query($queryParams);
		}
		return $url;
	}

	private function getLimit() {
		return array(($this->page - 1) * $this->pageRows, $this->pageRows);
	}

	/**
	 * 返回开始页
	 * @return int 
	 */
	private function start() {
		return $this->total == 0 ? ( ($this->page - 1) * $this->pageRows + 1 ): 0;
	}

	/**
	 * 返回尾页
	 * @return int
	 */
	private function end() {
		return min($this->page * $this->pageRows, $this->total);
	}

	/**
	 * 首页
	 * @return string
	 */
	private function first() {
		return $this->page == 1 ? '' : 
			'<a href="'.$this->uri.'&page=1" class="page-first">'.$this->config['first'].'</a>';
	}

	/**
	 * 前一页
	 * @return string
	 */
	private function prev() {
		return $this->page == 1 ? '':
			'<a href="'.$this->uri.'&page='.($this->page -1).'" class="page-prev">'.$this->config['prev'].'</a>';
	}

	/**
	 * 页码列表
	 * @return string
	 */
	private function pageList() {
		$link   = '';
		$i_Nums = floor($this->listNums / 2);

		for ($i = $i_Nums; $i >= 1; $i--) {
			$page = $this->page - $i;

			if ($page < 1) continue;

			$link .= '<a href="'.$this->uri.'&page='.$page.'">'.$page.'</a>';
		}

		$link .= '<a href="'.$this->uri.'&page='.$this->page.'" class="current">'.$this->page.'</a>';

		for ($i = 1; $i <= $i_Nums; $i++) {
			$page = $this->page + $i;

			if ($page <= $this->pageNums) {
				$link .= '<a href="'.$this->uri.'&page='.$page.'">'.$page.'</a>';
			} else {
				break;
			}
		}

		return $link;
	}

	/**
	 *　后一页
	 * @return string
	 */
	private function next() {
		return $this->page == $this->pageNums ? '':
			'<a href="'.$this->uri.'&page='.($this->page + 1).'">'.$this->config['next'].'</a>';
	}

	/**
	 * 尾页
	 * @return string
	 */
	private function last() {
		return $this->page == $this->pageNums? '':
			'<a href="'.$this->uri.'&page='.$this->pageNums.'">'.$this->config['last'].'</a>';
	}

	/**
	 *页面跳转
	 * @return string
	 */
	private function goPage() {
		return '<span class="page-go"><input type="text" onkeydown="javascript:if (event.keyCode == 13){ var page=(this.value >'.
			$this->pageNums.') ? '.
			$this->pageNums.':this.value; location=\''.
			$this->uri.'&page=\' + page}" value="'.
			$this->page.'" style="width:25px"><input type="button" value="GO" onclick="javascript: var page = (this.previousSibling.value>'.
			$this->pageNums.')?'.$this->pageNums.':this.previousSibling.value;location=\''.
			$this->uri.'&page=\' + page"></span>';
	}

}
?>