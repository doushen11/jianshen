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
<title>智能健身管理系统</title>
</head>
<body onLoad="read_ajaxs();">
<?php
	//print_r($rs);
?>
<script>
	function msgs()
	{
		$("#fks").click();	
	}
	
	function read_ajaxs()
	{
		//alert(10);
		$.ajax({url:"<?php echo http_url();?>admin/admins/countss", 
			type: 'GET', 
			dataType: 'html', 
			timeout: 15000, 
			error: function(){
				read_ajaxs();
			},
			beforeSend:function(){
													
			},
			success:function(result){
				
				result=result.replace(/(^\s*)|(\s*$)/g,"");
				if(parseInt(result)>0)
				{
					$("#msg_inner").html('<span class="badge badge-danger">' + parseInt(result) + '</span>');	
					setTimeout("read_ajaxs()",10000);
				}
			}
		});				
	}
	
	function system_fo()
	{
		$("#sysr").click();	
	}
	
	function abc1()
	{
		$("#abc1").click();		
	}

	function abc2()
	{
		$("#abc2").click();		
	}
	
	function abc3()
	{
		$("#abc3").click();		
	}
</script>
<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="<?php echo $_SERVER['REQUEST_URI'];?>">智能健身管理系统</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="<?php echo $_SERVER['REQUEST_URI'];?>">H-ui</a> <span class="logo navbar-slogan f-l mr-10 hidden-xs">v1.0</span> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>


			<nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
				<ul class="cl">
					<li>后台管理员</li>
					<li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A"><?php echo $rs["username"];?> <i class="Hui-iconfont">&#xe6d5;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:system_fo();">系统配置</a></li>
							<li><a href="<?php echo http_url();?>admin/admins/logouts">退出</a></li>
						</ul>
					</li>
                    <?php
                    	$qy=$this->db->query("select `id` from `dg_note` where `read`='1'");
					?>
					<li id="Hui-msg"> <a href="javascript:msgs();" title="消息">
                    <label id="msg_inner">
                    <?php
                    	if($qy->num_rows()>0){
					?>
                    <span class="badge badge-danger"><?php echo $qy->num_rows();?></span>
                    <?php
						}
					?>
                    </label>
                    <i class="Hui-iconfont" style="font-size:18px">&#xe68a;</i></a> </li>
					<li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
							<li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
							<li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
							<li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
							<li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
							<li><a href="javascript:;" data-val="orange" title="绿色">橙色</a></li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<aside class="Hui-aside">
	<input runat="server" id="divScrollValue" type="hidden" value="" />
	<div class="menu_dropdown bk_2">
		<dl id="menu-article">
			<dt><i class="Hui-iconfont">&#xe616;</i> 文章管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a _href="<?php echo http_url();?>admin/article/indexs" data-title="文章管理" href="javascript:void(0)">文章管理</a></li>
				</ul>
			</dd>
		</dl>
        <dl id="menu-article100">
			<dt><i class="Hui-iconfont">&#xe610;</i> 进门管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a _href="<?php echo http_url();?>admin/doors/indexs" data-title="教练进门" href="javascript:void(0)">教练进门</a></li>
                    <li><a _href="<?php echo http_url();?>admin/doors/homes" data-title="学员进门" href="javascript:void(0)">学员进门</a></li>
                    <li><a _href="<?php echo http_url();?>admin/doors/sos" data-title="工作进门" href="javascript:void(0)">工作进门</a></li>
                    <li><a _href="<?php echo http_url();?>admin/doors/maps" data-title="系统分析" href="javascript:void(0)">系统分析</a></li>
				</ul>
			</dd>
		</dl>
		<dl id="menu-picture">
			<dt><i class="Hui-iconfont">&#xe613;</i> 器材管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a _href="<?php echo http_url();?>admin/machines/acts" data-title="器材分类" href="javascript:void(0)">器材分类</a></li>

				</ul>
			</dd>
		</dl>
        <dl id="menu-picture100">
			<dt><i class="Hui-iconfont">&#xe623;</i> 课程管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a _href="<?php echo http_url();?>admin/classs/indexs" data-title="课类设置" href="javascript:void(0)">课类设置</a></li>
					<li><a _href="<?php echo http_url();?>admin/classs/sikes" data-title="私课管理" href="javascript:void(0)">私课管理</a></li>
                    <li><a _href="<?php echo http_url();?>admin/classs/caokes" data-title="操课管理" href="javascript:void(0)">操课管理</a></li>
				</ul>
			</dd>
		</dl>
		<dl id="menu-product">
			<dt><i class="Hui-iconfont">&#xe620;</i> 教练管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a _href="<?php echo http_url();?>admin/teachers/indexs" data-title="私课教练" href="javascript:void(0)">私课教练</a></li>
					<li><a _href="<?php echo http_url();?>admin/teachers/homes" data-title="操课教练" href="javascript:void(0)">操课教练</a></li>
                    <li><a _href="<?php echo http_url();?>admin/teachers/moneys" data-title="教练收益" href="javascript:void(0)">教练收益</a></li>
					<li><a _href="<?php echo http_url();?>admin/teachers/draws" data-title="打款记录" href="javascript:void(0)">打款记录</a></li>
				</ul>
			</dd>
		</dl>
       	
		<dl id="menu-product">
			<dt><i class="Hui-iconfont">&#xe620;</i> SOS管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a _href="<?php echo http_url();?>admin/worker/indexs" data-title="工作人员" href="javascript:void(0)">工作人员</a></li>
                    <li><a _href="<?php echo http_url();?>admin/worker/soss" data-title="sos操作记录" href="javascript:void(0)">sos操作记录</a></li>
                    

				</ul>
			</dd>
		</dl>       
		<dl id="menu-comments">
			<dt><i class="Hui-iconfont">&#xe622;</i> 意见管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a id="fks" _href="<?php echo http_url();?>admin/notes/indexs" data-title="客户端反馈" href="javascript:;">客户端反馈</a></li>
					<li><a _href="<?php echo http_url();?>admin/notes/homes" data-title="教练端反馈" href="javascript:void(0)">教练端反馈</a></li>
				</ul>
			</dd>
		</dl>
		<dl id="menu-member">
			<dt><i class="Hui-iconfont">&#xe60d;</i> 会员管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a _href="<?php echo http_url();?>admin/members/indexs" data-title="会员列表" href="javascript:;">会员列表</a></li>
					<li><a _href="<?php echo http_url();?>admin/members/pays" data-title="充值记录" href="javascript:;">充值记录</a></li>
                    <li><a _href="<?php echo http_url();?>admin/members/sikes" data-title="私课记录" href="javascript:;">私课记录</a></li>
                    <li><a _href="<?php echo http_url();?>admin/members/caokes" data-title="操课记录" href="javascript:;">操课记录</a></li>
                    <li><a _href="<?php echo http_url();?>admin/members/tixians" data-title="提现纪录" href="javascript:;">提现纪录</a></li>
				</ul>
			</dd>
		</dl>
		<dl id="menu-admin">
			<dt><i class="Hui-iconfont">&#xe62d;</i> 管理员管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a _href="<?php echo http_url();?>admin/admins/indexs" data-title="管理员列表" href="javascript:void(0)">管理员列表</a></li>
				</ul>
			</dd>
		</dl>

		<dl id="menu-system">
			<dt><i class="Hui-iconfont">&#xe62e;</i> 系统管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a _href="<?php echo http_url();?>admin/others/systems" data-title="系统设置" href="javascript:void(0)" id="sysr">系统设置</a></li>
                    <li><a _href="<?php echo http_url();?>admin/others/pushs" data-title="推送模板" href="javascript:void(0)" id="sysr">推送模板</a></li>
                    <li><a _href="<?php echo http_url();?>admin/others/shufs" data-title="轮播图设置" href="javascript:void(0)">轮播图设置</a></li>
					<li><a _href="<?php echo http_url();?>admin/others/abouts" data-title="关于我们" href="javascript:void(0)">关于我们</a></li>
					<li><a _href="<?php echo http_url();?>admin/others/houses" data-title="健身房信息" href="javascript:void(0)">健身房信息</a></li>
					<li><a _href="<?php echo http_url();?>admin/rooms/indexs" data-title="课厅设置" href="javascript:void(0)">课厅设置</a></li>
                    <li><a _href="<?php echo http_url();?>admin/others/opens" data-title="进门价格" href="javascript:void(0)">进门价格</a></li>
					<li><a _href="<?php echo http_url();?>admin/times/opens" data-title="进门时间" href="javascript:void(0)">进门时间</a></li>
                    <li><a _href="<?php echo http_url();?>admin/times/class_a" data-title="私课时间" href="javascript:void(0)">私课时间</a></li>
                    <li><a _href="<?php echo http_url();?>admin/times/class_b" data-title="操课时间" href="javascript:void(0)">操课时间</a></li>
				</ul>
			</dd>
		</dl>
	</div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
	<div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
		<div class="Hui-tabNav-wp">
			<ul id="min_title_list" class="acrossTab cl">
				<li class="active"><span title="我的桌面" data-href="<?php echo http_url();?>admin/admins/welcome">我的桌面</span><em></em></li>
			</ul>
		</div>
		<div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
	</div>
	<div id="iframe_box" class="Hui-article">
		<div class="show_iframe">
			<div style="display:none" class="loading"></div>
			<iframe scrolling="yes" frameborder="0" src="<?php echo http_url();?>admin/admins/welcome"></iframe>
		</div>
	</div>
</section>
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>public/admins/static/h-ui.admin/js/H-ui.admin.js"></script> 
<script type="text/javascript">
/*资讯-添加*/
function article_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*图片-添加*/
function picture_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*产品-添加*/
function product_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*用户-添加*/
function member_add(title,url,w,h){
	layer_show(title,url,w,h);
}
</script> 

</body>
</html>