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
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 系统管理 <span class="c-gray en">&gt;</span> 进门时间段设置 <a id="shuaxins" class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i id="wysx" class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
	<div class="form form-horizontal" id="form-article-add" action="#">
    	
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>高峰时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<select id="start_1" name="start_1">
                	<?php
                    	for($i=0;$i<=24;$i++)
						{
					?>
                	<option value="<?php echo $i;?>" <?php if($result1["min"]==$i){?> selected<?php }?>><?php echo $i;?>时</option>
                    <?php
						}
					?>
                </select>
                &nbsp;&nbsp;&nbsp;-----&nbsp;&nbsp;&nbsp;
				<select id="end_1" name="end_1">
                	<?php
                    	for($i=0;$i<=24;$i++)
						{
					?>
                	<option value="<?php echo $i;?>" <?php if($result1["max"]==$i){?> selected<?php }?>><?php echo $i;?>时</option>
                    <?php
						}
					?>
                </select>
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>低峰时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<select id="start_2" name="start_2">
                	<?php
                    	for($i=0;$i<=24;$i++)
						{
					?>
                	<option value="<?php echo $i;?>" <?php if($result2["min"]==$i){?> selected<?php }?>><?php echo $i;?>时</option>
                    <?php
						}
					?>
                </select>
                &nbsp;&nbsp;&nbsp;-----&nbsp;&nbsp;&nbsp;
				<select id="end_2" name="end_2">
                	<?php
                    	for($i=0;$i<=24;$i++)
						{
					?>
                	<option value="<?php echo $i;?>" <?php if($result2["max"]==$i){?> selected<?php }?>><?php echo $i;?>时</option>
                    <?php
						}
					?>
                </select>
			</div>
		</div>

        

        

		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onClick="article_save_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 更新开门高低峰时间段 </button>
				
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
		
			var start_1=$("#start_1").val().replace(/(^\s*)|(\s*$)/g,"");
			var start_2=$("#start_2").val().replace(/(^\s*)|(\s*$)/g,"");
			var end_1=$("#end_1").val().replace(/(^\s*)|(\s*$)/g,"");
			var end_2=$("#end_2").val().replace(/(^\s*)|(\s*$)/g,"");
			$.ajax({url:"<?php echo http_url();?>admin/times/opens_subs", 
			type: 'POST', 
			data:{start_1:start_1,start_2:start_2,end_1:end_1,end_2:end_2}, 
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