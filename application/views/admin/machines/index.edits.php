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
	var upload_indexs="";
	var form_loads=1;
	function z_imgs(id)
	{
		upload_indexs=id;
		$("#file_alls").click();
	}
	
	function upload_imgs()
	{
		//var forms="recsons" + id;
		//alert(forms);
		if(form_loads==1)
		{
			layer.closeAll();
			var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
			form_loads=2;	
			document.shenxu.submit();
		}
		else
		{
			layer.msg('抱歉：尚有数据正在处理中...');	
		}
	}
	
	function stopUpload1(result){
		layer.closeAll();
		form_loads=1;	
		//$("#sub_avatar").val("");	
		document.getElementById("shenxu").innerHTML='<input type="file" id="file_alls" name="file_alls" value="" class="wenjian" style="display:none;" onChange="upload_imgs();"> ';
		if(result.indexOf("|")>0){
			arr=result.split("|");
			if(arr[0]==10000){
				layer.msg('上传成功');
				$("#tupian_" + upload_indexs).val(arr[1]);
				$("#b" + upload_indexs).show();		
				$("#c" + upload_indexs).html('<img src="/' + arr[1] + '" style="max-width:100%;max-height:100%;">');
				upload_indexs="";
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
	
	function show_img(id)
	{
		var tpssss=$("#tupian_" + id).val();
		//alert(tpssss);
		if(tpssss=="")
		{
			layer.msg('操作过程出错，无法查看！');
		}	
		else
		{
			layer.open({
			  type: 1,
			  title: false,
			  closeBtn: 0,
			  shadeClose: true,
			  skin: 'yourclass',
			  content: $("#c" + id)
			});
		}
	}

	var rowCount=1000;		
	function add_one()
	{
		//添加一行
		rowCount++;  
		var newRow='<p id="a' + rowCount + '"><input type="hidden" id="tupian_' + rowCount + '" name="tupian" value="" class="beyond1" ><input type="button" value="选择图片文件"  class="btn btn-default btn-uploadstar radius" onClick="z_imgs(' + rowCount + ');">&nbsp;&nbsp;&nbsp; <input name="tw' + rowCount + '" type="text" class="wenzi" id="tw' + rowCount + '" placeholder="请填写匹配文字,最多500字" value="" maxlength="500"> &nbsp;&nbsp;&nbsp; <span id="b' + rowCount + '" style="display:none;"><a href="javascript:show_img(' + rowCount + ');" style="color:#FF0000;">已经上传图片,点击查看&nbsp;&nbsp;&nbsp;</a></span><span id="c' + rowCount + '" style="display:none;"></span> <a href="javascript:del_one(' + rowCount + ');">删除这条</a></p>'; 
		$('#upload_img_doms').append(newRow);		
	}
	
	function del_one(id)
	{
		$("#a" + id).remove();
	}
	
	function show_models()
	{
		var act=$("#act").val();
		if(act==2)
		{
			$("#z_1").show();	
			$("#z_2").hide();
		}
		else
		{
			$("#z_1").hide();
			$("#z_2").show();	
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
				$("#fengmian").show();
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

	function show_imgs()
	{	
		layer.open({
		  type: 1,
		  skin: 'layui-layer-rim', //加上边框
		  area: ['520px', '340px'], //宽高
		  content: $("#tong")
		});
	}
</script>
<article class="page-container">
	<div class="form form-horizontal" id="form-article-add" action="#">
    	
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>器材名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" placeholder="" id="name" name="name" value="<?php echo $result["name"];?>">
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>器材描述语：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $result["alt"];?>" placeholder="" id="alt" name="alt">
			</div>
		</div>
        <iframe id="upload_target1" name="upload_target1" src="#" style="width:0;height:0;border:0px solid #fff; display:none;"></iframe>
        <div class="row cl" id="fengmian">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>封面图片：</label>
			<div class="formControls col-xs-8 col-sm-9">
                <a href="javascript:show_imgs();" id="tupian_inner"><img src="/<?php echo $result["file"];?>" style="max-height:180px; max-width:180px;" /></a>
                <input type="hidden" id="tupian" name="tupian" value="<?php echo $result["file"];?>">
                <span id="tong" style="display:none;"><p align="center"><img src="/<?php echo $result["file"];?>" style="max-height:340px; max-width:520px;"></p></span>
			</div>
		</div>
        <form id="recsons" name="recsons" action="<?php echo http_url();?>admin/others/abouts_uploads" method="post" enctype="multipart/form-data" class="definewidth m20" target="upload_target1">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>封面图片：</label>
			<div class="formControls col-xs-8 col-sm-9">
            <input type="button" value="选择图片文件"  class="btn btn-default btn-uploadstar radius" onClick="c_imgs();">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="display:none;" id="file_inner"><input type="file" id="files" name="files" value="" onChange="uploads();"></span>
            <font color="#FF0000">说明：宽高比例为：3：2</font>
			</div>
		</div>
        </form>		
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>简介模式：</label>
			<div class="formControls col-xs-8 col-sm-9"> 
				<select id="act" name="act" onChange="show_models();">
                	<option value="2" <?php if($result["act"]==2){?> selected<?php }?>>--图文模式--</option>
                    <option value="1" <?php if($result["act"]==1){?> selected<?php }?>>--视频模式--</option>
                </select>
			</div>
		</div>
        
        <div class="row cl" id="z_1" <?php if($result["act"]==1){?> style="display:none;"<?php }?>>
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>图文信息：</label>
			<div class="formControls col-xs-8 col-sm-9"> 
                <iframe id="rs1" name="rs1" src="#" style="width:0;height:0;border:0px solid #fff; display:none;"></iframe>
                <form id="shenxu" name="shenxu" action="<?php echo http_url();?>admin/machines/indexs_uploads" method="post" enctype="multipart/form-data" class="definewidth m20" target="rs1">
                <input type="file" id="file_alls" name="file_alls" value="" class="wenjian" style="display:none;" onChange="upload_imgs();"> 
                </form>	
                
                <span id="upload_img_doms">		
                <?php
                	$arrs=json_decode($result["file_path"],true);
					if(empty($arrs)){
				?>
                <p id="a1">
                <input type="hidden" id="tupian_1" name="tupian" value="" class="beyond1" >
                <input type="button" value="选择图片文件"  class="btn btn-default btn-uploadstar radius" onClick="z_imgs('1');">&nbsp;&nbsp;&nbsp;<input name="tw1" type="text" class="wenzi" id="tw1" placeholder="请填写匹配文字,最多500字" value="" maxlength="500"> &nbsp;&nbsp;&nbsp; <span id="b1" style="display:none;"><a href="javascript:show_img('1');" style="color:#FF0000;">已经上传图片,点击查看&nbsp;&nbsp;&nbsp;</a></span><span id="c1" style="display:none;"></span> <a href="javascript:add_one();">添加一条</a>
               
                </p>
                <?php
                	}
					else
					{
				?>
                <?php
					for($i=0;$i<count($arrs);$i++){                
				?>
                <?php
                	if($i==0){
				?>
                <p id="a<?php echo $i+1;?>">
                <input type="hidden" id="tupian_<?php echo $i+1;?>" name="tupian" value="<?php echo $arrs[$i]["files"];?>" class="beyond1" >
                <input type="button" value="选择图片文件"  class="btn btn-default btn-uploadstar radius" onClick="z_imgs('<?php echo $i+1;?>');">&nbsp;&nbsp;&nbsp;<input name="tw<?php echo $i+1;?>" type="text" class="wenzi" id="tw<?php echo $i+1;?>" placeholder="请填写匹配文字,最多500字" value="<?php echo $arrs[$i]["text"];?>" maxlength="500"> &nbsp;&nbsp;&nbsp; <span id="b<?php echo $i+1;?>" style="display:;"><a href="javascript:show_img('<?php echo $i+1;?>');" style="color:#FF0000;">已经上传图片,点击查看&nbsp;&nbsp;&nbsp;</a></span><span id="c<?php echo $i+1;?>" style="display:none;"><img src="/<?php echo $arrs[$i]["files"];?>" style="max-width:100%;max-height:100%;"></span> <a href="javascript:add_one();">添加一条</a>
               
                </p>
                <?php
                	}
					else
					{
				?>
                <p id="a<?php echo $i+1;?>">
                <input type="hidden" id="tupian_<?php echo $i+1;?>" name="tupian" value="<?php echo $arrs[$i]["files"];?>" class="beyond1" >
                <input type="button" value="选择图片文件"  class="btn btn-default btn-uploadstar radius" onClick="z_imgs('<?php echo $i+1;?>');">&nbsp;&nbsp;&nbsp;<input name="tw<?php echo $i+1;?>" type="text" class="wenzi" id="tw<?php echo $i+1;?>" placeholder="请填写匹配文字,最多500字" value="<?php echo $arrs[$i]["text"];?>" maxlength="500"> &nbsp;&nbsp;&nbsp; <span id="b<?php echo $i+1;?>" style="display:;"><a href="javascript:show_img('<?php echo $i+1;?>');" style="color:#FF0000;">已经上传图片,点击查看&nbsp;&nbsp;&nbsp;</a></span><span id="c<?php echo $i+1;?>" style="display:none;"><img src="/<?php echo $arrs[$i]["files"];?>" style="max-width:100%;max-height:100%;"></span> <a href="javascript:del_one('<?php echo $i+1;?>');">删除这条</a>
                
                </p>
                <?php
                	}
				?>
                <?php
                	}
				?>
                <?php
                	}
				?>
                </span>
			</div>
		</div>
        
        <iframe id="rs2" name="rs2" src="#" style="width:0;height:0;border:0px solid #fff; display:none;"></iframe>
        <form id="shenxu2" name="shenxu2" action="<?php echo http_url();?>admin/machines/mv_uploads" method="post" enctype="multipart/form-data" class="definewidth m20" target="rs2">
      	<div class="row cl" id="z_2"  <?php if($result["act"]==2){?> style="display:none;"<?php }?>>
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>视频简介：</label>
			<div class="formControls col-xs-8 col-sm-9">
            
            <input type="button" value="选择视频文件"  class="btn btn-default btn-uploadstar radius" onClick="c_video();">  <span id="mv_puts" style="display:none"><input type="file" id="mv_file" name="mv_file" value="" onChange="upload_mv();"></span>&nbsp;&nbsp;&nbsp; <span id="shiping" <?php if($result["act"]==2 || $result["video_path"]==""){?>style="display:none;"<?php }?>><strong style="color:#FF0000">已经上传成功</a></strong></span><input type="hidden" id="mv" name="mv" value="<?php echo $result["video_path"];?>">
            
			</div>
		</div>
      	</form>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onClick="article_save_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 修改一条器材信息 </button> &nbsp;&nbsp;&nbsp;&nbsp; <font color="#FF0000">视频大小请控制在8M以内</font>
				
			</div>
		</div>
	</div>
</article>
<script>
	
	var form_loads=1;
	
	function article_save_submit()
	{
		
		if(form_loads==1)
		{
		
			var name=$("#name").val().replace(/(^\s*)|(\s*$)/g,"");
			var alt=$("#alt").val().replace(/(^\s*)|(\s*$)/g,"");
			var tupian=$("#tupian").val().replace(/(^\s*)|(\s*$)/g,"");
			var act=$("#act").val().replace(/(^\s*)|(\s*$)/g,"");
			var mv=$("#mv").val().replace(/(^\s*)|(\s*$)/g,"");
			var img_state=1;
			//alert(contents);
			var jsons="";
			if(act==2)
			{
				//开始查询文字信息
				$("#upload_img_doms p").each(function(){
					var tp=$(this).find(".beyond1").val();
					var wz=$(this).find(".wenzi").val();
					if(tp=="" || wz=="")
					{
						img_state=2;
					}
					if(jsons=="")
					{
						jsons=tp + "{sx}" + wz;
					}
					else
					{
						jsons=jsons + "{recson}" + tp + "{sx}" + wz;
					}
				});	
			}
			
			if(name=="")
			{
				layer.tips('请填写视频标题', '#name', {
				  tips: 3
				});			
			}
			else if(alt=="")
			{
				layer.tips('请填写视频描述语句', '#alt', {
				  tips: 3
				});
			}
			else if(tupian=="")
			{
				layer.alert('抱歉：请上传一张封面图！', {
					icon: 7,
					skin: 'layer-ext-moon'
				})			
			}
			else if(act==2 && img_state!=1)
			{
				layer.alert('抱歉：请把图文简介信息填写完整！', {
					icon: 7,
					skin: 'layer-ext-moon'
				})			
			}
			else if(act==1 && mv=="")
			{
				layer.alert('抱歉：请上传图文简介视频！', {
					icon: 7,
					skin: 'layer-ext-moon'
				})			
			}
			else
			{
				$.ajax({url:"<?php echo http_url();?>admin/machines/edits_subs/<?php echo intval($this->uri->segment(4));?>", 
				type: 'POST', 
				data:{name:name,alt:alt,tupian:tupian,act:act,id:"<?php echo @$_GET["id"];?>",mv:mv,jsons:jsons}, 
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
								setTimeout("location='<?php echo http_url();?>admin/machines/indexs/<?php echo @$_GET["id"];?>?pageindex=<?php echo @$_GET["pageindex"];?>&keywords=<?php echo @$_GET["keywords"];?>'",1500);
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