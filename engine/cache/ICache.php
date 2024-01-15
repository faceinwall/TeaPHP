<?php 
namespace engine\cache;

interface ICache {
    public function set($key, $value);
    public function get($key);
    public function setCacheDir($cacheDir);
    public function setCacheTime($time);
}