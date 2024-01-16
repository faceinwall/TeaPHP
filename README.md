TeaPHP是一个轻量级的开发框架, 简单,易用.
======
# 环境要求
TeaPHP需要PHP 5.3+的开发环境

# 许可 

TeaPHP 使用 MIT license, 可随意修改,发布

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
TeaPHP使用如下的路由方式: http://example.com?r=module/controller/action

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
模型定义
```php
class Blog {
    public $table = 'blog';
}

$blog = new Blog();
$blog->title = 'title';
$blog->content = 'content';
$blog->save();

```
