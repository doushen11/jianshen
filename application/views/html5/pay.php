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
    </style>
</head>
<body>
<div class="full use">
	<?php
    	echo stripslashes($result["contents"]);
	?>
</div>

</body>
</html>