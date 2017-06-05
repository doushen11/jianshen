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
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 系统管理 <span class="c-gray en">&gt;</span> 操课时间安排列表 <a id="shuaxins" class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i id="wysx" class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">

  <div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:add_one();"  class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加一条</a></span> &nbsp;&nbsp;&nbsp; <button onClick="save_submit();" class="btn btn-primary radius" type="submit" style="background:#009933; border:#009933 1px solid;"><i class="Hui-iconfont">&#xe632;</i> 更新对应信息 </button> &nbsp;&nbsp;&nbsp; <strong style="color:#FF0000;">* 开始时间和结束时间请填写0-24的整数，对应数据请谨慎填写，更新数据不会对之前发布的课程产生影响</strong></div>
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="4">进门价格列表</th>
			</tr>
			<tr class="text-c">
			  <th width="150">开始时间</th>
				<th width="142">结束时间</th>
				<th width="98">峰值类型</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody id="inners">
        	<?php
            	foreach($query->result_array() as $array)
				{
			?>
			<tr class="text-c" id="sx_<?php echo $array["id"];?>">
				<td><input type="hidden" value="<?php echo $array["id"];?>" class="beyond_5"><input type="text" value="<?php echo $array["min"];?>" class="beyond_1"></td>
				<td><input type="text" value="<?php echo $array["max"];?>" class="beyond_2"></td>
				<td>
                <select class="beyond_3">
                	<option value="">--请选择--</option>
                	<option value="1" <?php if($array["model"]==1){?> selected<?php }?>>高峰</option>
                    <option value="2" <?php if($array["model"]==2){?> selected<?php }?>>低峰</option>
                </select>
                </td>
				<td class="td-manage"><a title="删除" href="javascript:del_its(<?php echo $array["id"];?>);" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
			</tr>
            <?php
            	}
			?>
		</tbody>
	</table>
</div>



<script>
	var form_loads=1;
	function save_submit()
	{
		var subs=1;
		var sphinx="";
		$("#inners tr").each(function(){
			var id=$(this).find(".beyond_5").val();
			var mins=$(this).find(".beyond_1").val();
			var maxs=$(this).find(".beyond_2").val();
			var types=$(this).find(".beyond_3").val();

			if(id!="" && mins!="" && maxs!="" && types!="" && parseInt(mins)>=0 && parseInt(mins)<=24 && parseInt(maxs)>=0 && parseInt(maxs)<=24)
			{
				if(sphinx=="")
				{
					sphinx=id + "{syx}" + mins + "{syx}" + maxs + "{syx}" + types;
				}
				else
				{
					sphinx=sphinx + "|_|" + id + "{syx}" + mins + "{syx}" + maxs + "{syx}" + types;
				}
			}
			else
			{
				subs=2;
				
			}
		});
		
		if(subs==1)
		{
			if(form_loads==1)
			{
				$.ajax({url:"<?php echo http_url();?>admin/times/class_b_subs",
				type: 'POST', 
				data:{sphinx:sphinx,del_id:del_id}, 
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
						if(result.indexOf("|")>=0)
						{
							arr=result.split("|");
							if(arr[0]==10000)
							{
								form_loads=2;
								layer.alert(arr[1], {
									icon: 1,
									skin: 'layer-ext-moon'
								})		
								setTimeout("location='<?php echo $_SERVER['REQUEST_URI'];?>'",1500);								
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
				layer.msg('抱歉：尚有进程数据加载中');	
			}
		}
		else
		{
			layer.msg('抱歉：请检查有些数据尚未填写');	
		}
	}

	var del_id="";
	var rowCount=1000;
	function add_one()
	{
		//添加一行
		rowCount++;  
		var newRow='<tr class="text-c" id="sx_' + rowCount + '"><td><input type="hidden" value="recsons" class="beyond_5"><input type="text" value="" class="beyond_1"></td><td><input type="text" value="" class="beyond_2"></td><td><select class="beyond_3"><option value="">--请选择--</option><option value="1">高峰</option><option value="2">低峰</option></select></td><td class="td-manage"><a title="删除" href="javascript:del_its(' + rowCount + ');" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td></tr>'; 
		$('#inners').append(newRow);		
	}
	
	function del_it(id)
	{
		$("#sx_" + id).remove();
	}
	
	function del_its(id)
	{
		$("#sx_" + id).remove();
		del_id=del_id + "," + id;
	}	
</script>
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

</script>
</body>
</html>