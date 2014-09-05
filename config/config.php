<?php 
return array(
	//数据库配置
	'dbtype'   =>'pdomysql', // for dbbuilder db engine
	'mdbtype'  => 'mysql', //for medoo db egnine
	'hostname' => 'localhost',
	'database' => 'test',
	'username' => 'root',
	'password' => '',
	'table_prefix' => 'tm_',

	//模板配置
	'template_dir'    => 'view', //模板目录
	'template_engine' => 'phphtml',//模析引擎 phphtml->原生php twig->twig模板

	//缓存	
	'cache_dir'       => 'cache',
	'cache_life_time' => 300, //缓存周期
	'page_cache_dir'  => 'page_cache', //页面缓存目录
	'db_cache_dir'    => 'db_cache', //数据库缓存目录

	//日志
	'log_handler' => 'file', //系统日志保存方式 file->文件存储 db->数据库
	'log_level'   => 1, //日志等级
	'log_dir'     => 'logs',

	//session & cookie
	'session' => true,

	//第三方类库目录 (vendor)
	'vendor_dir' => 'vendor'
);
 ?>