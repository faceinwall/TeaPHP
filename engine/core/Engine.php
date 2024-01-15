<?php 
namespace engine\core;

class Engine{
    /**
     * 属性
     * @var array
     */
    protected $attributes = array();

    /**
     * 设置engine类的属性
     * @param string $name 属性名称
     * @param mixed $value 属性值
     */
    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }

    /**
     * Gets the value of a engine property.
     * @param string $name the property name
     * @return mixed the property value
     */
    public function __get($name) {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    /**
     * 扩展engine类的属性
     * @param array $properties 属性数组
     */
    public function configuration(array $properties) {
        foreach ($properties as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * 加载第三方vendor目录的类库, eg: $this->vendor('path.to.class#php');
     * @param $filename 类库名称
     * @param string $ext 类库扩展名
     */
    public function vendor($classname, $ext = '.php') {
        $config = new \engine\util\Config();
        //set default vendor
        $defaultVendorDir = $config->vender_dir ? $config->vender_dir : 'vendor';
        //separate . and #
        $classPath = str_replace(array('.', '#'), array(DIRECTORY_SEPARATOR, '.'), $classname);
        //class file
        $vendorFile = SITE_PATH. DIRECTORY_SEPARATOR.$defaultVendorDir.DIRECTORY_SEPARATOR.$classPath.$ext;

        if (file_exists($vendorFile)) {
            require_once $vendorFile;
        }
    }

    /**
     * 加载系统的帮助函数
     * @param string $filename 文件名(不含后缀)
     * @throws \Exception
     */
    public function helper($filename) {
        $config = new \engine\util\Config();
        $defaultHelperDir = 'helper';

        if ($config->helper) {
            $file_path = $config->header. DIRECTORY_SEPARATOR . $filename . '.php';
        } else {
            $file_path = $defaultHelperDir .DIRECTORY_SEPARATOR. $filename .'.php';
        }
        if (!file_exists($file_path)) {
            throw new \Exception("the `$file_path` does not exists!", 1);
        }

        include_once($file_path);
    }
}