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
<script src="<?php echo base_url();?>/public/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>/public/layer_pc/layer.js"></script>
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
				$("#show_a").show();
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
</script>

<title>新增文章 - 资讯管理 - H-ui.admin v2.3</title>

</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 会员管理 <span class="c-gray en">&gt;</span> 修改会员 <a id="shuaxins" class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i id="wysx" class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
	<div class="form form-horizontal" id="form-article-add" action="#">
    	
       
        <div class="row cl" id="show_a">
         	<iframe id="upload_target1" name="upload_target1" src="#" style="width:0;height:0;border:0px solid #fff;display:none;"></iframe>
			<label class="form-label col-xs-4 col-sm-2">会员头像：</label>
			<div class="formControls col-xs-8 col-sm-9">
                <a href="javascript:show_imgs();" id="tupian_inner"><img src="/<?php echo $result["avatar"]==""?$avatar:$result["avatar"];?>" style="max-height:180px; max-width:180px;"></a>
                <input type="hidden" id="tupian" name="tupian" value="<?php echo $result["avatar"]==""?"":$result["avatar"];?>">
                <span id="tong" style="display:none;"><p align="center"><img src="/<?php echo $result["avatar"]==""?$avatar:$result["avatar"];?>" style="max-height:340px; max-width:520px;"></p></span>
			</div>
		</div>
        <form id="recsons" name="recsons" action="<?php echo http_url();?>admin/others/houses_uploads/1" method="post" enctype="multipart/form-data" class="definewidth m20" target="upload_target1">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">会员头像：</label>
			<div class="formControls col-xs-8 col-sm-9">
            <input type="button" value="选择文件"  class="btn btn-default btn-uploadstar radius" onClick="c_imgs();">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="display:none;" id="file_inner"><input type="file" id="files" name="files" value="" onChange="uploads();"></span>
            <font color="#FF0000">说明：修改则上传替换，宽高比例为：3：2</font>
			</div>
		</div>
        </form>
        
 		
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>会员手机号：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" placeholder="" value="<?php echo $result["mobile"];?>" id="mobile" name="mobile">
			</div>
		</div>
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">会员用户名：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" placeholder="" value="<?php echo $result["nickname"];?>" id="nickname" name="nickname">
			</div>
		</div>
       <div class="row cl" style="display:none;">
			<label class="form-label col-xs-4 col-sm-2">登录密码：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="password" class="input-text" placeholder="" value="" id="passwd" name="passwd" style="width:60%;"> &nbsp;&nbsp;&nbsp;&nbsp; <font color="#FF0000">如果不修改则不用填写，长度为6-16位</font>
			</div>
		</div>
       <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">性别：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<select id="gender" name="gender" style="height:29px; line-height:29px;">
                	<option value="">--请选择--</option>
                    <option value="男"<?php if($result["gender"]=="男"){?> selected<?php }?>>男</option>
                    <option value="女"<?php if($result["gender"]=="女"){?> selected<?php }?>>女</option>
                </select>
			</div>
		</div>
        
       <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">生日：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" onfocus="WdatePicker()" id="datemax" class="input-text Wdate" style="width:120px;" value="<?php echo $result["brithday"];?>">
			</div>
		</div>        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">身高：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" placeholder="" value="<?php echo $result["height"];?>" id="height" name="height">
			</div>
		</div>
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">体重：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" placeholder="" value="<?php echo $result["weight"];?>" id="weight" name="weight">
			</div>
		</div>
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">职业：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" placeholder="" value="<?php echo $result["professional"];?>" id="professional" name="professional">
			</div>
		</div>
       <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">登录状态：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<select id="state" name="state" style="height:29px; line-height:29px;">
                    <option value="1"<?php if($result["state"]=="1"){?> selected<?php }?>>允许</option>
                    <option value="2"<?php if($result["state"]=="2"){?> selected<?php }?>>拒绝</option>
                </select>
			</div>
		</div>        

		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onClick="article_save_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 修改会员信息</button>
				
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
	

	
	function article_save_submit()
	{
		
		if(form_loads==1)
		{
			var mobile_check=/^(13[0-9]|15[0|1|2|3|4|5|6|7|8|9]|18[0|1|2|3|4|5|6|8|9|7]|17[0|1|2|3|4|5|6|8|9|7])\d{8}$/;	
			var tupian=$("#tupian").val().replace(/(^\s*)|(\s*$)/g,"");
			var mobile=$("#mobile").val().replace(/(^\s*)|(\s*$)/g,"");
			var nickname=$("#nickname").val().replace(/(^\s*)|(\s*$)/g,"");
			var passwd=$("#passwd").val().replace(/(^\s*)|(\s*$)/g,"");
			var gender=$("#gender").val().replace(/(^\s*)|(\s*$)/g,"");
			var brithday=$("#datemax").val().replace(/(^\s*)|(\s*$)/g,"");
			var height=$("#height").val().replace(/(^\s*)|(\s*$)/g,"");
			var weight=$("#weight").val().replace(/(^\s*)|(\s*$)/g,"");
			var professional=$("#professional").val().replace(/(^\s*)|(\s*$)/g,"");
			var state=$("#state").val().replace(/(^\s*)|(\s*$)/g,"");
			//alert(tupian);
			if(!mobile_check.test(mobile))
			{
				layer.tips('请填写正确手机号', '#mobile', {
				  tips: 3
				});			
			}
			else if(passwd!="" && (passwd.length<6 || passwd.length>16))
			{
				layer.tips('登录密码长度必须为6-16位', '#passwd', {
				  tips: 3
				});
			}
			else
			{
				$.ajax({url:"<?php echo http_url();?>admin/members/indexs_updates/<?php echo $result["id"];?>", 
				type: 'POST', 
				data:{avatar:tupian,mobile:mobile,nickname:nickname,passwd:passwd,gender:gender,brithday:brithday,height:height,weight:weight,professional:professional,state:state}, 
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
								setTimeout("location='<?php echo http_url();?>admin/members/indexs?keywords=<?php echo @$_GET["keywords"];?>&pageindex=<?php echo @$_GET["pageindex"];?>&start_time=<?php echo @$_GET["start_time"];?>&end_time=<?php echo @$_GET["end_time"];?>&states=<?php echo @$_GET["states"];?>&doors=<?php echo @$_GET["doors"];?>'",1500);
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