TeaPHP是一个轻量级的开发框, 简单,易用无须复杂的配置
======
# 环境要求
TeaPHP需要PHP 5.3+的开发环境

# 许可 

TeaPHP 使用 [MIT](http://flightphp.com/license) license, 可随意修改,发布

# 安装
1\. [下载](https://github.com/faceinwall/TeaPHP/archive/master.zip)并且解压到你网站的根目录.

# Hello World!
TeaPHP 使用命名空间的方式组织代码, 避免包的冲突

```php
class HelloController extends \engin\core\Controller {
	public function actionIndex() {
		echo '<h1>Hello, @World!</h1>';
	}
}
```

# 路由
TeaPHP使用如下的路由方式: http://example.com?r=&lt;module&gt;/&lt;controller&gt;/&lt;action&gt;

module: (可选) TeaPHP的控制层支持使用分组,更方便代码组织应用,如
<pre>
		|-...
		|-controller
		| |-blog
		| | |-BlogController.php #Blog分组下的blog控制器
		| | |-....
		| |-admin
		| | |-AdminController.php #admin分组下的admin控制器
</pre>
controller: 控制器

action: 动作名称


# 模型操作

1\.使用全局操作方式 \Tea::app()->model()

\Tea::app()->model(), 使用的数据库操作引擎是 engine\util\DbBuilder

在控制器里面使用：

```php
	//如查询blog里面id=1的记录
	\Tea::app()->model('blog')->where('id'=>1)->one();

	//如使用原生sql
	\Tea::app()->model()->sql('select * from blog where id=1')->excute();
```	

在模型中使用

```php
class Blog {
	public $table = 'blog';

	public function find() {
		return \Tea::app()->model($this->table)->where('id'=>1)->one();
	}
}
```


2\.使用model, TeaPHP的model使用开源的轻量级数据库操作引擎 medoo

```php
class Blog extends \engine\core\model {
	public $table = 'blog';	

	public function find() {
		$row = $this->db->select($this->table, array('id, title'));
	}	
}
```