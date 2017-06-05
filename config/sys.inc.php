<?php
/*
 QQ:Recson
 recson@qq.com
*/
	$_sys_inc=array(
		"doors_time"=>"10",//进门扫码延时秒数

		"captcha_time"=>"60",//两次短信发送间隔次数

		"captcha_lives"=>"192600",//验证码的时效性，默认1800秒，即半小时

		"class_close_time_a"=>"14400",//开课前多久不允许取消课程时间--私课

		"class_close_time_b"=>"14400",//开课前多久没达到人数取消课程时间--操课

		"class_insert_count_day"=>"3",//每日最多允许发布课程的数量

		"a_http"=>"http://fusion.qq.com/cgi-bin/qzapps/unified_jump?appid=52398316&from=mqq&actionFlag=0&params=pname%3Dcom.tjtd.zhinengjianshen%26versioncode%3D1%26channelid%3D%26actionflag%3D0",//安卓下载地址

		"i_http"=>"https://itunes.apple.com/cn/app/zi-jian-shen/id1180555840?mt=8",//IOS下载地址

		"code_reg"=>"您的注册验证码为{code},请在半个小时内使用，预期作废",//注册短信验证码

		"code_login"=>"您的登录验证码为{code},请在半个小时内使用，预期作废",//登录短信验证码

		"code_reset"=>"您的找回密码验证码为{code},请在半个小时内使用，预期作废",//找回密码短信验证码

	);
?>