<?php
	//操课结束更改状态DOS脚本----更新每个月课程的参与人数
	require "db.inc.php";
	
	$times=strtotime(date("Y-m")."-01 00:00:00");//计算出本月一日凌晨时间戳
	
	$query=mysql_query("select `id` from `dg_class` where `join_t`<'$times'");//计算出最后没有重置为0的信息
	
	while($array=mysql_fetch_assoc($query))
	{
		mysql_query("update `dg_class` set `join_t`='".time()."',`join_m`='0' where `id`='".$array["id"]."'");	
	}
	
	
	
