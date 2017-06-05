<?php
	//私课结束更改状态DOS脚本----私课更改状态
	require "db.inc.php";
	
	header("Content-type: text/html; charset=utf-8");
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
	
	$time_a=time();//截止时间节点
	
	$time_b=time()-3600;//开始节点时间一个小时前
	
	$a_1=date("Y-m-d",time()-3600*24);
	
	$a_2=date("Y-m-d",time()-3600*24*2);
	
	$a_3=date("Y-m-d",time()-3600*24*3);
	
	$a_4=date("Y-m-d",time()-3600*24*4);
	
	$sql="select `l`.`id`,`t`.`money_desc`,`l`.`money`,`l`.`start_time`,`l`.`tid`,`l`.`date`,`l`.`node`,`t`.`push_key` from `dg_tearch_plan_list` as `l`,`dg_teacher` as `t` where `l`.`tid`=`t`.`id` and `l`.`end_time`<'$time_a' and (`l`.`date`='".date("Y-m-d")."' or `l`.`date`='".$a_1."' or `l`.`date`='".$a_2."' or `l`.`date`='".$a_3."' or `l`.`date`='".$a_4."') and `l`.`state`='3'";
	//$sql="select `l`.`id`,`t`.`money_desc`,`l`.`money`,`l`.`start_time`,`l`.`tid`,`l`.`date`,`l`.`node`,`t`.`push_key` from `dg_tearch_plan_list` as `l`,`dg_teacher` as `t` where `l`.`tid`=`t`.`id` and `l`.`end_time`<'$time_a'  and `l`.`state`='3' limit 3";
		
	//$sql="select `l`.`id`,`t`.`money_desc`,`l`.`money`,`l`.`start_time`,`l`.`tid`,`l`.`date`,`l`.`node` from `dg_tearch_plan_list` as `l`,`dg_teacher` as `t` where `l`.`tid`=`t`.`id` and `l`.`end_time`<'$time_a' and `l`.`state`='3'";
	
	//echo $sql;
	//die;
	
	$t_arr=array();$i=0;$c_arr=array();$w=0;//教师推送信息合集
	
	$query=mysql_query($sql);
	
	while($array=mysql_fetch_assoc($query))
	{
		//更改对应状态信息
		//print_r($array);die;
		//@更新对应的课程完成信息
		$update_sql = "update `dg_tearch_plan_list` set `state`='6' where `id`={$array["id"]}";
		if(!mysql_query($update_sql))
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();	
		}	
		//print_r($array["id"]);die;
		//@更新对应的订单状态信息
		if(!mysql_query("update `dg_orders` set `state`='3' where `class_id`='".$array["id"]."' and `act`='1' and `state`='2'"))
		{
			mysql_query("ROLLBACK");//判断执行失败回滚
			exit();	
		}
		
		//根据订单查询出每个会员的push_key推送信息
		//$qys=mysql_query("select `u`.`push_key` from `dg_user` as `u` left join `dg_orders` as `o` on `u`.`id`=`o`.`uid` and `o`.`class_id`='".$array["id"]."' and `o`.`act`='1' and o.`state`='3'");
		$qys=mysql_query("SELECT * from   dg_orders   where class_id = '{$array["id"]}'  ");
		$res=mysql_fetch_assoc($qys);
		$qys2=mysql_query("SELECT * from   dg_user   where id = '{$res["uid"]}'  ");
		while($res=mysql_fetch_assoc($qys2))
		{
			$c_arr[$w]=$res["push_key"];
			//print_r($res);
			$w++;
		}
		//die;
		//print_r($c_arr);die;
		//@给对应的教练加账户余额和记录
		$arr=json_decode($array["money_desc"],true);
		//var_dump(is_array($arr) && isset($arr["money_peak_sys"]) && isset($arr["money_slack_sys"]));die;
		if(is_array($arr) && isset($arr["money_peak_sys"]) && isset($arr["money_slack_sys"]))
		{
			
			
			//@获取对应的价格信息，记录到教练的收益记录里面去
			$money_a=$array["money"];
			
			$money_b=create_moneys($array["money"],$arr["money_peak_sys"],$arr["money_slack_sys"],$array["start_time"],$arrs);	
			
			$_array=array(
				"class_node_id"=>$array["id"],
				"class_id"=>$array["id"],
				"class_img"=>"",
				"class_name"=>"",
				"class_date"=>$array["date"],
				"class_node"=>$array["node"],
			);	
			//print_r($c_arr);die;
			if($array["push_key"]!="")
			{
				//开始拼接教师推广收入
				$t_arr[$i]["push_key"]=$array["push_key"];	
				$t_arr[$i]["money"]=$money_b;
				$i++;
			}
			//print_r($t_arr);die;
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
	
	require dir."config/push.inc.php";
	
	//$t_arr[0]["money"]=100;
	//$t_arr[0]["push_key"]="6cbc74ebb6bd5267cb5953aa428e7e1";
	//print_r($t_arr);die;
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
	
	//$c_arr[0]="6cbc74ebb6bd5267cb5953aa428e7e1";
	//开始推送给对应的会员点评通知
	if(!empty($c_arr))
	{
		$push_key=implode(",",$c_arr);
		
		$msg=$push_inc["class_wancheng_cmessage"];
		
		$_arrays=array("message"=>$msg,"type"=>5,"id"=>"0","push_id"=>$push_key,"title"=>$push_inc["class_wancheng_ctitle"]);	
			
		c_all_push($_arrays);	
	}
	echo "success!";