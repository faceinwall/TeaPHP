<?php include '/view/layout/header.php' ?>
	<form action="<?= new \engine\net\Url('menu/index') ?>" method="post">
		<p>
			<label>父菜单:</label>
			<select name="pid">
				<option value="1">首页</option>
			</select>
		</p>
		<p>
			<label>菜单名称</label>
			<input type="text" name="name" value>
		</p>
		<p>
			<label>菜单类型 <?=(\library\Auth::powerCheck('菜单管理','add')) ?></label>

			<input type="radio" name="type" value="1">本系统模块
			<input type="radio" name="type" value="2">外链
		</p>
		<hr>
		<fieldset>
			<legend>页面元素</legend>
			<table id="elem-box">
				<tr>
					<td>页面元素</td>
					<td>名称</td>
					<td>标识符(英文)</td>
					<td>描术(50字以内)</td>
					<td><a href="javascript:void(0);" id="elem-add">添加</a></td>
				</tr>
				<tr>
					<td>新增页面元素1:</td>
					<td><input type="text" name="elements_name[]" value="新增"></td>
					<td><input type="text" name="elements_identify[]" value="add"></td>
					<td><input type="text" name="elements_describe[]" value="新增项目"></td>
					<td><a href="javascript:void(0);" class="elem-del">删除</a></td>
				</tr>
				<tr>
					<td>新增页面元素2:</td>
					<td><input type="text" name="elements_name[]" value="删除"></td>
					<td><input type="text" name="elements_identify[]" value="delete"></td>
					<td><input type="text" name="elements_describe[]" value="删除项目"></td>
					<td><a href="javascript:void(0);" class="elem-del">删除</a></td>
				</tr>
				<tr>
					<td>新增页面元素3:</td>
					<td><input type="text" name="elements_name[]" value="修改"></td>
					<td><input type="text" name="elements_identify[]" value="update"></td>
					<td><input type="text" name="elements_describe[]" value="修改项目"></td>
					<td><a href="javascript:void(0);" class="elem-del">删除</a></td>
				</tr>
				<tr>
					<td>新增页面元素4:</td>
					<td><input type="text" name="elements_name[]" value="查看"></td>
					<td><input type="text" name="elements_identify[]" value="view"></td>
					<td><input type="text" name="elements_describe[]" value="查看项目"></td>
					<td><a href="javascript:void(0);" class="elem-del">删除</a></td>
				</tr>
			</table>
		</fieldset>	

		<p>
			<input type="submit" value="提交">
		</p>	
	</form>
<?php include '/view/layout/footer.php' ?>

<script type="text/javascript">
$(function () {
	$('#elem-add').click(function(){
		var trLength = $('table#elem-box tr').length;
		var newLine = ''
		+'<tr>'
		+'<td>新增页面元素'+trLength+':</td>'
		+'<td><input type="text" name="elements_name[]" value></td>'
		+'<td><input type="text" name="elements_identify[]" value></td>'
		+'<td><input type="text" name="elements_describe[]"></td>'
		+'<td><a href="javascript:void(0);" class="elem-del">删除</a></td>';

		$('table#elem-box tr:last').after(newLine);
	})
	$('table#elem-box').on('click', '.elem-del', function(){
		$(this).closest('tr').remove();
	})
})
</script>