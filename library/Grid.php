<?php 
namespace library;

class Grid {
	/**
	 * @var array 表格th
	 */
	protected $gridTh= array();

	/**
	 * @var array 表格td
	 */
	protected $gridTd = array();

	/**
	 * @var string 默认排序
	 */
	protected $defaultSort = '';

	/**
	 * @var string 排序方式 DESC ASC
	 */
	protected $defaultOrder = '';

	/**
	 * @var string 当前排序
	 */
	protected $currentSort = '';

	/**
	 * @var string 当前排序方式
	 */
	protected $currentOrder = '';

	/**
	 * @var string Home url
	 */
	protected $baseUrl = '';

	/**
	 * @var array Query String
	 */
	protected $params = array();

	/**
	 * @var string 表格样式文件
	 */
	protected $gridCssFile = '';

	/**
	 * @var array Th的标记
	 */
	protected $gridThIcons = array();

	/**
	 * @var array Th的排序提示信息
	 */
	protected $gridThTips = array();

	/**
	 * Grid 配置属性
	 * @param array $config
	 * 
	 */
	public function __construct(array $config = array()) {

		if (!empty($config)) {
			$this->defaultSort = $config['sort'] ? $sort: '';

			if (in_array(strtolower($config['order']), array('', 'asc', 'desc'))) {
				$this->defaultOrder = $order;
			}
			$this->gridThIcons = $config['icons'];//array('asc'=>'asc.gif', 'desc'=>'desc.gif');
		} else {
			//JUST set gridThIcons
			$this->gridThIcons = array('asc'=>'↑', 'desc'=>'↓');
		}
	}

	/**
	 * 
	 * @param string $name 属性名
	 * @param mixed $value 属性值
	 */
	public function __set($name, $value) {
		if ( array_key_exists($name, get_class_vars(get_class($this)))) {

			$this->$name = $value;
		} else {
			throw new \Exception("Invalid property `$name`", 1);
		}
	}

	/**
	 * 设置表格头部
	 * @param array $gridTh
	 */
	public function setGridTh( array $gridTh = array()) {
		$this->gridTh = $gridTh;
	}

	/**
	 * 设置当前表格的排序
	 * @param string $sort 当前排序字段
	 * @param string $order 当前排序方式
	 */
	public function setCurrentOrder($sort = null, $order = null) {
		if ($order && $this->gridTh && array_key_exists($sort, $this->gridTh)) {
			$this->currentSort = $sort;
		} else {
			//Fix the current
			$this->currentSort = current(array_keys($this->gridTh));
		}

		if (in_array(strtolower($order), array('', 'asc', 'desc'))) {
			$this->currentOrder = $order;
		} else {
			$this->currentOrder = 'asc';
		}
	}

	/**
	 * 增加一行数据
	 * @param array $gridTd 表格行数据
	 */
	public function addGridTd( array $gridTd = array()) {
		$this->gridTd[] = $gridTd;
	}

	public function generalGrid() {
		$grid_HTML = "<TABLE>\r\n";
		$grid_HTML .= $this->generalGridHeader();
		$grid_HTML .= $this->generalGridBody();
		$grid_HTML .= "</TABLE>\r\n";

		return $grid_HTML;
	}


	private function generalGridHeader() {
		if (empty($this->gridTh)) return false;

		$gridTh_HTML = "<TR>\r\n";

		//Fix the default
		if (empty($this->defaultSort)) {
			$this->defaultSort = current(array_keys($this->gridTh));

			if (empty($this->defaultOrder)){
				if (isset($this->gridTh[$this->defaultSort]['order'])) {
					$this->defaultOrder = $this->gridTh[$this->defaultSort]['order'];
				} else {
					$this->defaultOrder = 'desc';
				}
			}
		}

		foreach ($this->gridTh as $columnName => $columnDefinations) {
			//bigin	
			$gridTh_HTML .= "<TD";
			$gridTh_HTML .= isset($columnDefinations['align']) && !empty($columnDefinations['align']) ? " ALIGN=\"{$columnDefinations['align']}\"" : '';
			$gridTh_HTML .= isset($columnDefinations['width']) && !empty($columnDefinations['width']) ? " WIDTH=\"{$columnDefinations['width']}\"" : '';
			$gridTh_HTML .= isset($columnDefinations['color']) && !empty($columnDefinations['color']) ? " COLOR=\"{$columnDefinations['color']}\"" : '';
			$gridTh_HTML .= ">";


			if (!isset($columnDefinations['sort']) && !$columnDefinations['sort']) {
				$gridTh_HTML .= $columnName;
			} else {
				//未进入当前排序
				if ($this->currentSort != $columnName &&
					$this->defaultSort != $columnName) {

					$next_SORT = $columnName;
					$next_ORDER = $columnDefinations['order'];
				} else {
					//当前排序
					$curr_ORDER = $this->currentSort == $columnName ? $this->currentOrder : $this->defaultOrder;
					$prev_ORDER = $this->currentSort == $columnName && $this->currentSort != $this->defaultSort ?
									$columnDefinations['order'] : $this->defaultOrder;

					if ($curr_ORDER== $prev_ORDER|| $this->defaultSort == $columnName) {
						$next_SORT = $columnName;
						$next_ORDER = strtolower($curr_ORDER) == 'asc'? 'desc' : 'asc';
					} else {
						//反序
						$next_SORT = $this->defaultSort;
						$next_ORDER = $this->defaultOrder;
					}
				}


				$this->params = array('sort' => $next_SORT, 'order' => $next_ORDER);
				$url = new \engine\net\Url($this->baseUrl,$this->params);
				echo $_SERVER['REQUEST_URI'], '<br/>';
				$thTitile = $columnName;

				if ($this->currentSort == $columnName || (empty($this->currentSort) && $this->defaultSort == $columnName)) {
					$curr_ORDER = $this->currentOrder ? $this->currentOrder : $this->defaultOrder;

					if ($curr_ORDER == '') {
						$curr_ORDER = 'asc';
					}

					// $gridThIcon = $this->gridThIcons[strtolower($curr_ORDER)];
					
					// $thTitile .= "<IMG SRC=\"{$gridThIcon}\" WIDTH=\"12\" HEIGHT=\"12\" ALIGN=\"absmiddle\" BORDER=\"0\"";

					$thTitile .= $this->gridThIcons[strtolower($curr_ORDER)];
				}
				$gridTh_HTML  .= "<A HREF=\"{$url->url}\">{$thTitile}</A>";
			}

			$gridTh_HTML .= "</TD>\r\n";
		}
		
		return $gridTh_HTML .= "<TR>\r\n";
	}

	private function generalGridBody() {
		$gridTd_HTML = '';

		if (count($this->gridTd) > 0) {
			foreach ($this->gridTd as $rowNo=> $column) {
				if (is_array($column)) {

					$gridTd_HTML .= "<TR color=\"$column\">\r\n";

					foreach ($this->gridTh as $columnName => $columnDefinition) {
						$gridTh_HTML .= '<TD';
						$gridTd_HTML .= isset($columnDefinition['align']) && $columnDefinition['align']? " align=\"{$columnDefinition['align']}\"" : '';
						$gridTd_HTML .= isset($columnDefinition['class']) && $columnDefinition['class']? " class=\"{$columnDefinition['class']}\"" : '';

						if (array_key_exists($columnName, $column)) $gridTd_HTML .= $column[$columnName];

						$gridTd_HTML .= "</TD>\r\n";	
					}
				} else {
					$gridTd_HTML .= "<TR>\r\n";
					$gridTd_HTML .= "<TD COLSPAN=\"".count($this->gridTh)."\">";
					$gridTd_HTML .= "</TR>\r\n";
				}
			}
		} else {
			$gridTd_HTML .= "<TR>\r\n";
			$gridTd_HTML .= "<TD COLSPAN=\"".count($this->gridTh)."\">";
		}
	}
}
?>