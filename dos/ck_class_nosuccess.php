<?php
	//操课结束更改状态DOS脚本----操课开课前未成立，退款操作
	require "db.inc.php";
	
	require dir."config/sys.inc.php";
	
	mysql_query("SET AUTOCOMMIT=0");//设置为不自动提交，因为MYSQL默认立即执行
	
	mysql_query("BEGIN");//开始事务定义
	
	//查询对应前一个小时之内的课程信息，更改完成状态
	
	$time_a=time()-$_sys_inc["class_close_time_b"];//截止时间节点
	
	//echo $time_a;die();
	
	$time_b=time()-3600;//开始节点时间一个小时前	
	
	$sql="select `l`.`id`,`t`.`money_desc`,`l`.`sale`,`l`.`start_time`,`l`.`end_time`,`l`.`tid`,`l`.`date`,`l`.`node`,`l`.`class_id` ,`l`.`class_name`,`t`.`push_key`,`l`.`room_name`,`l`.`tid_name`,`l`.`class_name` from `dg_tearch_plans` as `l`,`dg_teacher` as `t` where `l`.`tid`=`t`.`id` and `l`.`start_time`<='$time_a' and `l`.`date`='".date("Y-m-d")."' and (`l`.`state`='1')";
	
	//echo $sql;
	
	$query=mysql_query($sql);
	
	$t_arr=array();$a++;
	$x_arr=array();$b++;
	while($array=mysql_fetch_assoc($query))
	{
		
		//if($array["push_key"])
		//{
			//组合对应的教练推送
			$t_arr[$a]["push_key"]=$array["push_key"];
			$t_arr[$a]["room"]=$array["room_name"];
			$t_arr[$a]["id"]=$array["tid"];//教练的id，做消息添加使用
			$t_arr[$a]["class"]=$array["class_name"];
			$t_arr[$a]["start"]=date("H:i",$array["start_time"]);
			$t_arr[$a]["end"]=date("H:i",$array["end_time"]);
		//}
	
		//@更新对应的课程完成信息
		if(!mysql_query("update `dg_tearch_plans` set `state`='4' where `id`='".$array["id"]."'"))
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();	
		}
		
		//开始统计对应的订单，进行退款处理
		
		$querys=mysql_query("select `o`.`uid`,`o`.`returns`,`o`.`id`,`o`.`money`,`u`.`push_key` from `dg_orders` as `o` left join `dg_user` as `u` on `o`.`uid`=`u`.`id` where `o`.`act`='2' and `o`.`pid`='".$array["id"]."' and `o`.`state`='1'");
		
		while($arrays=mysql_fetch_assoc($querys))
		{	
			$x_arr[$b]["id"].=",".$arrays["uid"];
			$x_arr[$b]["push_key"].=",".$arrays["push_key"];
			$x_arr[$b]["room"]=$array["room_name"];
			$x_arr[$b]["class"]=$array["class_name"];
			$x_arr[$b]["start"]=date("H:i",$array["start_time"]);
			$x_arr[$b]["end"]=date("H:i",$array["end_time"]);
			$x_arr[$b]["teacher"]=date("H:i",$array["tid_name"]);				
			
			//更新订单状态
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
				
				//给对应的用户账户余额累计增加
				mysql_query("update `dg_user` set `balance`=`balance`+'".$arrays["money"]."' where `id`='".$arrays["uid"]."'");
			
			}
		}
		
		$b++;
			
	}
	
	
	mysql_query("COMMIT");//执行事务
	
	mysql_close($handler);//关闭数据库连接	
	
	require dir."config/push.inc.php";
	
	if(isset($t_arr) && is_array($t_arr) && !empty($t_arr))
	{
		//开始推送给教练
		for($a=0;$a<count($t_arr);$a++)
		{
			$msg=str_replace("{class}",$t_arr[$a]["class"],$push_inc["ck_class_qx_message"]);
			
			$msg=str_replace("{date}",$t_arr[$a]["date"],$msg);
			
			$msg=str_replace("{start}",$t_arr[$a]["start"],$msg);
			
			$msg=str_replace("{end}",$t_arr[$a]["end"],$msg);
			
			$msg=str_replace("{room}",$t_arr[$a]["room"],$msg);	
			
			//给教练插入一条消息信息
			message_insert($push_inc["ck_class_qx_title"],$msg,$t_arr[$a]["id"],1);
			
			if($t_arr[$a]["push_key"]!="")
			{
			
				$_arrays=array("message"=>$msg,"type"=>4,"id"=>"0","push_id"=>$t_arr[$a]["push_key"],"title"=>$push_inc["ck_class_qx_title"]);
			
				a_push($_arrays);
			}			
		}	
	}
	
	if(isset($t_arr) && is_array($t_arr) && !empty($t_arr))
	{
		//开始推送给学生
		for($a=0;$a<count($t_arr);$a++)
		{
			$msg=str_replace("{class}",$t_arr[$a]["class"],$push_inc["ck_class_qx_message_member"]);
			
			$msg=str_replace("{date}",$t_arr[$a]["date"],$msg);
			
			$msg=str_replace("{start}",$t_arr[$a]["start"],$msg);
			
			$msg=str_replace("{end}",$t_arr[$a]["end"],$msg);
			
			$msg=str_replace("{room}",$t_arr[$a]["room"],$msg);	
			$msg=str_replace("{teacher}",$t_arr[$a]["teacher"],$msg);	
			
			//拆解有效的推送cid
			$push_strs="";
			$arrs=trim($t_arr[$a]["push_key"],",");
			$arrs=explode(",",$arrs);
			foreach($arrs as $kk=>$vv)
			{
				if(trim($vv)!="")
				{
					$push_strs.=",".$v;
				}
			}
			//拆解有效的推送cid
			
			//开始拆解uid进行发送消息
			$uids=explode(",",trim($t_arr[$a]["id"],","));
			foreach($uids as $kk=>$vv)
			{
				if(is_numeric($vv))
				{
					message_insert($push_inc["ck_class_qx_title_member"],$msg,$vv);
				}
			}
			//开始拆解uid进行发送消息
			$_arrays=array("message"=>$msg,"type"=>4,"id"=>"0","push_id"=>trim($push_strs,","),"title"=>$push_inc["ck_class_qx_title_member"]);
			
			c_push_all($_arrays);													
		}
	}