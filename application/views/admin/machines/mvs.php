<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>视频预览</title>
</head>

<body>
<p align="center" style="padding-top:18px;"><embed src="" width="393" height="265"></embed>

<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="441" height="346"
    <param name="movie" value="Flvplayer.swf" />
    <param name="quality" value="high" />
    <param name="allowFullScreen" value="true" />
    <param name="FlashVars" value="vcastr_file=<?php echo base_url();?><?php echo @$_GET["p"];?>&IsAutoPlay=1&LogoText=www.fccctv.cn"/>
    <embed src="images/Flvplayer.swf" allowfullscreen="true" flashvars="vcastr_file=<?php echo base_url();?><?php echo @$_GET["p"];?>>&IsAutoPlay=1-->&LogoText=www.fccctv.cn" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="500" height="400"></embed>
  </object>
</p>
</body>
</html>
