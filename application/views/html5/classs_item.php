<!DOCTYPE HTML>
<html lang="en-US" manifest=""><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="Expires" content="-1">
    <title>课程图文详情</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <link href="/public/css/h5.css" rel="stylesheet" type="text/css">
    <script src="/public/js/zepto.min.js"></script>
    <script src="/public/js/swipe.js"></script>
    <script src="/public/layer/layer.js"></script>
	<style>
    img{ max-width:100%;}
	embed{max-width:100%; max-height:300px;}
    </style>
</head>
<body>
<div class="full use" style="text-align:center">
	<?php
    	if($result["act"]==1){
	?>
    <img src="/<?php echo $result["path"];?>">
    <?php
		}
		else
		{
	?>
    <embed src="<?php echo base_url().$result["path"];?>" volume=70 autostart=true></embed> 
    <?php
		}
	?>
</div>
<div class="full use" style="padding-top:15px; width:96%; padding-left:2%; padding-right:2%;">
	<?php
    	echo nl2br($result["contents"]);
	?>
</div>

</body>
</html>