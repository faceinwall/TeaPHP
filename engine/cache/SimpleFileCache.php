<?php 
namespace engine\cache;
use engine\cache\ICache;

class SimpleFileCache implements ICache {
	/**
	 * @var string 缓存目录
	 */
	private $cache_dir = 'cache';

	/**
	 * @var int 缓存生命周期
	 */
	private $cache_lifetime = 300;

	/**
	 * 构造函数
	 */
	public function __construct() {
		//TO DO
	}

	/**
	 * 设置key-value缓存
	 * @param string $key 键
	 * @param mixed 值
	 */
	public function set($key, $value) {
		$serializations = serialize($value);
		return $this->writeCache($this->getCacheFile($key), $serializations);
	}

	/**
	 * 得到缓存值
	 * @param string 缓存键名
	 * @return mixed
	 */
	public function get($key) {
		$filename = $this->getCacheFile($key);

		if (!file_exists($filename) || !is_writable($filename)) {
			return false;
		}
		//缓存没有过期
		if (time() < (fileatime($filename) + $this->cache_lifetime)) {
			$value = $this->readCache($filename);
			if (false !== $value) {
				return unserialize($value);
			}
		}
		return false;
	}

	/**
	 * 设置缓存目录名称
	 * @param string $cacheDir 目录名称
	 * @throws \Exception
	 */
	public function setCacheDir($cacheDir = null) {
		if (is_null($cacheDir)) {
			$cacheDir = \Tea::app()->config('cache_dir') .DIRECTORY_SEPARATOR.
				\Tea::app()->config('db_cache_dir');
		}
		if (!is_dir($cacheDir)) {
			if (mkdir($cacheDir) == false) {
				throw new \Exception("The cache dir `$cacheDir` does no exists!", 1);
			}
		}	
		if (!is_writable($cacheDir)) {
			throw new \Exception("The cache directory `$cacheDir` is not writable", 1);
		} else {
			$this->cache_dir = $cacheDir;
		}
	}

	/**
	 * 设置缓存周期
	 * @param int $time 缓存周期时间
	 * @throws \Exception
	 */
	public function setCacheTime($time) {
		if (is_int($time)) {
			$this->cache_lifetime = $time;
		} else{
			throw new \Exception("invalid parameter `\$time`, need interger!", 1);
		}
	}

	/**
	 * 返回缓存目标的文件名
	 * @param string $key 缓存的键名
	 * @return string
	 */
	private function getCacheFile($key) {
		return $this->cache_dir .DIRECTORY_SEPARATOR. md5($key);
	}

	/**
	 * 写入缓存
	 * @param string $filename 	文件名
	 * @param mixed $data 缓存的项目
	 * @param string $mode 文件打开的模式
	 * @return boolean
	 */
	private function writeCache($filename, $data, $mode = 'wb') {
		try {
			//若存在缓存且已过期
			if (file_exists($filename) && time() > (fileatime($filename) + $this->cache_lifetime)) {
				//删除
				unlink($filename);
			}
			$fd = fopen($filename, $mode);
		} catch (\Exception $e) {
			return false;			
		}
		//设置锁
		$tries = 3;
		while ($tries > 0) {
			$locked = flock($fd, LOCK_EX | LOCK_NB);
			if (!$locked) {
				sleep(5);
				$tries--;
			} else {
				$tries = 0;
			}
		} 

		//写数据
		if ($locked) {
			fwrite($fd, $data);
			fflush($fd);
			flock($fd, LOCK_UN);
			fclose($fd);
			return true;
		}
		return false;
	}

	/**
	 * 读取缓存
	 * @param string 缓存项目的文件名
	 * @return string
	 */
	private function readCache($filename) {
		if (is_readable($filename)) {
			return file_get_contents($filename);
		}
		return false;
	}
}
 ?>