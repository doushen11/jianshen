<?php
	/**
	 +------------------------------------------------------------------------------
	 * wsdl服务端  
	 +------------------------------------------------------------------------------
	 * @wsdl服务端接收
	 * @Author Recson<admin@zjtd100.com>
	 * @Copyright (c) www.zjtd100.com
	 +------------------------------------------------------------------------------
	 */
	
	
	define('WSDL_URL','doors.wsdl');        //定义WSDL文件路径
	ini_set('soap.wsdl_cache_enabled','0');    //关闭WSDL缓存
	 
	 //WSDL文件不存在时自动创建
	if(!file_exists(WSDL_URL))
	{
		require_once 'SoapDiscovery.class.php';
		$disco = new SoapDiscovery('Mywsdl','101.201.37.67');
		$str = $disco->getWSDL();
		file_put_contents(WSDL_URL,$str);
	}
	 
	//SOAP开启并接收Client传入的参数响应 
	$server = new SoapServer(WSDL_URL);
	$server->setClass('Mywsdl');
	$server->handle();
	
	class Mywsdl {
				
		public function __construct() 
		{
			//定义根目录数据，开始连接数据库
			define("BASEPATH","recsons");
			$path=str_replace("websevice","",dirname(__FILE__));
			require $path."application/config/database.php";
			@mysql_connect($db['default']["host_name"],$db['default']["username"],$db['default']["password"]);
			@mysql_select_db($db['default']["database"]);
			@mysql_query("set names ".$db['default']["char_set"]);
		}
		
		public function return_success($str)
		{
			
			//$files=fopen($_SERVER["DOCUMENT_ROOT"]."/webservice.php","a");
			//fwrite($files,$str."___________".date("Y-m-d H:i;s")."<hr></hr>");
			
			if(substr_count($str,"_")==4)
			{
				$arr=explode("_",$str);
				//return $arr[0]."_____".$arr[1];
				if(is_array($arr) && is_numeric($arr[0]) && sha1(trim($arr[0])."recsons500wolfes")==trim($arr[1]))
				{
					
					//清空用户信息数据，开始进入进门时间操作
					$uid=$arr[0];
					
					
					if($arr[4]==1)
					{
						//学员进门出门回执操作
						$result=mysql_query("select * from `dg_user` where `id`='".$uid."'");
						
						if(trim($arr[2])==1){
							//学员进门处理
							
							$rss=mysql_query("select `end_time` from `dg_doors` where `uid`='$uid' and `act`='1' order by `id` desc limit 1");
							
							if(mysql_num_rows($rss)>0)
							{
								$res=mysql_fetch_assoc($rss);
								if($res["end_time"]=="")
								{
									return "failed";	
								}	
							}						
							
							if(mysql_num_rows($result)>0)
							{
								$array=mysql_fetch_assoc($result);
								
								if($array["doors"]!=0)
								{
									return "failed";	
								}
								
								mysql_query("update `dg_user` set `doors_keys`='',`doors`='1' where `id`='$uid'");
								//开始插入一条进门记录
								mysql_query("INSERT INTO `dg_doors` (`uid`, `act`, `start_time`, `end_time`, `money`) VALUES ('$uid','1', '".time()."', NULL, '0.00');");	
								if($array["balance"]<100)
								{
									//开始curl推送对应的余额不足信息
									$this->push_msg($str);
								}
								mysql_query("update `dg_config` set `people`=`people`+'1' where `id`='1'");
								//开始查询对应的用户信息，如果为1人，开电
								$this->open_l();
								return "success";
							}
							else
							{
								return "failed";	
							}
						
						}
						elseif(trim($arr[2])==2)
						{
							//学员出门处理
							$rss=mysql_query("select `id`,`end_time` from `dg_doors` where `uid`='$uid' and `act`='1' order by `id` desc limit 1");
							
							if(mysql_num_rows($rss)>0)
							{
								$res=mysql_fetch_assoc($rss);
								if($res["end_time"]=="")
								{
									$end_time=$arr[3];
									//return $end_time;
									
									return $this->curl_out_doors($uid,$end_time);
								}
								else
								{	
									return "failed";
								}
							}
							else
							{
								return "failed";
							}
						}
					
					}
					elseif($arr[4]==2)
					{
						//教练进门出门回执操作
						if(trim($arr[2])==1){
							//教练进门处理
							$result=mysql_query("select * from `dg_teacher` where `id`='".$uid."'");
							$rss=mysql_query("select `end_time` from `dg_doors` where `uid`='$uid' and `act`='2' order by `id` desc limit 1");
							
							if(mysql_num_rows($rss)>0)
							{
								$res=mysql_fetch_assoc($rss);
								if($res["end_time"]=="")
								{
									return "failed";	
								}	
							}						
							//echo 100;die();
							if(mysql_num_rows($result)>0)
							{
								$array=mysql_fetch_assoc($result);
								
								if($array["doors"]!=0)
								{
									return "failed";	
								}
								
								mysql_query("update `dg_teacher` set `doors_keys`='',`doors`='1' where `id`='$uid'");
								
								//开始插入一条进门记录
								
								mysql_query("INSERT INTO `dg_doors` (`uid`, `act`, `start_time`, `end_time`, `money`) VALUES ('$uid','2', '".time()."', NULL, '0.00');");	
								
								mysql_query("update `dg_config` set `people`=`people`+'1' where `id`='1'");
								//开始查询对应的用户信息，如果为1人，开电
								$this->open_l();
								return "success";
							}
							else
							{
								return "failed";	
							}
						
						}	
						elseif(trim($arr[2])==2)
						{
							//教练出门处理
							$rss=mysql_query("select `id`,`end_time` from `dg_doors` where `uid`='$uid' and `act`='2' order by `id` desc limit 1");
							
							if(mysql_num_rows($rss)>0)
							{
								$res=mysql_fetch_assoc($rss);
								if($res["end_time"]=="")
								{
									$end_time=$arr[3];
									mysql_query("update `dg_teacher` set `doors`='0',`doors_keys`='' where `id`='$uid'");//更新教师的当前状态
									$end_time=$arr[3];
									mysql_query("update `dg_doors` set `end_time`='".$end_time."' where `id`='".$res["id"]."'");//更新最后出门的记录
									mysql_query("update `dg_config` set `people`=`people`-'1' where `id`='1'");//更新健身房人数
									$query=$this->db->query("select * from `dg_config` where `id`='1'");
									$result=$query->row_array();
									if($result["people"]==0)
									{
										$this->close_l();
									}
									return "success";
								}
								else
								{	
									return "failed";
								}
							}	
							else
							{
								return "failed";
							}
						}
					}
					elseif($arr[4]==3)
					{
						//sos进门出门回执操作
						if(trim($arr[2])==1){
							//sos进门处理
							$result=mysql_query("select * from `dg_worker` where `id`='".$uid."'");
							$rss=mysql_query("select `end_time` from `dg_doors` where `uid`='$uid' and `act`='3' order by `id` desc limit 1");
							
							if(mysql_num_rows($rss)>0)
							{
								$res=mysql_fetch_assoc($rss);
								if($res["end_time"]=="")
								{
									return "failed";	
								}	
							}						
							//echo 100;die();
							if(mysql_num_rows($result)>0)
							{
								$array=mysql_fetch_assoc($result);
								
								if($array["doors"]!=0)
								{
									return "failed";	
								}
								
								mysql_query("update `dg_worker` set `doors_keys`='',`doors`='1' where `id`='$uid'");
								
								//开始插入一条进门记录
								
								mysql_query("INSERT INTO `dg_doors` (`uid`, `act`, `start_time`, `end_time`, `money`) VALUES ('$uid','3', '".time()."', NULL, '0.00');");	
								
								mysql_query("update `dg_config` set `people`=`people`+'1' where `id`='1'");
								//开始查询对应的用户信息，如果为1人，开电
								$this->open_l();
								return "success";
							}
							else
							{
								return "failed";	
							}
						
						}	
						elseif(trim($arr[2])==2)
						{
							//sos出门处理
							$rss=mysql_query("select `id`,`end_time` from `dg_doors` where `uid`='$uid' and `act`='3' order by `id` desc limit 1");
							
							if(mysql_num_rows($rss)>0)
							{
								$res=mysql_fetch_assoc($rss);
								if($res["end_time"]=="")
								{
									$end_time=$arr[3];
									mysql_query("update `dg_worker` set `doors`='0',`doors_keys`='' where `id`='$uid'");//更新教师的当前状态
									$end_time=$arr[3];
									mysql_query("update `dg_doors` set `end_time`='".$end_time."' where `id`='".$res["id"]."'");//更新最后出门的记录
									mysql_query("update `dg_config` set `people`=`people`-'1' where `id`='1'");//更新健身房人数
									$query=$this->db->query("select * from `dg_config` where `id`='1'");
									$result=$query->row_array();
									if($result["people"]==0)
									{
										$this->close_l();
									}
									return "success";
								}
								else
								{	
									return "failed";
								}
							}	
							else
							{
								return "failed";
							}
						}
					}
				}	
				else
				{
					return "failed";	
				}
			}
			else
			{
				return "failed";	
			}
		}
		
		private function close_l()
		{
			$f=fopen("11111.php","w");
			fwrite($f,date("Y-m-d H:i:s"));
			$urls="http://".$_SERVER['HTTP_HOST']."/apis/l_close.php?keys=1282d94a4f461110b676f711b221d86a76b8a8008982064b6bc94d08ec2b58fff9023a3699ba8e03adebe8a12359bb772ee639a5c418c908";		
			//$fs=fopen(FCPATH."/z100.php","a");
			
			//fwrite($fs,$urls."________".date("Y-m-d H:i:s")."<hr></hr>");
				
			$ch = curl_init($urls) ;  
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
			$output = curl_exec($ch);
			//echo $output;die();
			
			return $output;	
			curl_close($ch);						
		}
		
		private function open_l()
		{	
			$urls="http://".$_SERVER['HTTP_HOST']."/index.php/client/doors/opens/open_l";
			//echo $urls;die();
			$ch = curl_init($urls) ;  
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
			$output = curl_exec($ch);
			//echo $output;die();
			
			return $output;	
			curl_close($ch);
		}
		
		private function push_msg($str)
		{
			$urls="http://".$_SERVER['HTTP_HOST']."/index.php/client/doors/opens/pushs?str=".$str;
			//echo $urls;die();
			$ch = curl_init($urls) ;  
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
			$output = curl_exec($ch);
			//echo $output;
			curl_close($ch);			
		}	
		
		private function curl_out_doors($uid,$time)
		{
			$urls="http://".$_SERVER['HTTP_HOST']."/index.php/client/doors/opens/outs_do?uid=".$uid."&ends=".$time;
			//echo $urls;die();
			$ch = curl_init($urls) ;  
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
			$output = curl_exec($ch);
			//echo $output;die();
			
			return $output;	
			curl_close($ch);			
		}	
		
		
	}	
	
