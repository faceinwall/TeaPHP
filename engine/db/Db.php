<?php
namespace engine\Db;

class Db {
    /**
     * pdo实例
     * @var object pdo
     */
    protected $pdo;

    /**
     * statement实例
     * @var object statement
     */
    protected $statement;

    /**
     * 数据表前缀
     * @var string 
     */
    public $table_prefix = '';

    /**
     * 构造函数
     * @param array $config
     */
    public function __construct($config) {
        $this->table_prefix = $config['table_prefix'];
        switch($config['db_type']) {
            case 'pgsql':
            case 'postgresql':
            case 'mysql':
                $this->mPdo($config['db_type'], $config['hostname'], $config['username'], $config['password'], $config['database'], $config['db_port']);
                break;
            case 'sqlite':
                $this->sqlitePdo($config['sqlite_path']);
                break;
            default:
                throw new \Exception("database type not supported");
        }
    }

    /**
     * mysql, postgre, mmsql
     * @param string $drive
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @param string $database
     * @param string $port
     * @throws Exception
     */
    public function mPdo($drive, $hostname, $username, $password, $database, $port = '3306') {
        try {
            $this->pdo = new \PDO($drive.":host=" . $hostname . ";port=" . $port . ";dbname=" . $database, $username, $password );
        } catch (\PDOException $e) {
            throw new \Exception('failed to connect to database. reason: \'' . $e->getMessage() . '\'');
        }
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * sqlite pdo
     * @param string $path
     * @throws Exception
     */
    public function sqlitePdo($path) {
        try {
            $this->pdo = new \PDO("sqlite:$path");
        } catch (\PDOException $e) {
            throw new \Exception('failed to connect to database. reason: \'' . $e->getMessage() . '\'');
        }
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * 获取pdo实例
     * @return object pdo
     */
    public function getPdo() {
        return $this->pdo;
    }

    /**
     * {table} -> oc_table
     * @param string $sql
     * @return string
     */
    public function replace_quote($sql) {
        $sql = preg_replace('/\{\s*(\S+)\s*\}/', $this->table_prefix.'${1}', $sql);
        return $sql;
    }

    /**
     * 准备一条即将执行的sql
     * @param string $sql
     * @return $this
     */
    public function prepare($sql) {
        $sql = $this->replace_quote($sql);
        $this->statement = $this->pdo->prepare($sql);
        return $this;
    }

    /**
     * 绑定sql语句参数
     * @param array $variables
     * @return $this
     */
    public function bind(array $variables) {
        $i = 1;
        foreach ($variables as $var) {
            $this->statement->bindParam($i, $var, is_string($var) ? \PDO::PARAM_STR: \PDO::PARAM_INT);
            $i++;
        }
        return $this;
    }

    /**
     * 绑定sql语句参数 
     * @param string $parameter
     * @param string $variable
     * @param int $data_type
     * @param int $length
     * @return $this
     */
    public function bindParam($parameter, $variable, $data_type = \PDO::PARAM_STR, $length = 0) {
        if ($length) {
            $this->statement->bindParam($parameter, $variable, $data_type, $length);
        } else {
            $this->statement->bindParam($parameter, $variable, $data_type);
        }
        return $this;
    }

    /**
     * 执行prepare sql
     * @return mixed
     * @throws Exception
     */
    public function execute() {
        try {
            if ($this->statement && $this->statement->execute()) {
                $data = array();

                while ($row = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->row = (isset($data[0])) ? $data[0] : array();
                $result->rows = $data;
                $result->num_rows = $this->statement->rowCount();

                return $result;
            }
        } catch (\PDOException $e) {
            throw new \Exception('error: ' . $e->getMessage() . ' error code : ' . $e->getCode());
        }
        return false;
    }

    /**
     * 执行sql查询
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function query($sql, $params = array()) {
        $sql = $this->replace_quote($sql);

        $this->statement = $this->pdo->prepare($sql);
        $result = false;
        try {
            if ($this->statement && $this->statement->execute($params)) {
                $data = array();
                while ($row = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->row = (isset($data[0]) ? $data[0] : array());
                $result->rows = $data;
                $result->num_rows = $this->statement->rowCount();
            }
        } catch (\PDOException $e) {
            throw new \Exception('error: ' . $e->getMessage() . ' error Code : ' . $e->getCode() . ' <br />' . $sql);
        }

        if ($result) {
            return $result;
        } else {
            $result = new \stdClass();
            $result->row = array();
            $result->rows = array();
            $result->num_rows = 0;
            return $result;
        }
    }

    /**
     * 插入一条记录
     * @param $table 数据表
     * @param $data 记录
     * @return object
     */
    public function insert($table, $data) {
        if (!is_array($data)) {
            return false;
        }
        $sql = "INSERT INTO " . $this->table_prefix . "{$table} SET ";
        foreach ($data as $field => $value) {
            $sql .= "`{$field}` = '{$this->escape((string)$value)}',";
        }
        $sql = substr($sql, 0, -1);
        return $this->query($sql);
    }

    /**
     * 更新一条记录
     * @param $table 数据表
     * @param $data 记录
     * @param $conditions 条件
     * @return object
     */
    public function update($table, $data, $conditions) {
        if (!is_array($data) || !is_array($conditions) || empty($data) || empty($conditions)) {
            return false;
        }
        $sql = "UPDATE" . $this->table_prefix . "{$table} SET ";
        foreach ($data as $field => $value) {
            $sql .= "SET `{$field}` = '{$this->escape((string)$value)}',";
        }
        $sql = substr($sql, 0, -1);
        $sql .= " WHERE ";
        foreach ($conditions as $field => $value) {
            $sql .= " `{$field}` = '{$this->escape((string)$value)}' AND ";
        }
        $sql = substr($sql, 0, -5);
        return $this->query($sql);
    }

    /**
     * 转义
     * @param string $value
     * @return string
     */
    public function escape($value) {
        return str_replace(array("\\", "\0", "\n", "\r", "\x1a", "'", '"'), array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'), $value);
    }

    /**
     * 返回影响行数
     * @return int
     */
    public function countAffected() {
        if ($this->statement) {
            return $this->statement->rowCount();
        } else {
            return 0;
        }
    }

    /**
     * 获取最后插入ID
     * @return int
     */
    public function getLastId() {
        return $this->pdo->lastInsertId();
    }

    /**
     * 是否已连接数据库
     * @return boolean
     */
    public function isConnected() {
        if ($this->pdo) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 数据表是否存在
     * @param string $table
     * @return boolean
     */
    public function hasTable($table) {
        try {
            $sql = "SELECT 1 FROM" . $this->table_prefix. $table . " LIMIT 1";
            $result = $this->pdo->query($sql);
        } catch (\Exception $e) {
            return false;
        }
        return $result !== false;
    }
}