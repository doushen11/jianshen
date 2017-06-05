<!--_meta 作为公共模版分离出去-->
<?php
	require FCPATH."config/push.inc.php";
	//require FCPATH."config/sys.inc.php";
	//require FCPATH."config/money_inc.php";
?>
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
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<!--/meta 作为公共模版分离出去-->
<script>
	var form_loads=1;

	function c_imgs()
	{
		$("#files").click();
	}
	
	function uploads()
	{
		if(form_loads==1)
		{
			layer.closeAll();
			var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
			form_loads=2;	
			document.recsons.submit();	
		}
		else
		{
			layer.msg('抱歉：还有进程数据正在处理中，请稍等...');
		}
	}
	
	function stopUpload(result){
		layer.closeAll();
		form_loads=1;	
		//$("#sub_avatar").val("");	
		document.getElementById("file_inner").innerHTML='<input type="file" id="files" name="files" value="" onChange="uploads();">';
		if(result.indexOf("|")>0){
			arr=result.split("|");
			if(arr[0]==10000){
				layer.msg('上传成功');
				$("#tupian").val(arr[1]);
				document.getElementById("tupian_inner").innerHTML='<img src="/' + arr[1] + '" style="max-height:180px; max-width:180px;">';
				document.getElementById("tong").innerHTML='<p align="center"><img src="/' + arr[1] + '" style="max-height:340px; max-width:520px;"></p>';
							
			}else if(arr[0]==20000){
				layer.msg('登录状态已失效');		
				setTimeout("location='<?php echo http_url();?>admin/login/indexs'",1500);			
			}else if(arr[0]==30000){	
				layer.msg(arr[1]);				
			}
		}else{
			form_loads=1;	
			layer.msg('操作过程出错，请您稍后再试！');					
		}	
	}
	
	function c_imgs1()
	{
		$("#files1").click();
	}
	
	function uploads1()
	{
		if(form_loads==1)
		{
			layer.closeAll();
			var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
			form_loads=2;	
			document.recsons1.submit();	
		}
		else
		{
			layer.msg('抱歉：还有进程数据正在处理中，请稍等...');
		}	
	}
	
	function stopUpload1(result){
		layer.closeAll();
		form_loads=1;	
		//$("#sub_avatar").val("");	
		document.getElementById("file_inner1").innerHTML='<input type="file" id="files1" name="files" value="" onChange="uploads1();">';
		if(result.indexOf("|")>0){
			arr=result.split("|");
			if(arr[0]==10000){
				layer.msg('上传成功');
				$("#tupian1").val(arr[1]);
				document.getElementById("tupian_inner1").innerHTML='<img src="/' + arr[1] + '" style="max-height:180px; max-width:180px;">';
				document.getElementById("tong1").innerHTML='<p align="center"><img src="/' + arr[1] + '" style="max-height:340px; max-width:520px;"></p>';
							
			}else if(arr[0]==20000){
				layer.msg('登录状态已失效');		
				setTimeout("location='<?php echo http_url();?>admin/login/indexs'",1500);			
			}else if(arr[0]==30000){	
				layer.msg(arr[1]);				
			}
		}else{
			form_loads=1;	
			layer.msg('操作过程出错，请您稍后再试！');					
		}	
	}
	
	function c_imgs2()
	{
		$("#files2").click();
	}
	
	function uploads2()
	{
		if(form_loads==1)
		{
			layer.closeAll();
			var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
			form_loads=2;	
			document.recsons2.submit();	
		}
		else
		{
			layer.msg('抱歉：还有进程数据正在处理中，请稍等...');
		}	
	}
	
	function stopUpload2(result){
		layer.closeAll();
		form_loads=1;	
		//$("#sub_avatar").val("");	
		document.getElementById("file_inner2").innerHTML='<input type="file" id="files2" name="files" value="" onChange="uploads2();">';
		if(result.indexOf("|")>0){
			arr=result.split("|");
			if(arr[0]==10000){
				layer.msg('上传成功');
				$("#tupian2").val(arr[1]);
				document.getElementById("tupian_inner2").innerHTML='<img src="/' + arr[1] + '" style="max-height:180px; max-width:180px;">';
				document.getElementById("tong2").innerHTML='<p align="center"><img src="/' + arr[1] + '" style="max-height:340px; max-width:520px;"></p>';
							
			}else if(arr[0]==20000){
				layer.msg('登录状态已失效');		
				setTimeout("location='<?php echo http_url();?>admin/login/indexs'",1500);			
			}else if(arr[0]==30000){	
				layer.msg(arr[1]);				
			}
		}else{
			form_loads=1;	
			layer.msg('操作过程出错，请您稍后再试！');					
		}	
	}
</script>

<title>新增文章 - 资讯管理 - H-ui.admin v2.3</title>

</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 系统管理 <span class="c-gray en">&gt;</span> 推送配置 <a id="shuaxins" class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i id="wysx" class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
	<div class="form form-horizontal" id="form-article-add" action="#">
    	
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>进门提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["door_title"];?>" placeholder="" id="door_title" name="door_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">进门低于100元推送信息</font>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>进门提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9">
<textarea name="door_message" class="input-text" id="door_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["door_message"];?></textarea>
&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">进门低于100元推送信息，{time}出门时间</font>
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>充值提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["money_title"];?>" placeholder="" id="money_title" name="money_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">账户余额不足100元推送信息标题</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>充值提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9">
<textarea name="money_message" class="input-text" id="money_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["money_message"];?></textarea>
&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">账户余额不足100元推送信息内容，{name}用户昵称</font>
			</div>
		</div>
        

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>充值成功提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["pay_title"];?>" placeholder="" id="pay_title" name="pay_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">充值成功推送信息标题</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>充值成功提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="pay_message" class="input-text" id="pay_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["pay_message"];?></textarea><br><font color="#FF0000">充值成功推送信息内容，{name}用户昵称，{money}充值金额，{height}高峰时间，{low}低峰时间，{height_money}高峰期享受价格，{low_money}低峰期享受价格</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>邀请好友提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["register_title"];?>" placeholder="" id="register_title" name="register_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">邀请好友奖励通知标题</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>邀请好友提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="register_message" class="input-text" id="register_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["register_message"];?></textarea><br><font color="#FF0000">邀请好友成功推送信息内容，{name}用户昵称，{time}时间，{money}奖励金额</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>点评课程提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["comment_title"];?>" placeholder="" id="comment_title" name="comment_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">点评课程提醒通知标题</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>点评课程提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="comment_message" class="input-text" id="comment_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["comment_message"];?></textarea><br><font color="#FF0000">点评课程推送信息内容，{name}用户昵称，{time}时间，{money}奖励金额</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>私课成立提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["class_title"];?>" placeholder="" id="class_title" name="class_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">私课成立提醒通知标题，针对学员</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>私课成立提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="class_message" class="input-text" id="class_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["class_message"];?></textarea><br><font color="#FF0000">私课成立提醒推送内容，{date}上课日期，{start}开始时间，{end}结束时间，{teacher}上课教练</font>
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>私课成立提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["class_cl_title"];?>" placeholder="" id="class_cl_title" name="class_cl_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">私课成立提醒通知标题，针对教练</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>私课成立提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="class_cl_message" class="input-text" id="class_cl_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["class_cl_message"];?></textarea><br><font color="#FF0000">私课成立提醒推送内容，{date}上课日期，{start}开始时间，{end}结束时间，{student}学员昵称</font>
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>私课取消提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["class_clear_title"];?>" placeholder="" id="class_clear_title" name="class_clear_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">私课取消提醒通知标题，针对学员</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>私课取消提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="class_clear_message" class="input-text" id="class_clear_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["class_clear_message"];?></textarea><br><font color="#FF0000">私课取消提醒推送内容，{date}上课日期，{start}开始时间，{end}结束时间，{time}取消时间，{money}退款金额</font>
			</div>
		</div>
        
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>私课取消提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["class_clear_teacher_title"];?>" placeholder="" id="class_clear_teacher_title" name="class_clear_teacher_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">私课取消提醒通知标题，针对教练</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>私课取消提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="class_clear_teacher_mssage" class="input-text" id="class_clear_teacher_mssage" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["class_clear_teacher_mssage"];?></textarea><br><font color="#FF0000">私课取消提醒推送内容，{date}上课日期，{start}开始时间，{end}结束时间，{time}取消时间</font>
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>操课成立提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["ck_class_title"];?>" placeholder="" id="ck_class_title" name="ck_class_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">操课成立提醒通知标题，针对学员</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>操课成立提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="ck_class_message" class="input-text" id="ck_class_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["ck_class_message"];?></textarea><br><font color="#FF0000">操课成立提醒推送内容，{date}上课日期，{start}开始时间，{end}结束时间，{teacher}上课教练，{room}上课大厅</font>
			</div>
		</div>
        
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>操课成立提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["ck_class_cl_title"];?>" placeholder="" id="ck_class_cl_title" name="ck_class_cl_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">操课成立提醒通知标题，针对教练</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>操课成立提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="ck_class_cl_message" class="input-text" id="ck_class_cl_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["ck_class_cl_message"];?></textarea><br><font color="#FF0000">操课成立提醒推送内容，{date}上课日期，{start}开始时间，{end}结束时间，{class}课程，{room}上课大厅</font>
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>操课取消提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["ck_class_qx_title_member"];?>" placeholder="" id="ck_class_qx_title_member" name="ck_class_qx_title_member" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">操课取消提醒通知标题，针对学员</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>操课取消提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="ck_class_qx_message_member" class="input-text" id="ck_class_qx_message_member" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["ck_class_qx_message_member"];?></textarea><br><font color="#FF0000">操课取消提醒推送内容，{date}上课日期，{start}开始时间，{end}结束时间，{class}课程，{room}上课大厅，{teacher}上课教练</font>
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>操课取消提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["ck_class_qx_title"];?>" placeholder="" id="ck_class_qx_title" name="ck_class_qx_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">操课取消提醒通知标题，针对教练</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>操课取消提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="ck_class_qx_message" class="input-text" id="ck_class_qx_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["ck_class_qx_message"];?></textarea><br><font color="#FF0000">操课取消提醒推送内容，{date}上课日期，{start}开始时间，{end}结束时间，{class}课程</font>
			</div>
		</div>
        
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>上传课程提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["ck_no_class_title"];?>" placeholder="" id="ck_no_class_title" name="ck_no_class_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">上传课程提醒通知标题，针对教练</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>上传课程提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="ck_no_class_message" class="input-text" id="ck_no_class_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["ck_no_class_message"];?></textarea><br><font color="#FF0000">上传课程提醒推送内容</font>
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>课程完成提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["class_wancheng_title"];?>" placeholder="" id="class_wancheng_title" name="class_wancheng_title" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">课程完成提醒通知标题，针对教练</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>课程完成提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="class_wancheng_message" class="input-text" id="class_wancheng_message" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["class_wancheng_message"];?></textarea><br><font color="#FF0000">课程完成提醒推送内容，{money}收入金额</font>
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>课程完成点评提醒标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $push_inc["class_wancheng_ctitle"];?>" placeholder="" id="class_wancheng_ctitle" name="class_wancheng_ctitle" style="width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FF0000">课程完成点评提醒通知标题，针对学员</font>
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>课程完成点评提醒内容：</label>
			<div class="formControls col-xs-8 col-sm-9" style="line-height:26px;">
<textarea name="class_wancheng_cmessage" class="input-text" id="class_wancheng_cmessage" style="width:50%;line-height:25px;height:90px;" placeholder=""><?php echo $push_inc["class_wancheng_cmessage"];?></textarea><br><font color="#FF0000">课程完成点评提醒推送内容</font>
			</div>
		</div>
		
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onClick="article_save_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 更新配置</button>
				
			</div>
		</div>
	</div>
</article>
<script>
	function show_imgs()
	{	
		layer.open({
		  type: 1,
		  skin: 'layui-layer-rim', //加上边框
		  area: ['520px', '340px'], //宽高
		  content: $("#tong")
		});
	}
	
	function show_imgs1()
	{
		layer.open({
		  type: 1,
		  skin: 'layui-layer-rim', //加上边框
		  area: ['520px', '340px'], //宽高
		  content: $("#tong1")
		});	
	}
	
	function show_imgs2()
	{
		layer.open({
		  type: 1,
		  skin: 'layui-layer-rim', //加上边框
		  area: ['520px', '340px'], //宽高
		  content: $("#tong2")
		});	
	}
	
	function article_save_submit()
	{
		
		if(form_loads==1)
		{
		
			var door_title=$("#door_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var door_message=$("#door_message").val().replace(/(^\s*)|(\s*$)/g,"");
			var money_title=$("#money_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var money_message=$("#money_message").val().replace(/(^\s*)|(\s*$)/g,"");
			var pay_title=$("#pay_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var pay_message=$("#pay_message").val().replace(/(^\s*)|(\s*$)/g,"");
			var register_title=$("#register_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var register_message=$("#register_message").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var comment_title=$("#comment_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var comment_message=$("#comment_message").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var class_title=$("#class_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var class_message=$("#class_message").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var class_cl_title=$("#class_cl_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var class_cl_message=$("#class_cl_message").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var class_clear_title=$("#class_clear_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var class_clear_message=$("#class_clear_message").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var class_clear_teacher_title=$("#class_clear_teacher_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var class_clear_teacher_mssage=$("#class_clear_teacher_mssage").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var ck_class_title=$("#ck_class_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var ck_class_message=$("#ck_class_message").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var ck_class_cl_title=$("#ck_class_cl_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var ck_class_cl_message=$("#ck_class_cl_message").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var ck_class_qx_title_member=$("#ck_class_qx_title_member").val().replace(/(^\s*)|(\s*$)/g,"");
			var ck_class_qx_message_member=$("#ck_class_qx_message_member").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var ck_class_qx_title=$("#ck_class_qx_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var ck_class_qx_message=$("#ck_class_qx_message").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var ck_no_class_title=$("#ck_no_class_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var ck_no_class_message=$("#ck_no_class_message").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var class_wancheng_title=$("#class_wancheng_title").val().replace(/(^\s*)|(\s*$)/g,"");
			var class_wancheng_message=$("#class_wancheng_message").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var class_wancheng_ctitle=$("#class_wancheng_ctitle").val().replace(/(^\s*)|(\s*$)/g,"");
			var class_wancheng_cmessage=$("#class_wancheng_cmessage").val().replace(/(^\s*)|(\s*$)/g,"");

			
			if(door_title=="" || door_message=="" || money_title=="" || money_message=="" || pay_title=="" || pay_message=="" || register_title=="" || register_message=="" || comment_title=="" || comment_message=="" || class_title=="" || class_message=="" || class_cl_title=="" || class_cl_message=="" || class_clear_title=="" || class_clear_message=="" || class_clear_teacher_title=="" || class_clear_teacher_mssage=="" || ck_class_title=="" || ck_class_message=="" || ck_class_cl_title=="" || ck_class_cl_message=="" || ck_class_qx_title_member=="" || ck_class_qx_message_member=="" || ck_class_qx_title=="" || ck_class_qx_message=="" || ck_no_class_title=="" || ck_no_class_message=="" || class_wancheng_title=="" || class_wancheng_message=="" || class_wancheng_ctitle=="" || class_wancheng_cmessage=="")
			{
				layer.alert('请填写完整数据', {
				  icon: 7,
				  skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
				})				
			}
			else
			{
				$.ajax({url:"<?php echo http_url();?>admin/others/pushs_subs", 
				type: 'POST', 
				data:{door_title:door_title,door_message:door_message,money_title:money_title,money_message:money_message,pay_title:pay_title,pay_message:pay_message,register_title:register_title,register_message:register_message,comment_title:comment_title,comment_message:comment_message,class_title:class_title,class_message:class_message,class_cl_title:class_cl_title,class_cl_message:class_cl_message,class_clear_title:class_clear_title,class_clear_message:class_clear_message,class_clear_teacher_title:class_clear_teacher_title,class_clear_teacher_mssage:class_clear_teacher_mssage,ck_class_title:ck_class_title,ck_class_message:ck_class_message,ck_class_cl_title:ck_class_cl_title,ck_class_cl_message:ck_class_cl_message,ck_class_qx_title_member:ck_class_qx_title_member,ck_class_qx_message_member:ck_class_qx_message_member,ck_class_qx_title:ck_class_qx_title,ck_class_qx_message:ck_class_qx_message,ck_no_class_title:ck_no_class_title,ck_no_class_message:ck_no_class_message,class_wancheng_title:class_wancheng_title,class_wancheng_message:class_wancheng_message,class_wancheng_ctitle:class_wancheng_ctitle,class_wancheng_cmessage:class_wancheng_cmessage}, 
				dataType: 'html', 
				timeout: 15000, 
					error: function(){
						layer.closeAll();
						form_loads=1;
						layer.alert('抱歉：程序更新过程中出错，请您稍后再试！', {
							icon: 2,
							skin: 'layer-ext-moon'
						})
					},
					beforeSend:function(){
						var index = layer.load(3,{
							shade: [0.2,'#333333'] //0.1透明度的白色背景
						});	
						form_loads=2;								
					},
					success:function(result){
						layer.closeAll();
						form_loads=1;
						result=result.replace(/(^\s*)|(\s*$)/g,"");
						if(result.indexOf("|")>0){
							arr=result.split("|");
							if(arr[0]==10000){
								form_state=1;
								layer.alert(arr[1], {
									icon: 1,
									skin: 'layer-ext-moon'
								})		
								setTimeout("location='<?php echo $_SERVER['REQUEST_URI'];?>'",1500);
							}else if(arr[0]==20000){
								layer.alert('登录状态已失效，请重新登录！', {
									icon: 2,
									skin: 'layer-ext-moon'
								})			
								setTimeout("location='<?php echo $_SERVER['REQUEST_URI']?>'",1500);			
							}else if(arr[0]==30000){
								layer.alert(arr[1], {
									icon: 2,
									skin: 'layer-ext-moon'
								})						
							}
						}else{
							layer.alert('操作过程出错，请您稍后再试！', {
								icon: 2,
								skin: 'layer-ext-moon'
							})					
						}						
					} 
				});	
			}
		}
		else
		{
			//第三方扩展皮肤
			layer.alert('尚有其他数据正在处理中，请稍等', {
			  icon: 7,
			  skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
			})			
		}
	}
</script>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/icheck/jquery.icheck.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/jquery.validation/1.14.0/jquery.validate.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/jquery.validation/1.14.0/validate-methods.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/jquery.validation/1.14.0/messages_zh.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui.admin/js/H-ui.admin.js"></script> 
<!--/_footer /作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/My97DatePicker/WdatePicker.js"></script>  
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/webuploader/0.1.5/webuploader.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/ueditor/1.4.3/ueditor.config.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/ueditor/1.4.3/ueditor.all.min.js"> </script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>

<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>