<?php include '/view/layout/header.php' ?>
<style type="text/css">
*{margin:0;padding: 0;}
.pagination span {float: left;height:20px;line-height: 20px;margin:0 2px;}
.pagination span a{float: left; width: 40px;margin: 0px 2px;border: 1px solid #ccc; background: #fdfdfd;text-align: center;}
.pagination span .page-first,
.pagination span .page-last,
.pagination span .page-prev,
.pagination span .page-next, {width: 60px; }
.pagination .current {background: green;}
.pagination a {text-decoration: none;}
</style>
<div class='data-grid'>
	<?= $table ?>
</div>
<div class='pagination'>
	<?= $page ?>
</div>
<?php include '/view/layout/footer.php' ?>