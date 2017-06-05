<?php
	//操课结束更改状态DOS脚本----操课更改状态
	require "db.inc.php";
	
	mysql_query("SET AUTOCOMMIT=0");//设置为不自动提交，因为MYSQL默认立即执行
	
	mysql_query("BEGIN");//开始事务定义
	
	
	//查询对应前一个小时之内的课程信息，更改完成状态
	
	$time_a=time();//截止时间节点
	
	$time_b=time()-3600;//开始节点时间一个小时前
	
	$sql="select `l`.`id`,`t`.`money_desc`,`l`.`sale`,`l`.`start_time`,`l`.`tid`,`l`.`date`,`l`.`node`,`l`.`class_id` ,`l`.`class_name`,`l`.`loads`,`l`.`sys`,`t`.`push_key` from `dg_tearch_plans` as `l`,`dg_teacher` as `t` where `l`.`tid`=`t`.`id` and `l`.`end_time`<'$time_a' and `l`.`date`='".date("Y-m-d")."' and (`l`.`state`='2' or `l`.`state`='3')";
	
	//echo $sql;
	
	$query=mysql_query($sql);
	
	$t_arr=array();$i=0;$c_arr=array();$w=0;//教师推送信息合集
	
	while($array=mysql_fetch_assoc($query))
	{
		//更改对应状态信息
		

		
		//@更新对应的课程完成信息
		if(!mysql_query("update `dg_tearch_plans` set `state`='5' where `id`='".$array["id"]."'"))
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();	
		}
		
		//@更新对应的订单状态信息
		if(!mysql_query("update `dg_orders` set `state`='3' where `pid`='".$array["id"]."' and `act`='2' and `state`='2'"))
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();	
		}
		
		//根据订单查询出每个会员的push_key推送信息
		$qys=mysql_query("select `u`.`push_key` from `dg_user` as `u` left join `dg_orders` as `o` on `u`.`id`=`o`.`uid` and `o`.`pid`='".$array["id"]."' and `o`.`act`='2' and `o`.`state`='3'");
		

		
		while($res=mysql_fetch_assoc($qys))
		{
			$c_arr[$w]=$res["push_key"];
			$w++;
		}
		
		//@给对应的教练加账户余额和记录
		
		//查询出对应的课程图片信息	
		$qys=mysql_query("select `bg_file` from `dg_class` where `id`='".$array["class_id"]."'");
		
		if(mysql_num_rows($qys)<=0)
		{
			$res=array("bg_file"=>"");		
		}
		else
		{
			$res=mysql_fetch_assoc($qys);		
		}
		

		//@获取对应的价格信息，记录到教练的收益记录里面去
		
		//对免费课程和非免费课程下的收入做出对应的计算以及录入操作
		if($array["sale"]>0){		
		
			$money_a=$array["sale"]*$array["loads"];
			
			$money_b=sprintf("%.2f",$array["sale"]*$array["loads"]);
			
			$money_b=sprintf("%.2f",$array["sys"]*$money_b);
		
		}
		else
		{
			
			$money_a=sprintf("%.2f",$array["moneys"]*$array["loads"]);
			
			$money_b=sprintf("%.2f",$array["moneys"]*$array["loads"]);	
						
		}
		
		if($array["push_key"]!="")
		{
			//开始拼接教师推广收入
			$t_arr[$i]["push_key"]=$array["push_key"];	
			$t_arr[$i]["money"]=$money_b;
			$i++;
		}
		
		$_array=array(
			"class_node_id"=>$array["id"],
			"class_id"=>$array["class_id"],
			"class_img"=>$res["bg_file"],
			"class_name"=>$array["class_name"],
			"class_date"=>$array["date"],
			"class_node"=>$array["node"],
		);
		
		$text=json_encode($_array);

		if(!mysql_query("INSERT INTO `dg_teacher_money` (`tid_index`, `tid`, `money`, `money_in`, `models`, `act`, `text`, `time`) VALUES ('".right_index($array["tid"])."', '".$array["tid"]."', '".$money_b."', '".$money_a."', '1', '2', '$text', '".time()."')"))
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();
		}
		
		//@给对应教练增加收入
		if(!mysql_query("update `dg_teacher` set `balance`=`balance`+'$money_b',`balance_count`=`balance_count`+'1' where `id`='".$array["tid"]."'"))
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();
		}
		
		//给对应的课程增加对应的参与人数信息
		
		$c_query=mysql_query("select `join_t` from `dg_class` where `id`='".$array["class_id"]."'");
		
		if(mysql_num_rows($c_query)>0)
		{
			$c_result=mysql_fetch_assoc($c_query);
			
			$c_times=strtotime(date("Y-m")."-01 00:00:00");//计算出本月一日凌晨时间戳
			
			if($c_result["join_t"]<$c_times)
			{
				//不是本月，直接更新
				mysql_query("update `dg_class` set `join_t`='".time()."',`join`=`join`+'".$array["loads"]."',`join_m`='".$array["loads"]."' where `id`='".$array["class_id"]."'");	
			}	
			else
			{
				//是本月，累加更新
				mysql_query("update `dg_class` set `join_t`='".time()."',`join`=`join`+'".$array["loads"]."',`join_m`=`join_m`+'".$array["loads"]."' where `id`='".$array["class_id"]."'");	
			}
		}
		//$w++;
	}
	
	mysql_query("COMMIT");//执行事务
	
	mysql_close($handler);//关闭数据库连接
	
	require dir."config/push.inc.php";

	//$t_arr[0]["money"]=100;
	//$t_arr[0]["push_key"]="6cbc74ebb6bd5267cb5953aa428e7e1";
	
	//开始推送对应的收入信息
	if(!empty($t_arr))
	{
		for($a=0;$a<count($t_arr);$a++)
		{
			$msg=str_replace("{money}",$t_arr[$a]["money"],$push_inc["class_wancheng_message"]);
			
			$_arrays=array("message"=>$msg,"type"=>5,"id"=>"0","push_id"=>$t_arr[$a]["push_key"],"title"=>$push_inc["class_wancheng_title"]);	
			
			a_push($_arrays);
		}	
	}
	
	//$c_arr[0]="df67d3ab0d676ba08a4333ceeacefeea";
	//开始推送给对应的会员点评通知
	if(!empty($c_arr))
	{
		$push_key=implode(",",$c_arr);
		
		$msg=$push_inc["class_wancheng_cmessage"];
		
		$_arrays=array("message"=>$msg,"type"=>5,"id"=>"0","push_id"=>$push_key,"title"=>$push_inc["class_wancheng_ctitle"]);
		
		//print_r($_arrays);	
			
		c_all_push($_arrays);
	}
	
?>