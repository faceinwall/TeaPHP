<?php 
namespace engine\util;

class Config {

     /**
      * @var array 配置数组
      */
     private $config = array();

     public function __construct(){
          $filename = SITE_PATH.DIRECTORY_SEPARATOR.'config'.
               DIRECTORY_SEPARATOR.'config.php';
                              
          $this->config = $this->parseConfig($filename);
     }

     /**
      * 设置配置值
      * @param string $name
      * @param mixed $value
      * @throws \Exception
      */
     public function __set($name, $value) {
          if (array_key_exists($name, $this->config)) {
               $this->$name = $value;
          } else {
               throw new \Exception("Invalid config item `$name`", 1);
          }
     }

     /**
      * 返回配置值
      * @param string $name
      * @return mixed
      * @throws \Exception
      */
     public function __get($name) {
          if (array_key_exists($name, $this->config)) {
               return $this->config[$name];
          }
          return null;
     }

     /**
      * @解析配置文件
      * @param string $filename
      * @return array
      */
     private function parseConfig($filename) {
          if (file_exists($filename)){
               return include($filename);
          } else {
               throw new \Exception("wrong config file path `$filename`", 1);
          }
     }
}
 ?>