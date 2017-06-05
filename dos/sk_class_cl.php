<?php
	//私课成立DOS脚本----私课更改状态
	require "db.inc.php";
	
	header("Content-type: text/html; charset=utf-8");
	
	require dir."config/sys.inc.php";
	
	$time=time()+$_sys_inc["class_close_time_a"];
	
	mysql_query("SET AUTOCOMMIT=0");//设置为不自动提交，因为MYSQL默认立即执行
	
	mysql_query("BEGIN");//开始事务定义	
	
	$a_1=date("Y-m-d",time()-3600*24);
	
	$a_2=date("Y-m-d",time()-3600*24*2);
	
	$a_3=date("Y-m-d",time()-3600*24*3);
	
	$a_4=date("Y-m-d",time()-3600*24*4);	
	
	$sql="select `o`.`uid`,`o`.`id`,`l`.tid,`l`.`date`,`l`.`start_time`,`l`.`end_time` from `dg_tearch_plan_list` as `l`,`dg_orders` as `o` where `l`.`id`=`o`.`class_id`  and `l`.`start_time`<'$time'  and `o`.`state`='1' and `o`.`act`='1' limit 5";
	
	//echo $sql;die();
	
	$query=mysql_query($sql);
	
	$arrays=array();
	
	$i=0;
	
	while($array=mysql_fetch_assoc($query))
	{
		//@更新对应的订单状态信息
		if(!mysql_query("update `dg_orders` set `state`='2' where `id`='".$array["id"]."'"))
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();	
		}
		//组合对应的用户信息
		
		$sqls="select `push_key`,`nickname`,`mobile` from `dg_user` where `id`='".$array["uid"]."'";
		
		$querys=mysql_query($sqls);
		
		$results=mysql_fetch_assoc($querys);
		
		$sqls1="select `realname` from `dg_teacher` where `id`='".$array["tid"]."'";
		
		$querys1=mysql_query($sqls1);
		
		$results1=mysql_fetch_assoc($querys1);	
		
		//print_r($results1);die;
		//var_dump($results["push_key"]!="");die;
		
		if($results["push_key"]!="")
		{
		
			$arrays[$i]["push_key"]=$results["push_key"];	

			if($results["nickname"]!="")
			{
				$arrays[$i]["nickname"]=$results["nickname"];	
			}
			else
			{
				$arrays[$i]["nickname"]=substr($results["mobile"],0,3)."****".substr($results["mobile"],7,4);	
			}	
			
			$arrays[$i]["teacher"]=$results1["realname"];	
			
			$arrays[$i]["date"]=$array["date"];	
			
			$arrays[$i]["start_time"]=date("H:i",$array["start_time"]);	
			
			$arrays[$i]["end_time"]=date("H:i",$array["end_time"]);	
		
			$i++;
		
		}
	}
	//print_r($arrays);die;
	mysql_query("COMMIT");//执行事务
	
	mysql_close($handler);//关闭数据库连接
	//开始推送对应信息
	
	require dir."config/push.inc.php";
	
	if(!empty($arrays))
	{
		
		
		for($i=0;$i<count($arrays);$i++)
		{
			//推送给对应的学生
			$msg=str_replace("{name}",$arrays[$i]["nickname"],$push_inc["class_message"]);
			
			$msg=str_replace("{date}",$arrays[$i]["date"],$msg);
			
			$msg=str_replace("{start}",$arrays[$i]["start_time"],$msg);
			
			$msg=str_replace("{end}",$arrays[$i]["end_time"],$msg);
			
			$msg=str_replace("{teacher}",$arrays[$i]["teacher"],$msg);
					
		
			$_arrays=array("message"=>$msg,"type"=>4,"id"=>"0","push_id"=>$arrays[$i]["push_key"],"title"=>$push_inc["class_title"]);
			
			c_push($_arrays);	
			
			//开始推送给对应老师
			
			$msg=str_replace("{student}",$arrays[$i]["nickname"],$push_inc["class_cl_message"]);
			
			$msg=str_replace("{date}",$arrays[$i]["date"],$msg);
			
			$msg=str_replace("{start}",$arrays[$i]["start_time"],$msg);
			
			$msg=str_replace("{end}",$arrays[$i]["end_time"],$msg);	
		
			$_arrays=array("message"=>$msg,"type"=>4,"id"=>"0","push_id"=>$arrays[$i]["push_key"],"title"=>$push_inc["class_cl_title"]);
			//print_r($_arrays);die;
			a_push($_arrays);
		}
		
		//$push_keys=trim(implode(",",$arrays),",");
		
			
	}
	
	echo "success!";