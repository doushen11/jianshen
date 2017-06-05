<?php
	//私课结束更改状态DOS脚本----私课开课前未成立
	require "db.inc.php";
	
	header("Content-type: text/html; charset=utf-8");
	
	mysql_query("SET AUTOCOMMIT=0");//设置为不自动提交，因为MYSQL默认立即执行
	
	mysql_query("BEGIN");//开始事务定义
	
	//查询对应前一个小时之内的课程信息，更改完成状态
	
	$time_a=time();//截止时间节点
	
	//$time_a=time() - 3600;//开始节点时间一个小时前
	
	$sql="select `l`.`id`,`t`.`money_desc`,`l`.`money`,`l`.`start_time`,`l`.`end_time`,`l`.`tid`,`l`.`date`,`l`.`node`,`t`.`push_key` from `dg_tearch_plan_list` as `l`,`dg_teacher` as `t` where `l`.`tid`=`t`.`id` and `l`.`start_time`<'$time_a'  and `l`.`state`='2' limit 5";

	$query=mysql_query($sql);
	$arr=array();$i=0;
	while($array=mysql_fetch_assoc($query))
	{
	    //print_r($array);die;
         
		//@更新对应的课程完成信息
		if(!mysql_query("update `dg_tearch_plan_list` set `state`='4' where `id`='".$array["id"]."'"))
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();	
		}
		
		if($array["push_key"]!="")
		{
			$arr[$i]["push_key"]=$array["push_key"];
			$arr[$i]["date"]=$array["date"];
			$arr[$i]["start"]=date("H:i",$array["start_time"]);
			$arr[$i]["end"]=date("H:i",$array["end_time"]);
			$i++;	
		}
		
	}
	mysql_query("COMMIT");//执行事务
	
	mysql_close($handler);//关闭数据库连接

	require dir."config/push.inc.php";
	
	if(!empty($arr))
	{
	
		for($i=0;$i<count($arr);$i++)
		{
			//开始推送给对应老师
			
			$msg=str_replace("{time}",date("Y-m-d H:i:s"),$push_inc["class_clear_teacher_mssage"]);
			
			$msg=str_replace("{date}",$arr[$i]["date"],$msg);
			
			$msg=str_replace("{start}",$arr[$i]["start"],$msg);
			
			$msg=str_replace("{end}",$arr[$i]["end"],$msg);	
		
			$_arrays=array(
			    "message"=>$msg,
			    "type"=>4,
			    "id"=>"0",
			    "push_id"=>$arr[$i]["push_key"],
			    "title"=>$push_inc["class_clear_teacher_title"]
			);
			
			a_push($_arrays);				
		}
	
	}
	
	echo "success!";
	
?>