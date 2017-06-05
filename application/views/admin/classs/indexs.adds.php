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
<script src="<?php echo base_url();?>/public/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>/public/layer_pc/layer.js"></script>
<style>
.wenzi{width:40%; height:25px; line-height:25px; padding-left:1%; padding-right:1%; border:#CCCCCC 1px solid;}
.wenjian{width:20%; height:25px; line-height:25px; padding-left:1%; padding-right:1%; border:#CCCCCC 1px solid;}
</style>
<title>新增文章 - 资讯管理 - H-ui.admin v2.3</title>

</head>
<body>
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
	
	function show_models()
	{
		var act=$("#act").val();
		//alert(act);
		if(act==1)
		{
			$("#beyond_1").show();	
			$("#beyond_2").hide();
		}
		else
		{
			$("#beyond_1").hide();
			$("#beyond_2").show();	
		}
	}
	
	function c_video()
	{
		$("#mv_file").click();
	}
	
	function upload_mv()
	{
		if(form_loads==1)
		{
			layer.closeAll();
			var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
			form_loads=2;	
			document.shenxu2.submit();
		}
		else
		{
			layer.msg('抱歉：尚有数据正在处理中...');	
		}		
	}
	
	function stopUpload2(result)
	{
		layer.closeAll();	
		form_loads=1;	
		//$("#sub_avatar").val("");	
		document.getElementById("mv_puts").innerHTML='<input type="file" id="mv_file" name="mv_file" value="" onChange="upload_mv();">';
		if(result.indexOf("|")>0){
			arr=result.split("|");
			if(arr[0]==10000){
				layer.msg('上传成功');
				$("#mv").val(arr[1]);
				$("#shiping").show();		
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
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 课程管理 <span class="c-gray en">&gt;</span> 课类添加 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
	<div class="form form-horizontal" id="form-article-add" action="#">

        <div class="row cl" id="sx_1" style="display:none;">
         	<iframe id="upload_target1" name="upload_target1" src="#" style="width:0;height:0;border:0px solid #fff;display:none;"></iframe>
			<label class="form-label col-xs-4 col-sm-2">新晋课程背景图片：</label>
			<div class="formControls col-xs-8 col-sm-9">
                <a href="javascript:show_imgs();" id="tupian_inner"></a>
                <input type="hidden" id="tupian" name="tupian" value="">
                <span id="tong" style="display:none;"></span>
			</div>
		</div>
        <form id="recsons" name="recsons" action="<?php echo http_url();?>admin/others/houses_uploads/1" method="post" enctype="multipart/form-data" class="definewidth m20" target="upload_target1">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>新晋课程背景图片：</label>
			<div class="formControls col-xs-8 col-sm-9">
            <input type="button" value="选择文件"  class="btn btn-default btn-uploadstar radius" onClick="c_imgs();">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="display:none;" id="file_inner"><input type="file" id="files" name="files" value="" onChange="uploads();"></span>
            <font color="#FF0000">说明：如果替换则上传，宽高比例为：3：2，最好选择炫酷深色图片</font>
			</div>
		</div>
        </form>
    	
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>课类名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" id="name" name="name">
			</div>
		</div>
        
        <div class="row cl" style="display:none;">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>最少开课人数：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="10" placeholder="" id="counts" name="counts">
			</div>
		</div>

        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>课类短语描述：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" id="alt" name="alt">
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>课类展示方式：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<select id="act" name="act" onChange="show_models();">
                	<option value="1">图片</option>
                    <option value="2">视频</option>
                </select>
			</div>
		</div>
        
        <span id="beyond_1">
 		<div class="row cl" id="sx_2" style="display:none;">
         	<iframe id="upload_target2" name="upload_target2" src="#" style="width:0;height:0;border:0px solid #fff;display:none;"></iframe>
			<label class="form-label col-xs-4 col-sm-2">课类展示图片：</label>
			<div class="formControls col-xs-8 col-sm-9">
                <a href="javascript:show_imgs1();" id="tupian_inner1"></a>
                <input type="hidden" id="tupian1" name="tupian1" value="">
                <span id="tong1" style="display:none;"><p align="center">></p></span>
			</div>
		</div>
        <form id="recsons1" name="recsons1" action="<?php echo http_url();?>admin/others/houses_uploads/2" method="post" enctype="multipart/form-data" class="definewidth m20" target="upload_target2">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>课类展示图片：</label>
			<div class="formControls col-xs-8 col-sm-9">
            <input type="button" value="选择文件"  class="btn btn-default btn-uploadstar radius" onClick="c_imgs1();">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="display:none;" id="file_inner1"><input type="file" id="files1" name="files" value="" onChange="uploads1();"></span>
            <font color="#FF0000">说明：如果替换则上传，宽高比例为：3：2，尽量黑色炫酷为主</font>
			</div>
		</div>
        </form>  
		</span>
        
        <span id="beyond_2" style="display:none;">
        <iframe id="rs2" name="rs2" src="#" style="width:0;height:0;border:0px solid #fff; display:none;"></iframe>
        <form id="shenxu2" name="shenxu2" action="<?php echo http_url();?>admin/machines/mv_uploads" method="post" enctype="multipart/form-data" class="definewidth m20" target="rs2">
      	<div class="row cl" id="z_2">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>视频简介：</label>
			<div class="formControls col-xs-8 col-sm-9">
            
            <input type="button" value="选择视频文件"  class="btn btn-default btn-uploadstar radius" onClick="c_video();">  <span id="mv_puts" style="display:none"><input type="file" id="mv_file" name="mv_file" value="" onChange="upload_mv();"></span>&nbsp;&nbsp;&nbsp; <span id="shiping" style="display:none;"><strong style="color:#FF0000">已经上传成功</strong></strong></span><input type="hidden" id="mv" name="mv" value="">
            
			</div>
		</div>
      	</form>        	
        </span>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>课类详细描述：</label>
			<div class="formControls col-xs-8 col-sm-9">
              <textarea name="contents" class="input-text" id="contents" placeholder="" style="height:75px;"></textarea>
			</div>
		</div>

		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onClick="article_save_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 添加一个课类信息 </button>
				
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
		
	var form_loads=1;
	
	function article_save_submit()
	{
		
		if(form_loads==1)
		{
		
			var tupian=$("#tupian").val().replace(/(^\s*)|(\s*$)/g,"");
			var name=$("#name").val().replace(/(^\s*)|(\s*$)/g,"");
			var counts=$("#counts").val().replace(/(^\s*)|(\s*$)/g,"");
			var alt=$("#alt").val().replace(/(^\s*)|(\s*$)/g,"");
			var act=$("#act").val().replace(/(^\s*)|(\s*$)/g,"");
			var tupian1=$("#tupian1").val().replace(/(^\s*)|(\s*$)/g,"");
			var mv=$("#mv").val().replace(/(^\s*)|(\s*$)/g,"");
			var contents=$("#contents").val().replace(/(^\s*)|(\s*$)/g,"");

			
			if(tupian=="")
			{
				layer.alert('请选择上传新晋课程背景图片！', {
					icon: 7,
					skin: 'layer-ext-moon'
				})		
			}
			else if(name=="")
			{
				layer.tips('请填写课类名称', '#name', {
				  tips: 3
				});
			}
			else if(parseInt(counts)<=0 || parseInt(counts)>=9999)
			{
				layer.tips('开课人数必须在1-9999人之间', '#counts', {
				  tips: 3
				});
			}	
			else if(alt=="")
			{
				layer.tips('请填写短语描述', '#alt', {
				  tips: 3
				});
			}	
			else if(act==1 && tupian1=="")
			{
				layer.alert('请选择上传展示图片！', {
					icon: 2,
					skin: 'layer-ext-moon'
				})					
			}
			else if(act==2 && mv=="")
			{
				layer.alert('请选择上传展示视频！', {
					icon: 2,
					skin: 'layer-ext-moon'
				})					
			}
			else if(contents=="")
			{
				layer.tips('请填写课类详细描述', '#contents', {
				  tips: 3
				});
			}
			else
			{
				$.ajax({url:"<?php echo http_url();?>admin/classs/adds_subs", 
				type: 'POST', 
				data:{name:name,tupian:tupian,counts:counts,alt:alt,act:act,tupian1:tupian1,mv:mv,contents:contents}, 
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
								setTimeout("location='<?php echo http_url();?>admin/classs/indexs'",1500);
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