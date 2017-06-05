<?php  
	//操课结束更改状态DOS脚本----操课开课4个小时前未成立，退款操作
	require "db.inc.php";
	header("Content-type: text/html; charset=utf-8");
	mysql_query("SET AUTOCOMMIT=0");//设置为不自动提交，因为MYSQL默认立即执行
	
	mysql_query("BEGIN");//开始事务定义
	
	//查询对应前一个小时之内的课程信息，更改完成状态
	
	//$time_a=strtotime("2026-08-20 22:00:00");//截止时间节点
	
	$time_a=time() + 3600 * 4;//开始节点时间一个小时前	
	$sql="select `l`.`id`,`t`.`money_desc`,`l`.`sale`,`l`.`start_time`,`l`.`tid`,`l`.`date`,`l`.`node`,`l`.`class_id` ,`l`.`class_name` from `dg_tearch_plans` as `l`,`dg_teacher` as `t` where `l`.`tid`=`t`.`id` and `l`.`start_time` < {$time_a}  and l.min > l.loads  and (`l`.`state`='1') limit 3";
	$query=mysql_query($sql);
	
	while($array=mysql_fetch_assoc($query))
	{
		//@更新对应的课程完成信息
		//if(!mysql_query("update `dg_tearch_plans` set `state`='4' where `id`= 1000000 "))
		if(!mysql_query("update `dg_tearch_plans` set `state`='4' where `id`='".$array["id"]."'"))
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();	
		}	
		
		//开始统计对应的订单，进行退款处理
		$sql = "select `uid`,`returns`,`id`,`money` from `dg_orders` where `act`='2' and `pid`='".$array["id"]."' and `state`='1'";
		//echo $sql;die;
		$querys=mysql_query($sql);
		
		while($arrays=mysql_fetch_assoc($querys))
		{
			//更新订单状态
			//mysql_query("update `dg_orders` set `state`='4' where `id`='1000000'");
			mysql_query("update `dg_orders` set `state`='4' where `id`='".$arrays["id"]."'");
			
			//判断是否免费课程，开始退款
			if($array["sale"]>0){
			
				//开始退款处理
				$arr=json_decode($arrays["returns"],true);
				
				//回滚金额到每个对应的账户字节里面去
				if(is_array($arr) && isset($arr[0]["money"]) && trim($arr[0]["money"])!="")
				{
					for($a=0;$a<count($arr);$a++)
					{
						
						mysql_query("update `dg_pay_order` set `money_remaining`=`money_remaining`+'".$arr[$a]["money"]."' where `id`='".$arr[$a]["id"]."'");	
						
					}
				}
				
				//print_r($arr);
				
				//exit();
				
				//给对应的用户账户余额累计增加
				mysql_query("update `dg_user` set `balance`=`balance`+'".$arrays["money"]."' where `id`='".$arrays["uid"]."'");
			
			}
		}
		//消减参与人数
		mysql_query("update `dg_tearch_plans` set  `loads`=`loads`-'1' where `id`='{$array["id"]}'");
			
	}
	
	
	
	mysql_query("COMMIT");//执行事务
	
	mysql_close($handler);//关闭数据库连接	
	
	echo "success!";