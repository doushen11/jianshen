<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/favicon.ico" >
<LINK rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/html5.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/respond.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/PIE_IE678.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/static/h-ui.admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>管理员列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 文章管理 <span class="c-gray en">&gt;</span> 文章列表 <a id="shuaxins" class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i id="wysx" class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">

	<div class="cl pd-5 bg-1 bk-gray mt-20">  <span class="r">共有数据：<strong id="nums_it"><?php echo $query->num_rows();?></strong> 条</span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="4">文章列表</th>
			</tr>
			<tr class="text-c">
				<th width="40">ID</th>
				<th width="150">标题</th>
				<th width="142">最后更新时间</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
        	<?php
            	foreach($query->result_array() as $array)
				{
			?>
			<tr class="text-c">
				<td><?php echo $array["id"];?></td>
				<td><?php echo $array["title"];?></td>
				<td><?php echo date("Y-m-d H:i:s",$array["times"]);?></td>
				<td class="td-manage"><a title="编辑" href="<?php echo http_url();?>admin/article/edits/<?php echo $array["id"];?>"  class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a></td>
			</tr>
            <?php
            	}
			?>
		</tbody>
	</table>
</div>

</body>
</html>