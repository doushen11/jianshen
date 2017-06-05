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
				$("#sx_1").show();
				layer.msg('上传成功');
				$("#tupian").val(arr[1]);
				document.getElementById("tupian_inner").innerHTML='<img src="/' + arr[1] + '" style="max-height:80px; max-width:80px;border-radius:50%;">';
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
				$("#sx_2").show();
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
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 教练管理 <span class="c-gray en">&gt;</span> 添加私课教练 <a id="shuaxins" class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i id="wysx" class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
	<div class="form form-horizontal" id="form-article-add" action="#">
    	
       
        <div class="row cl" id="sx_1" style="display:none;">
         	<iframe id="upload_target1" name="upload_target1" src="#" style="width:0;height:0;border:0px solid #fff;display:none;"></iframe>
			<label class="form-label col-xs-4 col-sm-2">教练头像：</label>
			<div class="formControls col-xs-8 col-sm-9">
                <a href="javascript:show_imgs();" id="tupian_inner"></a>
                <input type="hidden" id="tupian" name="tupian" value="">
                <span id="tong" style="display:none;"></span>
			</div>
		</div>
        <form id="recsons" name="recsons" action="<?php echo http_url();?>admin/others/houses_uploads/1" method="post" enctype="multipart/form-data" class="definewidth m20" target="upload_target1">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>教练头像：</label>
			<div class="formControls col-xs-8 col-sm-9">
            <input type="button" value="选择文件"  class="btn btn-default btn-uploadstar radius" onClick="c_imgs();">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="display:none;" id="file_inner"><input type="file" id="files" name="files" value="" onChange="uploads();"></span>
            <font color="#FF0000">说明：如果替换则上传，宽高比例为：1：1</font>
			</div>
		</div>
        </form>
        
 		<div class="row cl" id="sx_2" style="display:none;">
         	<iframe id="upload_target2" name="upload_target2" src="#" style="width:0;height:0;border:0px solid #fff;display:none;"></iframe>
			<label class="form-label col-xs-4 col-sm-2">教练背景图片：</label>
			<div class="formControls col-xs-8 col-sm-9">
                <a href="javascript:show_imgs1();" id="tupian_inner1"></a>
                <input type="hidden" id="tupian1" name="tupian1" value="">
                <span id="tong1" style="display:none;"><p align="center">></p></span>
			</div>
		</div>
        <form id="recsons1" name="recsons1" action="<?php echo http_url();?>admin/others/houses_uploads/2" method="post" enctype="multipart/form-data" class="definewidth m20" target="upload_target2">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>教练背景图片：</label>
			<div class="formControls col-xs-8 col-sm-9">
            <input type="button" value="选择文件"  class="btn btn-default btn-uploadstar radius" onClick="c_imgs1();">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="display:none;" id="file_inner1"><input type="file" id="files1" name="files" value="" onChange="uploads1();"></span>
            <font color="#FF0000">说明：如果替换则上传，宽高比例为：3：2，尽量黑色炫酷为主</font>
			</div>
		</div>
        </form>        
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>教练姓名：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" id="realname" name="realname">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>教练手机账号：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" id="mobile" name="mobile">
			</div>
		</div>


		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>教练登录密码：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="password" class="input-text" value="" placeholder="" id="passwd" name="passwd">
			</div>
		</div>
 
         <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>高峰期信息：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" id="money_peak" name="money_peak" style="width:7%;"> &nbsp;&nbsp;&nbsp; <font color="#FF0000">请填写高峰期服务费用</font> &nbsp;&nbsp;&nbsp; <input type="text" class="input-text" value="" placeholder="" id="money_peak_sys" name="money_peak_sys" style="width:7%;"> &nbsp;&nbsp;&nbsp; <font color="#FF0000">请填写高峰期服务费用费收益率点数，允许0.1-1之间，精确到小数点后两位</font>
			</div>
		</div>       
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>低峰期信息：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" id="money_slack" name="money_slack" style="width:7%;"> &nbsp;&nbsp;&nbsp; <font color="#FF0000">请填写低峰期服务费用</font> &nbsp;&nbsp;&nbsp; <input type="text" class="input-text" value="" placeholder="" id="money_slack_sys" name="money_slack_sys" style="width:7%;"> &nbsp;&nbsp;&nbsp; <font color="#FF0000">请填写低峰期服务费用费收益率点数，允许0.1-1之间，精确到小数点后两位</font>
			</div>
		</div>        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>教练性别：</label>
			<div class="formControls col-xs-8 col-sm-9"> 
				<select id="gender" name="gender" style="height:29px; line-height:29px;">
                	<option value="">--请选择--</option>
                    <option value="男">男</option>
                    <option value="女">女</option>
                </select>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>教练生日：</label>
			<div class="formControls col-xs-8 col-sm-9"> 
				<input type="text" onfocus="WdatePicker()" id="datemax" class="input-text Wdate" style="width:120px;" value="">
			</div>
		</div>
        <div class="row cl">
        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>教练等级：</label>
        <div class="formControls col-xs-8 col-sm-9">
            <input type="text" class="input-text" value="" placeholder="" id="level" name="level">
        </div>
        </div>
        <div class="row cl">
        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>教练文字简介：</label>
        <div class="formControls col-xs-8 col-sm-9">
            <input type="text" class="input-text" value="" placeholder="" id="desc" name="desc">
        </div>
        </div>
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>教练图文详情：</label>
			<div class="formControls col-xs-8 col-sm-9"> 
				<textarea id="contents" name="contents" style="width:98%; height:70px; padding-left:1%; padding-right:1%; padding-top:1%; line-height:26px;"></textarea>
			</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onClick="article_save_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 添加完成</button>
				
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
	
	function article_save_submit()
	{
		
		if(form_loads==1)
		{
			var mobile_check=/^(13[0-9]|15[0|1|2|3|4|5|6|7|8|9]|18[0|1|2|3|4|5|6|8|9|7]|17[0|1|2|3|4|5|6|8|9|7])\d{8}$/;	
			var moneys_test=/^\-?([1-9]\d*(\.\d{1,2})?|0\.\d{1,2})$|^0$/;
			var tupian=$("#tupian").val().replace(/(^\s*)|(\s*$)/g,"");
			var tupian1=$("#tupian1").val().replace(/(^\s*)|(\s*$)/g,"");
			var realname=$("#realname").val().replace(/(^\s*)|(\s*$)/g,"");
			var mobile=$("#mobile").val().replace(/(^\s*)|(\s*$)/g,"");
			var passwd=$("#passwd").val().replace(/(^\s*)|(\s*$)/g,"");
			var gender=$("#gender").val().replace(/(^\s*)|(\s*$)/g,"");
			var money_slack=$("#money_slack").val().replace(/(^\s*)|(\s*$)/g,"");
			var money_peak=$("#money_peak").val().replace(/(^\s*)|(\s*$)/g,"");
			var money_peak_sys=$("#money_peak_sys").val().replace(/(^\s*)|(\s*$)/g,"");
			var money_slack_sys=$("#money_slack_sys").val().replace(/(^\s*)|(\s*$)/g,"");
			var gender=$("#gender").val().replace(/(^\s*)|(\s*$)/g,"");
			var brithday=$("#datemax").val().replace(/(^\s*)|(\s*$)/g,"");
			var level=$("#level").val().replace(/(^\s*)|(\s*$)/g,"");
			var desc=$("#desc").val().replace(/(^\s*)|(\s*$)/g,"");
			editor.sync(); 
			var contents=$("#contents").val().replace(/(^\s*)|(\s*$)/g,"");
			if(tupian=="" || tupian1=="")
			{
				layer.alert('教练的头像和背景图必须要上传哦', {
				  icon: 7,
				  skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
				})				
			}
			else if(realname=="")
			{
				layer.tips('请填写教练的真实姓名', '#realname', {
				  tips: 3
				});
			}
			else if(!mobile_check.test(mobile))
			{
				layer.tips('请正确填写教练的登录手机账号', '#mobile', {
				  tips: 3
				});			
			}
			else if(passwd=="" || passwd.length<6 || passwd.length>16)
			{
				layer.tips('请填写教练登录密码，长度为6-16位', '#passwd', {
				  tips: 3
				});			
			}
			else if(!moneys_test.test(money_peak))
			{
				layer.tips('请正确填写教练高峰期服务费用', '#money_peak', {
				  tips: 3
				});			
			}
			else if(!moneys_test.test(money_peak_sys) || money_peak_sys>1)
			{
				layer.tips('请正确填写教练高峰期服务费用收益点数，0-1之间，精确到小数点后两位', '#money_peak_sys', {
				  tips: 3
				});			
			}
			else if(!moneys_test.test(money_slack))
			{
				layer.tips('请正确填写教练低峰期服务费用', '#money_slack', {
				  tips: 3
				});			
			}
			else if(!moneys_test.test(money_slack_sys) || money_slack_sys>1)
			{
				layer.tips('请正确填写教练低峰期服务费用收益点数，0-1之间，精确到小数点后两位', '#money_slack_sys', {
				  tips: 3
				});			
			}
			else if(gender=="")
			{
				layer.tips('请选择教练的性别', '#gender', {
				  tips: 3
				});			
			}
			else if(brithday=="")
			{
				layer.tips('请选择教练出生日期', '#datemax', {
				  tips: 3
				});			
			}
			else if(level=="")
			{
				layer.tips('请填写教练等级称谓', '#level', {
				  tips: 3
				});			
			}
			else if(desc=="")
			{
				layer.tips('请填写教练文字简介', '#desc', {
				  tips: 3
				});			
			}
			else
			{
				$.ajax({url:"<?php echo http_url();?>admin/teachers/index_inserts", 
				type: 'POST', 
				data:{contents:contents,avatar:tupian,bg:tupian1,realname:realname,mobile:mobile,passwd:passwd,gender:gender,money_slack:money_slack,money_peak:money_peak,money_peak_sys:money_peak_sys,money_slack_sys:money_slack_sys,gender:gender,brithday:brithday,level:level,desc:desc}, 
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
								setTimeout("location='<?php echo http_url();?>admin/teachers/indexs?keywords=<?php echo @$_GET["keywords"];?>&pageindex=<?php echo @$_GET["pageindex"];?>&start_time=<?php echo @$_GET["start_time"];?>&end_time=<?php echo @$_GET["end_time"];?>&states=<?php echo @$_GET["states"];?>'",1500);
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
<script charset="utf-8" src="/public/kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="/public/kindeditor/lang/zh_CN.js"></script>
<script>

        KindEditor.ready(function(K) {
                window.editor = K.create('#contents');
        });
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