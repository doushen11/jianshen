<!--_meta 作为公共模版分离出去-->
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
<script type="text/javascript" src="http://lib.h-ui.net/html5.js"></script>
<script type="text/javascript" src="http://lib.h-ui.net/respond.min.js"></script>
<script type="text/javascript" src="http://lib.h-ui.net/PIE_IE678.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="http://static.h-ui.net/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="http://static.h-ui.net/h-ui/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="http://lib.h-ui.net/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="http://lib.h-ui.net/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="http://static.h-ui.net/h-ui/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="http://static.h-ui.net/h-ui/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<!--/meta 作为公共模版分离出去-->

<title>添加管理员 - 管理员管理 - H-ui.admin v2.4</title>
<meta name="keywords" content="H-ui.admin v2.3,H-ui网站后台模版,后台模版下载,后台管理系统模版,HTML后台模版下载">
<meta name="description" content="H-ui.admin v2.3，是一款由国人开发的轻量级扁平化网站后台模板，完全免费开源的网站后台管理系统模版，适合中小型CMS后台系统。">
</head>
<body style="width:90%;">
<article class="page-container">
	<form class="form form-horizontal" id="form-admin-add">
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>管理员：</label>
		<div class="formControls col-xs-8 col-sm-9">
			<input type="text" class="input-text" placeholder="" id="adminName" name="adminName" value="<?php echo $result["username"];?>" disabled="disabled" readonly="readonly">
		</div>
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>初始密码：</label>
		<div class="formControls col-xs-8 col-sm-9">
			<input type="password" class="input-text" autocomplete="off" value="" placeholder="密码" id="password" name="password">
		</div>
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>确认密码：</label>
		<div class="formControls col-xs-8 col-sm-9">
			<input type="password" class="input-text" autocomplete="off"  placeholder="确认新密码" id="password2" name="password2">
		</div>
	</div>

		<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"></label>
		<div class="formControls col-xs-8 col-sm-9" id="msg_shows">
			
		</div>
	</div>
	<div class="row cl">
		<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
			<input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;修改&nbsp;&nbsp;">
		</div>
	</div>
	</form>
</article>

<!--_footer 作为公共模版分离出去--> 
<script type="text/javascript" src="http://lib.h-ui.net/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="http://lib.h-ui.net/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="http://lib.h-ui.net/icheck/jquery.icheck.min.js"></script> 
<script type="text/javascript" src="http://lib.h-ui.net/jquery.validation/1.14.0/jquery.validate.min.js"></script> 
<script type="text/javascript" src="http://lib.h-ui.net/jquery.validation/1.14.0/validate-methods.js"></script> 
<script type="text/javascript" src="http://lib.h-ui.net/jquery.validation/1.14.0/messages_zh.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui.admin/js/H-ui.admin.js"></script> 
<!--/_footer /作为公共模版分离出去--> 

<!--请在下方写此页面业务相关的脚本--> 
<script type="text/javascript">
var form_loads=1;
$(function(){
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});
	
	$("#form-admin-add").validate({
		rules:{
			adminName:{
				required:true,
				minlength:4,
				maxlength:16
			},
			password:{
				required:true,
			},
			password2:{
				required:true,
				equalTo: "#password"
			},
			sex:{
				required:true,
			},
			phone:{
				required:true,
				isPhone:true,
			},
			email:{
				required:true,
				email:true,
			},
			adminRole:{
				required:true,
			},
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			//var index = parent.layer.getFrameIndex(window.name);
			//parent.layer.close(index);
			if(form_loads==1){
				var adminName=$("#adminName").val().replace(/(^\s*)|(\s*$)/g,"");
				var password=$("#password").val().replace(/(^\s*)|(\s*$)/g,"");
				$.ajax({url:"<?php echo http_url();?>admin/admins/edits_subs/<?php echo $result["id"];?>",
				type: 'POST', 
				data:{username:adminName,passwd:password}, 
				dataType: 'html',
				timeout: 10000, 
					error: function(){
						$("#msg_shows").html('<strong style="color:#cc0000">抱歉：网络连接失败，请您稍后再试！</strong>');
						form_loads=1;
					},
					beforeSend:function(){$("#msg_shows").html('<strong style="color:#999">数据正在提交中，请稍等！</strong>');form_loads=2;},
					success:function(result){
						form_loads=1;
						result=result.replace(/(^\s*)|(\s*$)/g,"");
						$("#msg_shows").html('');
						if(result.indexOf("|")>=0)
						{
							arr=result.split("|");
							if(arr[0]==10000)
							{
								//$("#adminName").val("");
								$("#password").val("");
								$("#password2").val("");
								$("#msg_shows").html('<strong style="color:green">成功修改一条管理员信息,一秒半后自动关闭！</strong>');
								
								setTimeout("close_indexs()",1500);
							}
							else
							{
								$("#msg_shows").html('<strong style="color:#cc0000">' + arr[1] + '</strong>');
								if(arr[0]==20000)
								{
									location='<?php echo http_url();?>admin/login/indexs';
								}
							}
						}
						else
						{
							$("#msg_shows").html('<strong style="color:#cc0000">抱歉：网络连接失败，请您稍后再试！</strong>');
						}
					} 
				});	
			}
			else
			{
				alert("尚有程序加载中，请结束后再次操作！");	
			}		
			//alert('ajaxs');
			//$(form).ajaxSubmit();
			//var index = parent.layer.getFrameIndex(window.name);
			//parent.$('.btn-refresh').click();
			//parent.layer.close(index);
		}
	});
});

function close_indexs()
{
	var index = parent.layer.getFrameIndex(window.name);
	parent.$('#wysx').click();
	parent.layer.close(index);
	

}
</script> 
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>