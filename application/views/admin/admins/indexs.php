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
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 管理员列表 <a id="shuaxins" class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i id="wysx" class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">

	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onClick="admin_add('添加管理员','<?php echo http_url();?>admin/admins/adds','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加管理员</a></span> <span class="r">共有数据：<strong id="nums_it"><?php echo $query->num_rows();?></strong> 条</span> </div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="7">管理员列表</th>
			</tr>
			<tr class="text-c">
				<th width="40">ID</th>
				<th width="150">登录名</th>
				<th width="142">最后登录时间</th>
				<th width="98">最后登录IP</th>
				<th width="130">上上次登录时间</th>
				<th width="100">上上次登录IP</th>
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
				<td><?php echo $array["username"];?></td>
				<td><?php echo date("Y-m-d H:i:s",$array["login_time"]);?></td>
				<td><?php echo long2ip($array["login_ip"]);?></td>
				<td><?php echo date("Y-m-d H:i:s",$array["last_time"]);?></td>
				<td><?php echo long2ip($array["last_ip"]);?></td>
				<td class="td-manage"><a title="编辑" href="javascript:;" onClick="admin_edit('管理员编辑','<?php echo http_url();?>admin/admins/edits/<?php echo $array["id"];?>','1','800','500')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onClick="admin_del(this,'<?php echo $array["id"];?>')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
			</tr>
            <?php
            	}
			?>
		</tbody>
	</table>
</div>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/jquery/1.9.1/jquery.min.js"></script>  
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/laypage/1.2/laypage.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui.admin/js/H-ui.admin.js"></script> 
<script type="text/javascript">
/*
	参数解释：
	title	标题
	url		请求的url
	id		需要操作的数据id
	w		弹出层宽度（缺省调默认值）
	h		弹出层高度（缺省调默认值）
*/
/*管理员-增加*/
function admin_add(title,url,w,h){
	layer_show(title,url,w,h);
}
var form_loads=1;
/*管理员-删除*/
function admin_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		//此处请求后台程序，下方是成功后的前台处理……
		del_admins(obj,id);
		//$(obj).parents("tr").remove();
		//layer.msg('已删除!',{icon:1,time:1000});
	});
}

function del_admins(obj,id)
{
	if(form_loads==1){
		
		$.ajax({url:"<?php echo http_url();?>admin/admins/dels",
		type: 'POST', 
		data:{id:id}, 
		dataType: 'html',
		timeout: 10000, 
			error: function(){
			 	layer.closeAll();
				form_loads=1;
			},
			beforeSend:function(){ layer.closeAll();var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
			form_loads=2;},
			success:function(result){
				form_loads=1;
				layer.closeAll();
				result=result.replace(/(^\s*)|(\s*$)/g,"");
				$("#msg_shows").html('');
				if(result.indexOf("|")>=0)
				{
					arr=result.split("|");
					if(arr[0]==10000)
					{
						$(obj).parents("tr").remove();
						layer.msg('已删除!',{icon:1,time:1000});
						var nums_it=parseInt(document.getElementById("nums_it").innerHTML);
						document.getElementById("nums_it").innerHTML=nums_it-1;
					}
					else
					{
						layer.msg(arr[1],{icon:2,time:1000});
						if(arr[0]==20000)
						{
							location='<?php echo http_url();?>admin/login/indexs';
						}
					}
				}
				else
				{
					
				}
			} 
		});	
	}
	else
	{
		alert("尚有程序加载中，请结束后再次操作！");	
	}
}

/*管理员-编辑*/
function admin_edit(title,url,id,w,h){
	layer_show(title,url,w,h);
}
/*管理员-停用*/
function admin_stop(obj,id){
	layer.confirm('确认要停用吗？',function(index){
		//此处请求后台程序，下方是成功后的前台处理……
		
		$(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_start(this,id)" href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>');
		$(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">已禁用</span>');
		$(obj).remove();
		layer.msg('已停用!',{icon: 5,time:1000});
	});
}

/*管理员-启用*/
function admin_start(obj,id){
	layer.confirm('确认要启用吗？',function(index){
		//此处请求后台程序，下方是成功后的前台处理……
		
		
		$(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_stop(this,id)" href="javascript:;" title="停用" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a>');
		$(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
		$(obj).remove();
		layer.msg('已启用!', {icon: 6,time:1000});
	});
}
</script>
</body>
</html>