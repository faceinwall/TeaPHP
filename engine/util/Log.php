<?php 
namespace engine\util;

class Log {
	/**
	 * @var int 日志等级
	 *
	 */
	const LG_INFO    = 1;	//信息
	const LG_NOTIC   = 2;	//注意
	const LG_WARNING = 3;	//警告
	const LG_ERROR   = 4;	//错误

	/**
	 * 构造函数, 设置为私有
	 *
	 */
	private function __construct(){
	}

	/**
	 * 记录日志信息
	 * @param $fn 		写入函数的名称
	 * @param $args 	日志参数
	 * @throws \Exception
	 */
	public static function __callStatic($fn, $args) {
		if ( $args[1] <= \Tea::app()->config('log_level')) {
			$line = array(
				'log_function' => $fn,
				'log_message'  => $args[0],
				'log_level'    => $args[1],
				'log_file'     => $args[2],
				'log_line'     => $args[3],
			);

			switch (\Tea::app()->config('log_handler')) {
				case 'file':
					$line['log_time'] = date('Y-m-d H:i:s', time());
					$json = json_encode($line)."\n";

					//日志文件
					$logfile= \Tea::app()->config('log_dir') 
						.DIRECTORY_SEPARATOR. 'log_'.date('Y-m-d');

					if ($fd = fopen($logfile, 'a+')) {
						if (!fwrite($fd, $json)) {
							throw new \Exception("Unable to write to log file", 1);
						}
						fclose($fd);
					}
					break;
				case 'database':
					break;
				default:
					throw new \Exception("Invalid log option!", 1);
			}
		}
	}
}
 ?>