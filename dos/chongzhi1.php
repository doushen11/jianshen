<?php
	//操课结束更改状态DOS脚本----私课更改状态
	require "db.inc.php";
	
	//查询对应的私课高峰期和低峰期
	
	//$queryer=mysql_query("select `push_key`,`nickname`,`mobile` from `dg_user` where `balance`<100 and `push_key`!=''");
	
	require dir."config/push.inc.php";
	
	$pushs=array();
	
	$i=0;
	
	$pushs=array("e492e83fdc886b1c3056fab402810397");
	
	//while($array=mysql_fetch_assoc($queryer))
	//{
			//$pushs[$i]=$array["push_key"];	
			//$i++;			
	//}
	
	//if(!empty($pushs))
	//{

						
						
	isset($_REQUEST["cid"]) && trim($_REQUEST["cid"])!=""?$cid=trim($_REQUEST["cid"]):$cid="92d777a443115fe23ce445d1a4734cde";	
		
		//echo $msg;
		
		//$m_result["push_key"]="df67d3ab0d676ba08a4333ceeacefeea";
		
	$_arrays=array("message"=>"这是一个测试内容","type"=>2,"id"=>"0","push_id"=>$cid,"title"=>"这是一个测试标题");

	//print_r($_arrays);
	
	a_push($_arrays);
	//}
	
	