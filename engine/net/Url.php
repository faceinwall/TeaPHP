<?php 
namespace engine\net;

class Url {
	/**
	 * @var string 主机名称
	 */
	public $hostname = '';

	/**
	 * @var string 返回的完整的url
	 */
	public $url;

	/**
	 * 构造函数
	 * @param string $uri
	 * @param array $params
	 * @param boolean
	 */
	public function __construct($uri, array $params = array(), $mode = true) {
		$this->url = $mode ? $this->createOriginalUrl($uri, $params) : 
			$this->createShortyUrl($uri, $params);
	}

	/**
	 * 构造短风格的url路径, 必须开启url rewrite 才有效
	 * @param string $uri 
	 * @param array $params
	 * @return string
	 *
	 * @usage: $this->createShortUrl('/User/index', array('id'=>5)) --> /user/index/5
	 * 		   $this->createShortUrl('/User/index', array('year'=>2014, 'month'=>5, 'day'=>12)) --> /user/index/2014/5/12
	 */
	private function createShortyUrl($uri, array $params = array()) {
		if (!empty($params)) {
			$anchor = isset($params['#']) ? '#'.$params['#'] :'';
			unset($params['#']);

			$framgent = '';
			if (count($params) > 0){
				foreach ($params as $key => $value) {
					$framgent .= '/'.$value;
				}
			}
			return $framgent ? rtrim($uri, '/').$framgent.$anchor:
				rtrim($uri, '/').$anchor;
		}
		return $uri;
	}

	/**
	 * 构造query string模式的url
	 * @param string $uri
	 * @param array $param 
	 * @return string
	 *
	 * @usage: $this->createOriginalUrl('/user/index', array('year'=>2014, 'month'=>5)) --> /index.php?r=user/index&year=2014&month=5
	 */
	private function createOriginalUrl($uri, array $params = array()) {
		if (!empty($params)) {
			$anchor = isset($params['#']) ? '#'.$params['#'] : '';

			unset($params['#']);

			$params = array_merge(array('r'=>trim($uri, '/')), $params);
			$query  = http_build_query($params);

			return $anchor ? 'index.php?'.$query.$anchor : 'index.php?'.$query;
		} 
		return 'index.php?'.http_build_query(array('r'=>trim($uri, '/')));
	}

	public function __toString() {
		return $this->url;
	}
}
 ?>