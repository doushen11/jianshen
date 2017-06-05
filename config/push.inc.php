<?php
/*
 QQ:Recson
 recson@qq.com
*/
	$push_inc=array(
		"door_title"=>"进门提醒",//开门余额不足100元进行提醒标题

		"door_message"=>"您好，您的账户余额已不足100元，为了不影响您健身，请您在{time}前出门哦，切记，切记",//开门余额不足100元进行提醒内容

		"money_title"=>"健身充值小提醒",//不足100元提醒充值标题
		"money_message"=>"{name},您好,您的账户余额已经不足100元,为了不影响您的健身使用体验,请您尽快充值！",//不足100元提醒充值内容

		"pay_title"=>"充值小提醒",//充值成功后推送信息标题
		"pay_message"=>"{name},您本次充值金额：{money}元,荆门高峰期为：{height},低峰期为：{low},您在使用本次充值金额时的高峰期金额为：{height_money}元/小时,低峰期金额为：{low_money}元/小时",//充值成功后推送信息内容

		"register_title"=>"邀请好友奖励小提醒",//邀请好友奖励信息标题
		"register_message"=>"{name},您好，您在{time}成功邀请一位好友注册，系统已经把{money}元奖励存到了您的账户余额里，请注意查收",//邀请好友奖励信息内容

		"comment_title"=>"点评课程获取奖励小提醒",//课程结束点评获取奖励通知标题
		"comment_message"=>"{name},您好，您在{time}成功点评了参与课程，系统已经把{money}元奖励存到了您的账户余额里，请注意查收",//课程结束点评获取奖励通知内容

		"class_title"=>"上课提醒",//私课成立学员通知
		"class_message"=>"您好,您预约的课程已经成立了,上课时间为{date},开始时间为{start},结束时间为{end},上课老师为:{teacher},请您提前准备上课哦,祝您上课愉快",//私课成立学员通知内容

		"class_cl_title"=>"上课提醒",//私课成立教练通知
		"class_cl_message"=>"您好,您的私课已经成立,上课时间为{date},开始时间为{start},结束时间为{end},学员为:{student},请您提前准备上课哦,祝您上课愉快",//私课成立教练通知内容

		"class_clear_title"=>"课程订单被取消通知",//私课取消学员通知
		"class_clear_message"=>"您好,您预约上课时间为{date},开始时间为{start},结束时间为{end}的课程在{time}已被取消,{money}元已经退回到您的账户中",//私课取消学员通知内容

		"class_clear_teacher_title"=>"您有课程被用户或系统取消通知",//私课取消教练通知
		"class_clear_teacher_mssage"=>"您好，您的上课时间为{date},开始时间为{start},结束时间为{end}的课程在{time}已被用户或系统取消",//私课取消教练通知内容

		"ck_class_title"=>"操课成立提醒",//操课成立学员提醒
		"ck_class_message"=>"您好,您预约的课程已经成立了,上课时间为{date},开始时间为{start},结束时间为{end},上课大厅:{room},授课老师:{teacher}请您提前准备上课哦,祝您上课愉快",//操课成立学员提醒

		"ck_class_cl_title"=>"操课成立提醒",//操课成立教练提醒
		"ck_class_cl_message"=>"您好,您的操课已经成立,上课时间为{date},开始时间为{start},结束时间为{end},{room},{class}请您提前准备上课哦,祝您上课愉快",//操课成立教练提醒

		"ck_class_qx_title_member"=>"操课取消提醒",//操课取消学员提醒
		"ck_class_qx_message_member"=>"您好,您预约的上课时间为{date},开始时间为{start},结束时间为{end},上课大厅:{room},授课老师:{teacher}的{class}课程已经被系统取消",//操课取消学员提醒

		"ck_class_qx_title"=>"操课取消提醒",//操课取消教练提醒
		"ck_class_qx_message"=>"您好,您的上课时间为{date},开始时间为{start},结束时间为{end}的{class}课程已经被系统取消",//操课取消教练提醒

		"ck_no_class_title"=>"上传课程提醒",//上传课程提醒通知
		"ck_no_class_message"=>"您的最近好像没有上传课程哦，请您尽快上传哦",//上传课程提醒通知

		"class_wancheng_title"=>"课程已完成通知",//课程完成给教练通知
		"class_wancheng_message"=>"您的课程已经完成，收入{money}元",//课程完成给教练通知

		"class_wancheng_ctitle"=>"课程上完点评通知",//课程完成给学员通知
		"class_wancheng_cmessage"=>"您的课程已经完成，快去点评获取对应收入吧",//课程完成给学员通知

	);
?>