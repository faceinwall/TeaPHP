<?php 
namespace engine\core;

use engine\core\Engine;

class Model extends Engine {
    /**
     * @var string 表名
     */
    protected $table = '';

    /**
     * @var string 主键
     */
    protected $primaryKey = 'id';

    /**
     * @var \engine\Db db实例
     */
    protected $db;

    /**
     * 若表名为空, 默认为类名
     * @access public
     */
    public function __construct() {
        $this->db = \Tea::db();
    }

    /**
     * 保存到数据库
     * @return mixed
     */
    public function save() {
        if (empty($this->attributes)) {
            return false;
        }
        $pk = isset($this->attributes[$this->primaryKey]) ? $this->attributes[$this->primaryKey] : '';
        // 新增
        if (!$pk) {
            return $this->db->insert($this->table, $this->attributes);
        }
        // 更新
        $attributes = $this->attributes;
        unset($attributes, $this->primaryKey);
        return $this->db->update($this->table, $attributes, array($this->primaryKey => $pk));
    }
}