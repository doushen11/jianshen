<?php
	//操课提示上传课程推送
	require "db.inc.php";
	
	$s_time=strtotime(date("Y-m-d ")."23:59:59")+3600*24*11;//截止时间节点
	
	$e_time=strtotime(date("Y-m-d ")."23:59:59")+3600*24*14;//截止时间节点
	
	$sql="select `id`,`push_key` from `dg_teacher` where `act`='2'";
	
	$query=mysql_query($sql);
	
	$pushs=array();
	
	$i=0;
	
	while($array=mysql_fetch_assoc($query))
	{
		//查询是否有对应的课程
		$querys=mysql_query("select `id` from `dg_tearch_plans` where `tid_index`='".right_index($array["id"])."' and `tid`='".$array["id"]."' and `start_time`>='$s_time' and `end_time`<='$e_time'");	
		
		if(mysql_num_rows($querys)<=0 && $array["push_key"]!="")
		{
			//没有上传课程开始推送哦
			$pushs[$i]=$array["push_key"];	
			$i++;
		}
	}
	
	print_r($pushs);
	
	require dir."config/push.inc.php";	
	
	//集合完推送信息，调用推送Api进行推送
	if(!empty($pushs))
	{
	
		$msg=$push_inc["ck_no_class_message"];
		
		$str=implode(",",$pushs);
		
		$_arrays=array("message"=>$msg,"type"=>6,"id"=>"0","push_id"=>$str,"title"=>$push_inc["ck_no_class_title"]);
		
		a_all_push($_arrays);
	}