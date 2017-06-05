<!DOCTYPE HTML>
<html lang="en-US" manifest=""><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="Expires" content="-1">
    <title>二维码下载</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <link href="/public/css/h5.css" rel="stylesheet" type="text/css">
    <script src="/public/js/zepto.min.js"></script>
    <script src="/public/js/swipe.js"></script>
    <script src="/public/layer/layer.js"></script>
	<style>
    img{ max-width:100%;}
    body,td,th {
	font-family: "微软雅黑";
	}
	a{color:#666; text-decoration:none;}
    </style>
</head>
<body>

<?php
	require FCPATH."config/sys.inc.php";
?>


<div class="full use" style="text-align:center;font-weight:bold;padding-bottom:8px;">
	<p>您的邀请码是：<strong><?php echo $id;?></strong></p>
</div>
<div class="full use">
    <div style="width:90%; margin-left:5%; margin-right:5%; float:left;">
        安卓下载地址：<a href="<?php echo $_sys_inc["a_http"];?>"><?php echo $_sys_inc["a_http"];?></a>
    </div>
    <div style="width:90%; margin-left:5%; margin-right:5%; float:left; padding-top:15px;">
        IOS下载地址：<a href="<?php echo $_sys_inc["i_http"];?>"><?php echo $_sys_inc["i_http"];?></a>
    </div>
    <div style="width:90%; margin-left:5%; margin-right:5%; float:left; padding-top:15px;color:#F00; line-height:26px;">
    	温馨提示：请在其他浏览器里面打开本网页，然后根据您的手机版本选择对应的软件进行下载
    </div> 
</div>

</body>
</html>