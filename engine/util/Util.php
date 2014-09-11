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

	/**
	 * 把返回的数据集转换成Tree
	 * @param array $list 要转换的数据集
	 * @param string $pid parent标记字段
	 * @param string $level level标记字段
	 * @return array
	 */
	public static function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
	    // 创建Tree
	    $tree = array();
	    if(is_array($list)) {
	        // 创建基于主键的数组引用
	        $refer = array();
	        foreach ($list as $key => $data) {
	            $refer[$data[$pk]] =& $list[$key];
	        }
	        foreach ($list as $key => $data) {
	            // 判断是否存在parent
	            $parentId =  $data[$pid];
	            if ($root == $parentId) {
	                $tree[] =& $list[$key];
	            }else{
	                if (isset($refer[$parentId])) {
	                    $parent =& $refer[$parentId];
	                    $parent[$child][] =& $list[$key];
	                }
	            }
	        }
	    }
	    return $tree;
	}

	/**
	 * 将list_to_tree的树还原成列表
	 * @param  array $tree  原来的树
	 * @param  string $child 孩子节点的键
	 * @param  string $order 排序显示的键，一般是主键 升序排列
	 * @param  array  $list  过渡用的中间数组，
	 * @param int $level 递归层级
	 * @return array        返回排过序的列表数组
	 */
	public static function tree_to_list($tree, $child = '_child', $order='id', &$list = array(), $level = 0){
	    if(is_array($tree)) {
	        $refer = array();
	        foreach ($tree as $key => $value) {
	            $value['level'] = $level;
	            $reffer = $value;
	            if(isset($reffer[$child])){
	                unset($reffer[$child]);
	                tree_to_list($value[$child], $child, $order, $list, ++$level);
	                --$level;
	            }
	            $list[] = $reffer;
	        }
	        $list = list_sort_by($list, $order, $sortby='asc');
	    }
	    return $list;
	}
}
 ?>