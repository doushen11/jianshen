<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
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
<script>
	var keywords="";
	var pageindex="";
	var start_time="";
	var end_time="";
	var states="";

	var form_status=0;
	
	//会员修改
	function members_edit(id)
	{
		location='<?php echo http_url();?>admin/members/edits/' + id + "?keywords=" + keywords + "&pageindex=" + pageindex + "&start_time=" + start_time + "&end_time=" + end_time + "&states=" + states;	
	}
	
	//会员充值
	function members_pay(id)
	{
		location='<?php echo http_url();?>admin/members/online_pay/' + id + "?keywords=" + keywords + "&pageindex=" + pageindex + "&start_time=" + start_time + "&end_time=" + end_time + "&states=" + states;	
	}
	
	function page_gos()
	{
		pageindex=$("#pagenum").val(); 
		reads();
	}
	
	function show_pages(pages)
	{
		pageindex=pages; 
		reads();
	}
	
	function reads()
	{
		//alert(1);
		$.ajax({url:"<?php echo http_url();?>admin/teachers/draws_ajaxs/" + pageindex + "?keywords=" + keywords + "&start_time=" + start_time + "&end_time=" + end_time + "&states=" + states, 
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
	

	
	function reads_notes(id)
	{
		if(form_status==0 || form_status==3){
			$.ajax({url:"<?php echo http_url();?>admin/notes/reads?id=" + id, 
			type: 'GET', 
			dataType: 'html', 
			timeout: 15000, 
				error: function(){
					form_status=3;					
				},
				beforeSend:function(){

					form_status=1;	
				},
				success:function(result){
					form_status=3;
				} 
			});
		}	

	}
	
	
	function soso()
	{
		keywords=$("#keys").val();
		start_time=$("#datemin").val();
		end_time=$("#datemax").val();
		states=$("#states").val();
		//alert(froms);
		pageindex=1;
		reads();
	}
	
	function change_state(id)
	{
		if(form_status==0 || form_status==3){
			$.ajax({url:"<?php echo http_url();?>admin/members/changes", 
			type: 'POST', 
			data:{id:id},
			dataType: 'html', 
			timeout: 15000, 
				error: function(){
					layer.closeAll();
					form_status=3;	
					layer.alert('处理失败，请您稍后再试！', {
						icon: 7,
						skin: 'layer-ext-moon'
					})			
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
					if(result.indexOf("|")>0){
						arr=result.split("|");
						if(arr[0]==10000){
							if(arr[1]==1)
							{
								$("#state_" + id).html('<strong style="color:green;">允许</strong>');	
							}
							else
							{
								$("#state_" + id).html('<strong style="color:#cc0000;">禁止</strong>');	
							}
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
		}else{
			layer.alert('还有其他进程正在进行中，请您稍后再试！', {
				icon: 7,
				skin: 'layer-ext-moon'
			})					
		}				
	}
	
	function choose_all()
	{
		//选择信息
		var s_group = document.getElementsByName("sx");
		var s_group_value="";
		for(var i = 0; i< s_group.length; i++){
			if(s_group[i].checked==true){
				s_group_value=s_group[i].value;
			}
		}
		var s_group = document.getElementsByName("cid");
		for(var i = 0; i< s_group.length; i++){
			if(s_group_value!=""){
				s_group[i].checked=true;
			}else{
				s_group[i].checked=false;	
			}
		}	
	}	
	
	function del_all(){
		var s_group = document.getElementsByName("cid");
		var s_group_value="";
		for(var i = 0; i< s_group.length; i++){
			if(s_group[i].checked==true){
				//alert(group[i].value);
				if(s_group_value==""){
					s_group_value=s_group[i].value;	
				}else{
					s_group_value=s_group_value + "," + s_group[i].value;
				}
			}
		}	
		if(s_group_value=="")
		{
			layer.msg('请选择要删除的数据');
		}
		else
		{
			del_it(s_group_value);	
		}			
	}	
	
	function del_it(id){
		layer.confirm('警告：您确定要删除所选数据吗！当前系统仅支持删除取消类记录，其他类型记录不支持删除！', {
			btn: ['确定','取消'] //按钮
		}, function(){
			del_rs(id);
		}, function(){
			layer.closeAll();
		});				
	}	
	
	function del_rs(id){
		if(form_status==0 || form_status==3){
			$.ajax({url:"<?php echo http_url();?>admin/members/sikes_dels", 
			type: 'POST', 
			data:{id:id},
			dataType: 'html', 
			timeout: 15000, 
				error: function(){
					layer.closeAll();
					form_status=3;	
					layer.alert('处理失败，请您稍后再试！', {
						icon: 7,
						skin: 'layer-ext-moon'
					})			
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
					if(result.indexOf("|")>0){
						arr=result.split("|");
						if(arr[0]==10000){
							layer.alert(arr[1], {
								icon: 1,
								skin: 'layer-ext-moon'
							})		
							setTimeout("reads()",1000);
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
		}else{
			layer.alert('还有其他进程正在进行中，请您稍后再试！', {
				icon: 7,
				skin: 'layer-ext-moon'
			})					
		}				
	}
	
	function shows_boxs(id)
	{
		layer.open({
		  type: 1,

		  skin: 'layui-layer-rim', //加上边框
		  area: ['420px', '240px'], //宽高
		  content: $("#abc_" + id)
		});


	}
	
	function shows_boxss(id)
	{
		layer.open({
		  type: 1,
		  skin: 'layui-layer-rim', //加上边框
		  area: ['420px', '240px'], //宽高
		  content: $("#abcd_" + id)
		});			
	}
</script>
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>用户管理</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 教练管理 <span class="c-gray en">&gt;</span> 教练打款记录 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="text-c"> 
    相关日期
    &nbsp;&nbsp;
    <input type="text" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'datemax\')||\'%y-%M-%d\'}'})" id="datemin" class="input-text Wdate" style="width:120px;" value="">
		-
		<input type="text" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'datemin\')}',maxDate:'%y-%M-%d'})" id="datemax" class="input-text Wdate" style="width:120px;" value="">
        
		<input type="text" class="input-text" style="width:250px" placeholder="教练手机号/教练昵称/金额/备注" id="keys" name="keys" value="">&nbsp;&nbsp;
  
         <select id="states" name="states" style="height:30px; line-height:30px; border:#e4e4e4 1px solid;">
        	<option value="">--请选择教练身份--</option>
            <option value="1">私课教练</option>
            <option value="2">操课教练</option>
        </select>
        &nbsp;&nbsp;       
        
		<button type="submit" class="btn btn-success radius" id="" name="" onClick="soso();"><i class="Hui-iconfont">&#xe665;</i> 搜记录</button>
	</div>
    <span id="inners">
	
    </span>
</div>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/laypage/1.2/laypage.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/datatables/1.10.0/jquery.dataTables.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui.admin/js/H-ui.admin.js"></script> 
<style>
.pages a{background-color: #FFFFFF;border: 1px solid #e4e4e4;color: #999;display:inline-block;font-size: 12px;height: 24px;line-height:24px;margin: 0px 3px 0px 0px;padding-left: 8px;padding-right:8px; margin-right:12px; width:auto;}
.pages a:hover{margin-right:12px; text-decoration:underline;}
.pages span{background-color: #f4f4f4;border: 1px solid #ccc;color: #333;display:inline-block;font-size: 12px;height: 24px;line-height: 24px;margin: 0px 3px 0px 0px;padding-left: 10px;padding-right: 10px;margin-right:12px;font-weight:bold;}
</style>
</body>
</html>