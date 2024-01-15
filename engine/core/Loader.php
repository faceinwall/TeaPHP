<?php 
namespace engine\core;

class Loader {
    /**
     * @var Autoload directories
     */
    protected static $dirs = array();

    
    /**
     * Starts/stops autoloader.
     * @param bool $enabled Enable/disable autoloading
     * @param mixed $dirs Autoload directories
     */
    public static function autoLoad($enabled = true, $dirs) {
        if ($enabled) {
            spl_autoload_register(array(__CLASS__, 'loadClass'));
        } else {
            spl_autoload_unregister(array(__CLASS__, 'loadClass'));
        }

        if (!empty($dirs) && is_dir($dirs)) {
            self::addDirectory($dirs);
        }
    }

    /**
     * Autoloads classes
     * @param string $classname Class name
     */
    public static function loadClass($classname) {
        $class_file = str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
        foreach (self::$dirs as $dir) {
            $file = $dir . DIRECTORY_SEPARATOR . $class_file;
            if (file_exists($file)) {
                require_once $file; 
                return;
            }
        }
        throw new \Exception("class file `$class_file` does not exists!", 1);
    }

    /**
     * Adds a directory for autoloading classes.
     * 
     * @param mixed $dir Directory path
     */
    public static function addDirectory($dir) {
        if (is_array($dir) || is_object($dir)) {
            foreach ($dir as $value){
                self::addDirectory($value);
            }
        }
        else if (is_string($dir)) {
            if (!in_array($dir, self::$dirs)) self::$dirs[] = $dir;
        }
    }
}
 ?>