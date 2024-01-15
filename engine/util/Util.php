<?php 
namespace engine\util;

class Util {
    /**
     * 检测是否为关联数组
     * @param array $array
     * @return boolean
     */
    public static function is_assoc(array $array) {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}