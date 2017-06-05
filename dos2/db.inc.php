<?php
	//配置防攻击常量
	
	define("BASEPATH","Recson");
	
	//定义操作目录
	
	define("dir",trim(dirname(__FILE__),"dos2"));
	require dir."application/config/database.php";
	//数据库配置连接
	
	$handler=mysql_connect($db['default']['hostname'],$db['default']['username'],$db['default']['password']) or die('database message error!');
	
	mysql_select_db($db['default']['database']);
	
	mysql_query("set names ".$db['default']['char_set']);
	
	//插入消息
	function message_insert($title,$content,$uid,$act="")
	{
		$table="dg_message_u";
		if($act!="")
		{
			$table="dg_message_t";
		}
		//开始CURL发送数据
		$sql="insert into `".$table."`(`uid`,`title`,`contents`,`read`,`time`)value('$uid','$title','$content','1','".time()."')";
		mysql_query($sql);
	}		
	
	//获取索引查询键值
	function right_index($str)
	{
		if($str==""){
			return 0;	
		}else{
			if(strlen($str)==1){
				return $str;	
			}else{
				return substr($str,strlen($str)-1,1);
			}	
		}			
	}

	//配置类文件
	function create_moneys($money,$peak_sys,$slack_sys,$starts,$arrs)
	{
		$d=date("H",$starts);
		$d=intval($d);
		
		//echo $d."\n\r";
		
		for($i=0;$i<count($arrs);$i++)
		{
			if(($arrs[$i]["min"]<=$d && $arrs[$i]["max"]>$d))
			{
				//如果第一位数小于当前失效，直接取第一个
				if($arrs[$i]["model"]==1)
				{
					return sprintf("%.2f",$money*$peak_sys);
				}
				else
				{
					return sprintf("%.2f",$money*$slack_sys);	
				}	
			}
			else
			{
				//开始时间大于结束时间
				if($arrs[$i]["min"]>$arrs[$i]["max"])
				{
					
					
					if($arrs[$i]["min"]<=$d || ($arrs[$i]["max"]>$d && $d>=0))
					{
						//echo 100;
						if($arrs[$i]["model"]==1)
						{
							return sprintf("%.2f",$money*$peak_sys);
						}
						else
						{
							return sprintf("%.2f",$money*$slack_sys);	
						}							
					}	
				}
			}
			//return sprintf("%.2f",$money*$peak_sys);
		}
		
	}
	
	if(!function_exists('base_url'))
	{
		function base_url()
		{
			return "http://101.201.37.67:10090/";
		}
	}
	
	if(!function_exists('c_push'))
	{
		function c_push($array)
		{
			$urls=base_url()."push/client_one_push.php?";
			$strs="";
			foreach($array as $k=>$v)
			{
				$strs.="&".$k."=".urlencode($v);	
			}	
			$strs=trim($strs,"&");
			$urls=$urls.($strs);
			$ch = curl_init($urls) ;  
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
			$output = curl_exec($ch);
			echo $output;
			curl_close($ch);
		}
	}

	if(!function_exists('c_all_push'))
	{
		function c_all_push($array)
		{
			$urls=base_url()."push/client_group_push.php?";
			$strs="";
			foreach($array as $k=>$v)
			{
				$strs.="&".$k."=".urlencode($v);	
			}	
			$strs=trim($strs,"&");
			$urls=$urls.($strs);
			$ch = curl_init($urls) ;  
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
			$output = curl_exec($ch);
			echo $output;
			curl_close($ch);
		}
	}
	
	if(!function_exists('a_push'))
	{
		function a_push($array)
		{
			$urls=base_url()."push/master_one_push.php?";
			$strs="";
			foreach($array as $k=>$v)
			{
				$strs.="&".$k."=".urlencode($v);	
			}	
			$strs=trim($strs,"&");
			$urls=$urls.($strs);
			$ch = curl_init($urls) ;  
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
			$output = curl_exec($ch);
			echo $output;
			curl_close($ch);
		}
	}
	
	if(!function_exists('a_all_push'))
	{
		function a_all_push($array)
		{
			$urls=base_url()."push/master_group_push.php?";
			$strs="";
			foreach($array as $k=>$v)
			{
				$strs.="&".$k."=".urlencode($v);	
			}	
			$strs=trim($strs,"&");
			$urls=$urls.($strs);
			$ch = curl_init($urls) ;  
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
			$output = curl_exec($ch);
			echo $output;
			curl_close($ch);
		}
	}