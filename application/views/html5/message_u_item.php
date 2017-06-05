<!DOCTYPE HTML>
<html lang="en-US" manifest=""><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="Expires" content="-1">
    <title>客户消息详情</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <link href="/public/css/h5.css" rel="stylesheet" type="text/css">
    <script src="/public/js/zepto.min.js"></script>
    <script src="/public/js/swipe.js"></script>
    <script src="/public/layer/layer.js"></script>
	<style>
    img{ max-width:100%;}
    </style>
</head>
<body style="background:#e4e4e4;">
<div style="width:98%; float:left; height:30px; background:#FFF; padding-left:1%; padding-right:1%;">
	<div style="width:65%; float:left; height:30px; line-height:30px;font-size:14px; text-align:left;font-weight:bold;color:#000;">
    	<?php echo $result["title"];?>
    </div>
    <div style="width:35%; float:left; height:30px;line-height:30px;text-align:right;font-size:14px; font-weight:bold;color:#000;">
    	<?php echo date("Y.m.d H:i",$result["time"]);?>
    </div>
</div>
<div class="full use" style="background:#FFF; padding-bottom:10px; border-bottom:#999 1px solid; color:#999;width:98%;padding-left:1%; padding-right:1%;">
	<?php
    	echo stripslashes($result["contents"]);
	?>
</div>

</body>
</html>