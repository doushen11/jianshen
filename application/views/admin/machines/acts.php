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
<script src="<?php echo base_url();?>/public/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>/public/layer_pc/layer.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/admins/static/h-ui.admin/css/style.css" />
<script>
	function reads()
	{
		//alert(1);
		$.ajax({url:"<?php echo http_url();?>admin/machines/acts_ajax", 
			type: 'POST', 
			dataType: 'html', 
			timeout: 15000, 
			error: function(){
				layer.closeAll();
				reads();
			},
			beforeSend:function(){
				var index = layer.load(3,{
					shade: [0.2,'#333333'] //0.1透明度的白色背景
				});									
			},
			success:function(result){
				layer.closeAll();
				result=result.replace(/(^\s*)|(\s*$)/g,"");
				//alert(result);
				document.getElementById("inners").innerHTML=result;
			}
		});			
	}
	
	
	$(document).ready(function(){
		reads();
	});
	
	var form_status=0;
	
	function ups(id)
	{
		if(form_status==0 || form_status==3){
			$.ajax({url:"<?php echo http_url();?>admin/machines/ups?id=" + id, 
			type: 'POST', 
			dataType: 'html', 
			timeout: 15000, 
				error: function(){
					form_status=3;
										
				},
				beforeSend:function(){
					layer.closeAll();
						var index = layer.load(3,{
							shade: [0.2,'#333333'] //0.1透明度的白色背景
						});
					
					form_status=1;	
				},
				success:function(result){
					form_status=3;
					layer.closeAll();
					result=result.replace(/(^\s*)|(\s*$)/g,"");
					reads();
				} 
			});
		}else{
			layer.alert('还有其他进程正在执行中，请您稍后再试！', {
				icon: 7,
				skin: 'layer-ext-moon'
			})					
		}
	
	}
	
	function downs(id)
	{
		if(form_status==0 || form_status==3){
			$.ajax({url:"<?php echo http_url();?>admin/machines/downs?id=" + id, 
			type: 'POST', 
			dataType: 'html', 
			timeout: 15000, 
				error: function(){
					form_status=3;
										
				},
				beforeSend:function(){
					layer.closeAll();
						var index = layer.load(3,{
							shade: [0.2,'#333333'] //0.1透明度的白色背景
						});
					
					form_status=1;	
				},
				success:function(result){
					form_status=3;
					layer.closeAll();
					result=result.replace(/(^\s*)|(\s*$)/g,"");
					reads();
				} 
			});
		}else{
			layer.alert('还有其他进程正在执行中，请您稍后再试！', {
				icon: 7,
				skin: 'layer-ext-moon'
			})					
		}
	
	}
</script>
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>管理员列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 器材分类 <span class="c-gray en">&gt;</span> 器材分类列表 <a id="shuaxins" class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i id="wysx" class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">

	<div class="cl pd-5 bg-1 bk-gray mt-20">  <span class="r">共有数据：<strong id="nums_it">3</strong> 条</span> </div>
    <span id="inners">
		<p align="center" style="padding-top:15px;"><img src="/public/images/0907091937a5e83557ebfd840c.gif" /></p>
    </span>
</div>

</body>
</html>