<!--_meta 作为公共模版分离出去-->
<?php
	require FCPATH."config/img.inc.php";
	require FCPATH."config/sys.inc.php";
	require FCPATH."config/money_inc.php";
	require FCPATH."config/soft.inc.php";
	require FCPATH."config/address.inc.php";
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
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=vqXoIAjaiGEb2REGzjzygjgm"></script>
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
<body  onLoad="set_way();">
<style>
body, html,#allmap {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
#l-map{height:500px;width:100%;}

</style>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 系统管理 <span class="c-gray en">&gt;</span> 系统设置 <a id="shuaxins" class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i id="wysx" class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
	<div class="form form-horizontal" id="form-article-add" action="#">
    	
       
        <div class="row cl">
         	<iframe id="upload_target1" name="upload_target1" src="#" style="width:0;height:0;border:0px solid #fff;display:none;"></iframe>
			<label class="form-label col-xs-4 col-sm-2">默认头像：</label>
			<div class="formControls col-xs-8 col-sm-9">
                <a href="javascript:show_imgs();" id="tupian_inner"><img src="/<?php echo $img_inc["avatar"];?>" style="max-height:180px; max-width:180px;"></a>
                <input type="hidden" id="tupian" name="tupian" value="<?php echo $img_inc["avatar"];?>">
                <span id="tong" style="display:none;"><p align="center"><img src="/<?php echo $img_inc["avatar"];?>" style="max-height:100%; max-width:100%;"></p></span>
			</div>
		</div>
        <form id="recsons" name="recsons" action="<?php echo http_url();?>admin/others/houses_uploads/1" method="post" enctype="multipart/form-data" class="definewidth m20" target="upload_target1">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">默认头像：</label>
			<div class="formControls col-xs-8 col-sm-9">
            <input type="button" value="选择文件"  class="btn btn-default btn-uploadstar radius" onClick="c_imgs();">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="display:none;" id="file_inner"><input type="file" id="files" name="files" value="" onChange="uploads();"></span>
            <font color="#FF0000">说明：如果替换则上传，宽高比例为：1：1</font>
			</div>
		</div>
        </form>
        
 		<div class="row cl">
         	<iframe id="upload_target2" name="upload_target2" src="#" style="width:0;height:0;border:0px solid #fff;display:none;"></iframe>
			<label class="form-label col-xs-4 col-sm-2">教练端公告背景图：</label>
			<div class="formControls col-xs-8 col-sm-9">
                <a href="javascript:show_imgs1();" id="tupian_inner1"><img src="/<?php echo $img_inc["j_notice_bg"];?>" style="max-height:180px; max-width:180px;"></a>
                <input type="hidden" id="tupian1" name="tupian1" value="<?php echo $img_inc["j_notice_bg"];?>">
                <span id="tong1" style="display:none;"><p align="center"><img src="/<?php echo $img_inc["j_notice_bg"];?>" style="max-height:100%; max-width:100%;"></p></span>
			</div>
		</div>
        <form id="recsons1" name="recsons1" action="<?php echo http_url();?>admin/others/houses_uploads/2" method="post" enctype="multipart/form-data" class="definewidth m20" target="upload_target2">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">教练端公告背景图：</label>
			<div class="formControls col-xs-8 col-sm-9">
            <input type="button" value="选择文件"  class="btn btn-default btn-uploadstar radius" onClick="c_imgs1();">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="display:none;" id="file_inner1"><input type="file" id="files1" name="files" value="" onChange="uploads1();"></span>
            <font color="#FF0000">说明：如果替换则上传，宽高比例为：3：2</font>
			</div>
		</div>
        </form>   
        
		<div class="row cl">
         	<iframe id="upload_target3" name="upload_target3" src="#" style="width:0;height:0;border:0px solid #fff;display:none;"></iframe>
			<label class="form-label col-xs-4 col-sm-2">客户端公告背景图：</label>
			<div class="formControls col-xs-8 col-sm-9">
                <a href="javascript:show_imgs2();" id="tupian_inner2"><img src="/<?php echo $img_inc["k_notice_bg"];?>" style="max-height:180px; max-width:180px;"></a>
                <input type="hidden" id="tupian2" name="tupian2" value="<?php echo $img_inc["k_notice_bg"];?>">
                <span id="tong2" style="display:none;"><p align="center"><img src="/<?php echo $img_inc["k_notice_bg"];?>" style="max-height:100%; max-width:100%;"></p></span>
			</div>
		</div>
        <form id="recsons2" name="recsons2" action="<?php echo http_url();?>admin/others/houses_uploads/3" method="post" enctype="multipart/form-data" class="definewidth m20" target="upload_target3">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">客户端公告背景图：</label>
			<div class="formControls col-xs-8 col-sm-9">
            <input type="button" value="选择文件"  class="btn btn-default btn-uploadstar radius" onClick="c_imgs2();">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="display:none;" id="file_inner2"><input type="file" id="files2" name="files" value="" onChange="uploads2();"></span>
            <font color="#FF0000">说明：如果替换则上传，宽高比例为：3：2</font>
			</div>
		</div>
        </form>                    
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>进门扫码延时秒数：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $_sys_inc["doors_time"];?>" placeholder="" id="doors_time" name="doors_time">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>短信发送间隔秒数：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $_sys_inc["captcha_time"];?>" placeholder="" id="captcha_time" name="captcha_time">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>短信验证码时效<font color="#cc0000">(秒)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $_sys_inc["captcha_lives"];?>" placeholder="" id="captcha_lives" name="captcha_lives">
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>私课开课程前多久不允许取消课程<font color="#cc0000">(秒)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $_sys_inc["class_close_time_a"];?>" placeholder="" id="class_close_time_a" name="class_close_time_a">
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>操课开课前多久没达到人数取消课程<font color="#cc0000">(秒)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $_sys_inc["class_close_time_b"];?>" placeholder="" id="class_close_time_b" name="class_close_time_b">
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>每位操课教练当日最多发布课程条数：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $_sys_inc["class_insert_count_day"];?>" placeholder="" id="class_insert_count_day" name="class_insert_count_day">
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>注册奖励金额（微信邀请）<font color="#cc0000">(元)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $money_inc["register"];?>" placeholder="" id="register" name="register">
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>注册赠送金额<font color="#cc0000">(元)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $money_inc["reg"];?>" placeholder="" id="reg" name="reg">
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>评论奖励金额<font color="#cc0000">(元)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $money_inc["comment"];?>" placeholder="" id="comment" name="comment">
			</div>
		</div>  
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>注册短信模板<font color="#cc0000">({code}为验证码)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $_sys_inc["code_reg"];?>" placeholder="" id="code_reg" name="code_reg">
			</div>
		</div> 
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>登录短信模板<font color="#cc0000">({code}为验证码)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $_sys_inc["code_login"];?>" placeholder="" id="code_login" name="code_login">
			</div>
		</div>        

        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>找回密码短信模板<font color="#cc0000">({code}为验证码)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $_sys_inc["code_reset"];?>" placeholder="" id="code_reset" name="code_reset">
			</div>
		</div>  
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">IOS下载地址<font color="#cc0000">(分享使用)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $_sys_inc["a_http"];?>" placeholder="" id="a_http" name="a_http">
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">安卓下载地址<font color="#cc0000">(分享使用)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $_sys_inc["i_http"];?>" placeholder="" id="i_http" name="i_http">
			</div>
		</div>
        
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">客户端下载地址<font color="#cc0000">(升级使用)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $soft_inc["k_downloads"];?>" placeholder="" id="k_downloads" name="k_downloads">
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">客户端版本号<font color="#cc0000">(升级使用)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $soft_inc["k_verson"];?>" placeholder="" id="k_verson" name="k_verson">
			</div>
		</div>
        
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">教练端下载地址<font color="#cc0000">(升级使用)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $soft_inc["j_downloads"];?>" placeholder="" id="j_downloads" name="j_downloads">
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">教练端版本号<font color="#cc0000">(升级使用)</font>：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $soft_inc["j_verson"];?>" placeholder="" id="j_verson" name="j_verson">
			</div>
		</div>   
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">健身房地址：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<div id="l-map"></div>
			</div>
		</div>     
        <input type="hidden" id="longitude" name="longitude" value="<?php echo $address_array["longitude"]?>">
        <input type="hidden" id="latitude" name="latitude" value="<?php echo $address_array["latitude"]?>">
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
		
			var tupian=$("#tupian").val().replace(/(^\s*)|(\s*$)/g,"");
			var tupian1=$("#tupian1").val().replace(/(^\s*)|(\s*$)/g,"");
			var tupian2=$("#tupian2").val().replace(/(^\s*)|(\s*$)/g,"");
			var captcha_lives=$("#captcha_lives").val().replace(/(^\s*)|(\s*$)/g,"");
			var captcha_time=$("#captcha_time").val().replace(/(^\s*)|(\s*$)/g,"");
			var doors_time=$("#doors_time").val().replace(/(^\s*)|(\s*$)/g,"");
			var class_close_time_a=$("#class_close_time_a").val().replace(/(^\s*)|(\s*$)/g,"");
			var class_close_time_b=$("#class_close_time_b").val().replace(/(^\s*)|(\s*$)/g,"");
			var class_insert_count_day=$("#class_insert_count_day").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var register=$("#register").val().replace(/(^\s*)|(\s*$)/g,"");
			var reg=$("#reg").val().replace(/(^\s*)|(\s*$)/g,"");
			var comment=$("#comment").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var a_http=$("#a_http").val().replace(/(^\s*)|(\s*$)/g,"");
			var i_http=$("#i_http").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var code_reg=$("#code_reg").val().replace(/(^\s*)|(\s*$)/g,"");
			var code_login=$("#code_login").val().replace(/(^\s*)|(\s*$)/g,"");
			var code_reset=$("#code_reset").val().replace(/(^\s*)|(\s*$)/g,"");
			
			var k_verson=$("#k_verson").val().replace(/(^\s*)|(\s*$)/g,"");
			var k_downloads=$("#k_downloads").val().replace(/(^\s*)|(\s*$)/g,"");
			var j_verson=$("#j_verson").val().replace(/(^\s*)|(\s*$)/g,"");
			var j_downloads=$("#j_downloads").val().replace(/(^\s*)|(\s*$)/g,"");
			
			
			var longitude=$("#longitude").val().replace(/(^\s*)|(\s*$)/g,"");
			var latitude=$("#latitude").val().replace(/(^\s*)|(\s*$)/g,"");
			
			
			
			if(tupian=="" || tupian1=="" || tupian2=="")
			{
				layer.alert('页面数据已丢失，请刷新重试', {
				  icon: 7,
				  skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
				})				
			}
			else if(doors_time=="")
			{
				layer.tips('请正确填写延时秒数，最好4-10秒内', '#doors_time', {
				  tips: 3
				});					
			}
			else if(captcha_time=="" || isNaN(captcha_time))
			{
				layer.tips('请正确填写短信发送间隔秒数', '#captcha_time', {
				  tips: 3
				});
			}
			else if(captcha_lives=="" || isNaN(captcha_lives))
			{
				layer.tips('请正确填写短信验证码时效', '#captcha_lives', {
				  tips: 3
				});			
			}
			else if(class_close_time_a=="" || isNaN(class_close_time_a))
			{
				layer.tips('请正确填写私课开课程前多久不允许取消课程(秒)', '#class_close_time_a', {
				  tips: 3
				});			
			}
			else if(class_close_time_b=="" || isNaN(class_close_time_b))
			{
				layer.tips('请正确填写操课开课前多久没达到人数取消课程(秒)', '#class_close_time_b', {
				  tips: 3
				});			
			}
			else if(class_insert_count_day=="" || isNaN(class_insert_count_day))
			{
				layer.tips('请正确填写每位操课教练当日最多发布课程条数', '#class_insert_count_day', {
				  tips: 3
				});			
			}
			else if(code_reg=="")
			{
				layer.tips('请填写短信注册模板', '#code_reg', {
				  tips: 3
				});			
			}
			else if(code_login=="")
			{
				layer.tips('请填写短信登录模板', '#code_login', {
				  tips: 3
				});			
			}
			else if(code_reset=="")
			{
				layer.tips('请填写短信找回密码模板', '#code_reset', {
				  tips: 3
				});					
			}
			else if(register=="" || isNaN(register))
			{
				layer.tips('请正确填写注册(微信邀请好友)奖励金额', '#register', {
				  tips: 3
				});			
			}
			else if(reg=="" || isNaN(reg))
			{
				layer.tips('请正确填写注册赠送金额', '#reg', {
				  tips: 3
				});					
			}
			else if(comment=="" || isNaN(comment))
			{
				layer.tips('请正确填写点评课程后获取的奖励金额', '#comment', {
				  tips: 3
				});			
			}
			else if(k_verson=="" || k_downloads=="" || j_verson=="" || j_downloads=="")
			{
				layer.alert('升级信息不能为空，请填写', {
				  icon: 7,
				  skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
				})					
			}
			else
			{
				$.ajax({url:"<?php echo http_url();?>admin/others/systems_subs", 
				type: 'POST', 
				data:{tupian:tupian,tupian1:tupian1,tupian2:tupian2,captcha_lives:captcha_lives,captcha_time:captcha_time,class_close_time_a:class_close_time_a,class_close_time_b:class_close_time_b,class_insert_count_day:class_insert_count_day,register:register,comment:comment,a_http:a_http,i_http:i_http,code_reg:code_reg,code_login:code_login,code_reset:code_reset,doors_time:doors_time,k_verson:k_verson,k_downloads:k_downloads,j_verson:j_verson,j_downloads:j_downloads,reg:reg,latitude:latitude,longitude:longitude}, 
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
								setTimeout("location='<?php echo http_url();?>admin/login/indexs'",1500);			
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
<script type="text/javascript">
	// 百度地图API功能
	var map = new BMap.Map("l-map");
	var point = new BMap.Point(<?php echo $address_array["longitude"]?>,<?php echo $address_array["latitude"]?>);
	map.centerAndZoom(point, 14);
	
	map.enableScrollWheelZoom(true);
	
	var marker = new BMap.Marker(point);// 创建标注

	
	map.addOverlay(marker);             // 将标注添加到地图中
	marker.enableDragging();


	var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
	var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
	var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角，仅包含平移和缩放按钮
	map.addControl(top_left_control);        
	map.addControl(top_left_navigation);     
	map.addControl(top_right_navigation);	
	
	function set_way()
	{
		$("#longitude").val(marker.point.lng);	
		$("#latitude").val(marker.point.lat);
		setTimeout("set_way()",500);
	}
	
	//alert(marker.point.lng);
	
	
	
	
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