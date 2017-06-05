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
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 操课管理 <span class="c-gray en">&gt;</span> 操课添加 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
	<div class="form form-horizontal" id="form-article-add" action="#">
    
		 <div class="row cl" id="sx_1">
         	<iframe id="upload_target1" name="upload_target1" src="#" style="width:0;height:0;border:0px solid #fff;display:none;"></iframe>
			<label class="form-label col-xs-4 col-sm-2"></label>
			<div class="formControls col-xs-8 col-sm-9">
                <a href="javascript:show_imgs();" id="tupian_inner"><img src="/<?php echo $result["avatar"];?>" style="height:100px;width:100px; border-radius:50%;"></a>
                <span id="tong" style="display:none;"><p align="center"><img src="/<?php echo $result["avatar"];?>" style="max-height:340px; max-width:520px;"></p></span>
                
			</div>
		</div>   	

       <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>教练姓名：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $result["realname"];?>" placeholder="" id="name" name="name" disabled>
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>教练手机：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $result["mobile"];?>" placeholder="" id="name" name="name" disabled>
			</div>
		</div>
    	
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>私课最后安排时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="<?php echo $time==""?"暂无":date("Y-m-d H:i:s",$time);?>" placeholder="" id="name" name="name" disabled>
			</div>
		</div>
<script>
	function show_c1()
	{
		//var classs=$("#classs").val();
		//if(classs.indexOf("_")>=0)
		//{
			//classs=classs.split("_");
			//$("#min").val(classs[1]);	
			//$("#max").val(classs[2]);
		//}else{
			//$("#min").val(0);
		//	$("#max").val(0);
		//}
		
		<?php
			$str="";
			$arr=json_decode($result["money_desc"],true);
			//print_r($arr);die();
			for($i=0;$i<count($arr);$i++)
			{
				$str.=":".$arr[$i]["class"]."_".$arr[$i]["alls"][0]["room_id"]."_".$arr[$i]["alls"][0]["class_min"]."_".$arr[$i]["alls"][0]["class_max"].":".$arr[$i]["class"]."_".$arr[$i]["alls"][1]["room_id"]."_".$arr[$i]["alls"][1]["class_min"]."_".$arr[$i]["alls"][1]["class_max"];
			}
			$str=trim($str,":");
			
		?>
		var strs="<?php echo $str;?>";
		var classs=$("#classs").val();
		var rooms=$("#rooms").val();
		if(classs!="" && rooms!="")
		{
			arr=strs.split(":");
			aaa=classs + "_" + rooms;
			for(var i=0;i<arr.length;i++)
			{
				if(arr[i].indexOf(aaa)>=0)
				{
					as=arr[i].split("_");
					$("#min").val(as[2]);
					$("#max").val(as[3]);
				}	
			}	
		}
		else
		{
			$("#min").val(0);
			$("#max").val(0);				
		}
	}
</script>        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>所上课程：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<select id="classs" name="classs" onChange="show_c1();">
            	<option value="">--请选择所上课程--</option>
                <?php
                	foreach($query1->result_array() as $array){
						$text='"class":"'.$array["id"].'",';	
				?>
					<?php if(substr_count($result["money_desc"],$text)>0){?>
    
                    <option value="<?php echo $array["id"];?>" ><?php echo $array["name"];?></option>
                    <?php }?>
                <?php }?>
            </select>
			</div>
		</div> 
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>开课人数：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<input type="text" class="input-text" value="0" placeholder="" id="min" name="min" disabled >
			</div>
		</div> 
	<script>
        function show_c2()
        {
            var rooms=$("#rooms").val();
            if(rooms.indexOf("_")>=0){
            rooms=rooms.split("_");
            $("#max").val(rooms[1]);	
            }else{$("#max").val(0);}
        }
    </script> 
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>所在课厅：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<select id="rooms" name="rooms" onChange="show_c1();">
            	<option value="">--请选择所上课厅--</option>
                <?php
                	foreach($query2->result_array() as $array){
						
				?>
              
                <option value="<?php echo $array["id"];?>" ><?php echo $array["name"];?></option>

                <?php }?>
            </select>
			</div>
		</div> 
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>容纳人数：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<input type="text" class="input-text" value="0" placeholder="" id="max" name="max" disabled >
			</div>
		</div>       
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>课程时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
            	 <input type="text" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'datemax\')}'})" id="datemin" class="input-text Wdate" style="width:120px;display:none;" value="<?php echo date("Y-m-d",time()+3600*24);?>">
				<input type="text" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'datemin\')}'})" id="datemax" class="input-text Wdate" style="width:120px;" value="" >
			</div>
		</div>
<script>
	function show_times()
	{
		var s=$("#s").val();
		if(s!="")
		{
			s=parseInt(s)+1;	
			$("#times_inner").html('<select id="e" name="e"><option value="' + s + '">' + s + ':00</option></select>');
		}		
	}
</script>       
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>开课节点：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<select id="s" name="s" onChange="show_times();">
                	<?php
                    	for($a=9;$a<=21;$a++){
					?>
                	<option value="<?php echo $a;?>"><?php echo $a;?>:00</option>
                    <?php
						}
					?>
                </select>
                &nbsp;&nbsp;&nbsp;
                -
                &nbsp;&nbsp;&nbsp;
                <span id="times_inner">
                <select id="e" name="e"><option value="10">10:00</option></select>
                </span>
			</div>
		</div>
<script>
	function show_a()
	{
		$("#frees_inner").hide();	
		var frees=$("#frees").val();
		if(frees==2)
		{
			$("#frees_inner").show();		
		}	
	}
	
	
	
</script>
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>课程收费类型：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<select id="frees" name="frees" onChange="show_a();">
                	<option value="">--请选择课程类型--</option>
                    <option value="1">正常计费</option>
                    <option value="2">免费课程</option>
                </select>
			</div>
		</div>
        
        <div class="row cl" id="frees_inner" style="display:none;">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>每人收费金额：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<input type="text" class="input-text" value="" placeholder="" id="moneys" name="moneys" style="width:50%"> &nbsp;&nbsp;&nbsp; <font color="#FF0000">线下结算使用</font>
			</div>
		</div>
       
        
        

		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onClick="article_save_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 添加操课信息 </button>
				
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
		
			var datemax=$("#datemax").val().replace(/(^\s*)|(\s*$)/g,"");
			var s=$("#s").val().replace(/(^\s*)|(\s*$)/g,"");
			var e=$("#e").val().replace(/(^\s*)|(\s*$)/g,"");
			var frees=$("#frees").val().replace(/(^\s*)|(\s*$)/g,"");
			var moneys=$("#moneys").val().replace(/(^\s*)|(\s*$)/g,"");
			var classs=$("#classs").val().replace(/(^\s*)|(\s*$)/g,"");
			var mins=$("#min").val().replace(/(^\s*)|(\s*$)/g,"");
			var rooms=$("#rooms").val().replace(/(^\s*)|(\s*$)/g,"");
			var maxs=$("#max").val().replace(/(^\s*)|(\s*$)/g,"");
			var moneys_test=/^\-?([1-9]\d*(\.\d{1,2})?|0\.\d{1,2})$|^0$/;
			
			if(classs=="")
			{
				layer.alert('请选择教授课程！', {
					icon: 7,
					skin: 'layer-ext-moon'
				})			
			}
			else if(mins=="" || mins==0)
			{
				layer.alert('请选择教授课程！', {
					icon: 7,
					skin: 'layer-ext-moon'
				})			
			}
			if(rooms=="")
			{
				layer.alert('请选择教授课程所在课厅！', {
					icon: 7,
					skin: 'layer-ext-moon'
				})			
			}
			else if(maxs=="" || maxs==0)
			{
				layer.alert('请选择教授课程所在课厅！', {
					icon: 7,
					skin: 'layer-ext-moon'
				})			
			}
			else if(datemax=="")
			{
				layer.alert('请选择开课日期！', {
					icon: 7,
					skin: 'layer-ext-moon'
				})			
			}
			else if(s=="" || e=="")
			{
				layer.alert('请选择开课和结束时间节点！', {
					icon: 7,
					skin: 'layer-ext-moon'
				})	
			}
			else if(frees=="")
			{
				layer.alert('请选择课程类型！', {
					icon: 7,
					skin: 'layer-ext-moon'
				})	
			}
			else if(frees==2 && !moneys_test.test(moneys))
			{
				layer.alert('请正确填写每人收费金额！', {
					icon: 7,
					skin: 'layer-ext-moon'
				})	
			}	
			else
			{
				$.ajax({url:"<?php echo http_url();?>admin/classs/caokes_adds_subs/<?php echo $result["id"];?>", 
				type: 'POST', 
				data:{datemax:datemax,s:s,e:e,frees:frees,moneys:moneys,classs:classs,mins:mins,rooms:rooms,maxs:maxs}, 
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
								setTimeout("location='<?php echo http_url();?>admin/teachers/homes?keywords=<?php echo @$_GET["keywords"];?>&pageindex=<?php echo @$_GET["pageindex"];?>&start_time=<?php echo @$_GET["start_time"];?>&end_time=<?php echo @$_GET["end_time"];?>&states=<?php echo @$_GET["states"];?>'",1500);
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