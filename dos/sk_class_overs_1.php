<?php
	//私课结束更改状态DOS脚本----私课更改状态
	require "db.inc.php";
	
	//查询对应的私课高峰期和低峰期
	
	$queryer=mysql_query("select * from `dg_time_model` where `act`='2'");
	
	$arrs=array();
	
	$i=0;
	
	while($arrer=mysql_fetch_assoc($queryer))
	{
		$arrs[$i]=$arrer;
		$i++;	
	}
	
	mysql_query("SET AUTOCOMMIT=0");//设置为不自动提交，因为MYSQL默认立即执行
	
	mysql_query("BEGIN");//开始事务定义
	
	
	//查询对应前一个小时之内的课程信息，更改完成状态
	
	$time_a=strtotime("2016-08-19 22:00:00");//截止时间节点
	
	$time_b=time()-3600;//开始节点时间一个小时前
	
	$sql="select `l`.`id`,`t`.`money_desc`,`l`.`money`,`l`.`start_time`,`l`.`tid`,`l`.`date`,`l`.`node` from `dg_tearch_plan_list` as `l`,`dg_teacher` as `t` where `l`.`tid`=`t`.`id` and `l`.`end_time`<'$time_a' and `l`.`date`='2016-08-19' and `l`.`state`='3'";
	
	//$sql="select `l`.`id`,`t`.`money_desc`,`l`.`money`,`l`.`start_time`,`l`.`tid`,`l`.`date`,`l`.`node` from `dg_tearch_plan_list` as `l`,`dg_teacher` as `t` where `l`.`tid`=`t`.`id` and `l`.`end_time`<'$time_a' and `l`.`state`='3'";
	
	//echo $sql;exit();
	
	$query=mysql_query($sql);
	
	while($array=mysql_fetch_assoc($query))
	{
		//更改对应状态信息
		
		//@更新对应的课程完成信息
		if(!mysql_query("update `dg_tearch_plan_list` set `state`='6' where `id`='".$array["id"]."'"))
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();	
		}	
		
		//@更新对应的订单状态信息
		if(!mysql_query("update `dg_orders` set `state`='3' where `class_id`='".$array["id"]."' and `act`='1' and `state`='2'"))
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();	
		}
		
		//@给对应的教练加账户余额和记录
		$arr=json_decode($array["money_desc"],true);
		
		if(is_array($arr) && isset($arr["money_peak_sys"]) && isset($arr["money_slack_sys"]))
		{
			
			
			//@获取对应的价格信息，记录到教练的收益记录里面去
			$money_a=$array["money"];
			
			$money_b=create_moneys($array["money"],$arr["money_peak_sys"],$arr["money_slack_sys"],$array["start_time"],$arrs);	
			
			//echo $money_b."_____".$money_a;die();
			
			$_array=array(
				"class_node_id"=>$array["id"],
				"class_id"=>$array["id"],
				"class_img"=>"",
				"class_name"=>"",
				"class_date"=>$array["date"],
				"class_node"=>$array["node"],
			);	
			
			$text=json_encode($_array);
			
			if(!mysql_query("INSERT INTO `dg_teacher_money` (`tid_index`, `tid`, `money`, `money_in`, `models`, `act`, `text`, `time`) VALUES ('".right_index($array["tid"])."', '".$array["tid"]."', '".$money_b."', '".$money_a."', '1', '1', '$text', '".time()."')"))
			{
				mysql_query("ROLLBACK");//判断执行失败回滚
				exit();
			}
			
			//@给对应教练增加收入
			if(!mysql_query("update `dg_teacher` set `balance`=`balance`+'$money_b' where `id`='".$array["tid"]."'"))
			{
				mysql_query("ROLLBACK");//判断执行失败回滚
				exit();
			}
			
		}
		else
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();
		}
		
		
	}
	//echo 'no rollback';
	mysql_query("COMMIT");//执行事务
	
	mysql_close($handler);//关闭数据库连接
	
	//$file=fopen("E:\phpsite\Code\jianshen_10090\dos\times.php","w");
	
	//fwrite($file,date("Y-m-d H:i:s"));