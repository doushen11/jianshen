<!DOCTYPE HTML>
<html lang="en-US" manifest=""><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="Expires" content="-1">
    <title><?php echo $result["title"];?></title>
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
    </style>
</head>
<body>
<div class="full use" style="text-align:center;font-weight:bold; padding-bottom:12px;">
<?php
	require FCPATH."config/img.inc.php";
?>
<img src="/<?php echo $img_inc["j_notice_bg"];?>">
</div>
<div class="full use" style="text-align:center;font-weight:bold;padding-bottom:8px;">
	
	<?php
    	echo stripslashes($result["title"]);
	?>
    
</div>
<div class="full use">
<div style="width:90%; margin-left:5%; margin-right:5%; float:left;">
	<?php
    	echo stripslashes($result["contents"]);
	?>
    </div>
</div>

</body>
</html>