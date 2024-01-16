<?php 
// change the following paths if necessary
require_once __DIR__.'/init/bootstrap.php';
$config = include(__DIR__.'/config/config.php');

// remove the following lines when in production mode
defined('DEBUG') or define('DEBUG',true);
defined('SITE_PATH') or define('SITE_PATH', __DIR__);

\Tea::createWebApplication($config)->run();
 ?>