<?php
	
	//客户端开门请求973.71
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Opens extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function pushs()
		{
			
			isset($_GET["str"]) && trim($_GET["str"])!=""?$str=trim($_GET["str"]):$str="";
			
				
			//$file=fopen(FCPATH."/510.php","w");
			//fwrite($file,$str);			
			
			if(substr_count($str,"_")==4)
			{
				$arr=explode("_",$str);
								
				if(is_array($arr) && is_numeric($arr[0]) && sha1(trim($arr[0])."recsons500wolfes")==trim($arr[1]))
				{
					$uid=trim($arr[0]);
					
					$query=$this->db->query("select * from `dg_user` where `id`='".$uid."'");
					if($query->num_rows()>0)
					{
						$rs=$query->row_array();
						//进门账户余额低于100元开始推送
						
						$this->load->model("Opens_model","dos");
						$this->dos->openings($rs);
					}
				}
			}
		}
		
		//我的消费记录--进出门记录
		public function lists()
		{
			if(is_fulls("token"))
			{
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token);
				$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;
				$sql="select `start_time`,`end_time`,`money` from `dg_doors` where `uid`='".$rs["id"]."' and `act`='1' and `end_time`!='' and `money`>0 order by `id` desc";	
				$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
				$query=$this->db->query($sql);
				//$arr=array();$i=0;
				json_array("10000","成功",$query->result_array());
			}
			else
			{
				error_show();	
			}
		}
		
		public function index()
		{
			if(is_fulls("token")){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token);
				
				//开始判断用户是开门还是进门
				if($rs["doors"]==0)
				{
					//判断最后一条进门记录是否为正常数据
					$querys=$this->db->query("select `end_time` from `dg_doors` where `uid`='".$rs["id"]."' and `act`='1' order by `id` desc limit 1");
					if($querys->num_rows()>0)
					{
						$results=$querys->row_array();
						
						if($results["end_time"]=="")
						{
							json_array2(30000,"抱歉：您的上次进门记录有异常，系统拒绝了您的进门请求","1");
						}	
					}
					
					//$files=fopen(FCPATH."ssssss.php","a");
					//fwrite($files,json_encode($rs)."___<hr></hr>");
					
					//当前用户需要进门
					if($rs["balance"]<50)
					{
						json_array2(30000,"抱歉：您账户余额低于50元，系统拒绝了您的进门请求","1");	
					}
					else
					{
						//if($rs["balance"]<100)//进门成功后推送
						//{
							//大于50元小于100元，推送对应的出门信息要求
							//$this->load->model("Opens_model","dos");
							//$this->dos->openings($rs);
						//}	
						$hash=mt_rand(10000000,999999999);
						$str=$this->encrypt->encode($hash."_".$rs["id"]."_".microtime()."_".time()."_".sha1($hash."_recsons")."_1_1");
						//加密对应的用户信息，存储到对应的开门透传字段中
						if($this->db->query("update `dg_user` set `doors_keys`='$str' where `id`='".$rs["id"]."'"))
						{
							$rands=mt_rand(10000000,999999999);
							//开始出码
							json_array2(10000,"1",http_url()."client/doors/opens/captchas/".$rs["id"]."/".sha1($rs["id"]."{recson}shenyuxihuangxiaoyu1000{recson}".$rands)."/".$rands."/1");
						}
						else
						{
							json_array2(30000,"抱歉：网络连接失败，请稍后再试","1");	
						}
					}
				}
				elseif($rs["doors"]==1)
				{
					//当前用户需要出门
					$querys=$this->db->query("select `end_time`,`start_time` from `dg_doors` where `uid`='".$rs["id"]."' and `act`='1' and `end_time` is null order by `id` desc limit 1");
					//echo "select `end_time`,`start_time` from `dg_doors` where `uid`='".$rs["id"]."' and `act`='1' and `end_time`='' order by `id` desc limit 1";die();
					if($querys->num_rows()>0)
					{
						$results=$querys->row_array();	
						if($results["end_time"]=="")
						{
							$this->load->model("Opens_model","dos");
							$moneys=$this->dos->get_now_money($rs,$results["start_time"],time());
							//echo $moneys;die();
							if($moneys<=0)
							{
								json_array2(30000,"抱歉：您的账户余额不足，请您尽快联系健身房管理员","2");			
							}	
							else
							{
								$hash=mt_rand(10000000,999999999);
								$str=$this->encrypt->encode($hash."_".$rs["id"]."_".microtime()."_".time()."_".sha1($hash."_recsons")."_2_1");
								//加密对应的用户信息，存储到对应的开门透传字段中
								if($this->db->query("update `dg_user` set `doors_keys`='$str' where `id`='".$rs["id"]."'"))
								{
									$rands=mt_rand(10000000,999999999);
									//开始出码
									json_array2(10000,"2",http_url()."client/doors/opens/captchas/".$rs["id"]."/".sha1($rs["id"]."{recson}shenyuxihuangxiaoyu1000{recson}".$rands)."/".$rands."/1");
								}
								else
								{
									json_array2(30000,"抱歉：网络连接失败，请稍后再试","2");	
								}															
							}
						}
						else
						{
							json_array2(30000,"抱歉：您的上次出门状态出现异常，系统本次拒绝了您的出门请求","2");		
						}
					}	
					else
					{
						json_array2(30000,"抱歉：您的上次出门状态出现异常，系统本次拒绝了您的出门请求","2");	
					}
				}
				else
				{
					//系统错误
					json_array2(30000,"抱歉：系统读取您的身份信息错误，请稍后再试","2");	
				}
			}else{
				json_array2(30000,"抱歉：网络连接失败，请稍后再试","1");
			}				
		}
	
		public function captchas()
		{
			//进门二维码信息
			$uid=intval($this->uri->segment(5));
			$hash=trim($this->uri->segment(6));
			$hash_str=intval($this->uri->segment(7));
			$acts=intval($this->uri->segment(8));
			if(sha1($uid."{recson}shenyuxihuangxiaoyu1000{recson}".$hash_str)==$hash)
			{
				
				if($acts==1)
				{
					$query=$this->db->query("select `doors_keys` from `dg_user` where `id`='$uid'");
					if($query->num_rows()>0)
					{
						$result=$query->row_array();
						if($result["doors_keys"]!="")
						{
							//echo $result["doors_keys"];die();
							require FCPATH."public/phpqrcode.php";
							QRcode::png($result["doors_keys"],false,"H",100000);
						}	
						else
						{
							json_array2(30000,"抱歉：验证码信息已失效","");	
						}
					}	
					else
					{
						json_array2(30000,"抱歉：验证码信息已失效","");	
					}						
				}
				elseif($acts==2)
				{
					$query=$this->db->query("select `doors_keys` from `dg_teacher` where `id`='$uid'");
					if($query->num_rows()>0)
					{
						$result=$query->row_array();
						if($result["doors_keys"]!="")
						{
							//echo $result["doors_keys"];
							require FCPATH."public/phpqrcode.php";
							QRcode::png($result["doors_keys"],false,"H",100000);
						}	
						else
						{
							json_array2(30000,"抱歉：验证码信息已失效","");	
						}
					}	
					else
					{
						json_array2(30000,"抱歉：验证码信息已失效","");	
					}						
				}
				elseif($acts==3)
				{
					//soser的进门出门码
					$query=$this->db->query("select `doors_keys` from `dg_worker` where `id`='$uid'");
					if($query->num_rows()>0)
					{
						$result=$query->row_array();
						if($result["doors_keys"]!="")
						{
							//echo $result["doors_keys"];
							require FCPATH."public/phpqrcode.php";
							QRcode::png($result["doors_keys"],false,"H",100000);
						}	
						else
						{
							json_array2(30000,"抱歉：验证码信息已失效","");	
						}
					}	
					else
					{
						json_array2(30000,"抱歉：验证码信息已失效","");	
					}						
				}
			}
			else
			{
				json_array2(30000,"抱歉：验证码信息已失效","");
			}
		}
		
		public function validation()
		{
			//$str=json_encode($_REQUEST);
			//$files=fopen(FCPATH."/ceshi.php","a");
			//fwrite($files,$str);
			//进门二维码验证
			isset($_REQUEST["vgdecoderesult"]) && trim($_REQUEST["vgdecoderesult"])!=""?$codes=trim($_REQUEST["vgdecoderesult"]):$codes="";
			if($codes=="")
			{
				return false;
			}
			else
			{
				
				//$codes=str_replace(" ","+",$codes);
				//$fs=fopen(FCPATH."/z100.php","a");
				
				//fwrite($fs,$_REQUEST["vgdecoderesult"]."________".date("Y-m-d H:i:s")."<hr></hr>");
				
				require FCPATH."config/sys.inc.php";
				
				$memory=$this->db->query("select `time` from `dg_open` where `id`='1'");
				
				$db_do="insert";
				
				if($memory->num_rows()>0)
				{
					$db_do="update";
					$memorys=$memory->row_array();
					if(time()-$memorys["time"]<$_sys_inc["doors_time"])
					{
						//小于延时时间，拒绝操作
						return false;
					}
				}

				$str=str_replace(" ","+",trim($_REQUEST["vgdecoderesult"]));
				
			
				
				//echo $str;die();
				$result=$this->encrypt->decode($str);
				//echo $result;die();
				if(substr_count($result,"_")==6)
				{
					$arr=explode("_",$result);	
					
					if(trim($arr[4])==trim(sha1($arr[0]."_recsons")))
					{
						
						
						$uid=intval($arr[1]);//获取到用户ID
						
						if(trim($arr[5])==1)
						{
							
							//进门处理
							if(trim($arr[6])==1)
							{
								
								//普通用户进门
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_user` where `id`='$uid'");	
								if($query->num_rows()>0)
								{
									
									$results=$query->row_array();
									if($results["doors"]==0 && trim($results["doors_keys"])==trim($str))
									{
										
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_1_".time()."_1";												
											
										if($db_do=="insert")
										{
											$this->db->query("INSERT INTO `dg_open` (`id`, `uid`, `state`, `time`) VALUES ('1', '$uid', '1', '".time()."')");	
										}
										elseif($db_do=="update")
										{
											$this->db->query("update `dg_open` set `uid`='$uid', `state`='1', `time`='".time()."' where `id`='1'");	
										}
										$this->curl_doors($str);
										//echo $str;die();
										//echo 100;die();
										//结合mongo数据库处理
										
									}	
									
								}
								
								
							}
							elseif(trim($arr[6])==2)
							{
								//教练用户进门
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_teacher` where `id`='$uid'");	
								if($query->num_rows()>0)
								{
									$results=$query->row_array();
									if($results["doors"]==0 && trim($results["doors_keys"])==trim($str))
									{
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_1_".time()."_2";
										
										if($db_do=="insert")
										{
											$this->db->query("INSERT INTO `dg_open` (`id`, `uid`, `state`, `time`) VALUES ('1', '$uid', '1', '".time()."')");	
										}
										elseif($db_do=="update")
										{
											$this->db->query("update `dg_open` set `uid`='$uid', `state`='1', `time`='".time()."' where `id`='1'");	
										}
										$this->curl_doors($str);
										//echo $str;die();	
										//结合mongo数据库处理
									}	
								}									
							}
							elseif(trim($arr[6])==3)
							{
								//soser用户进门
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_worker` where `id`='$uid'");	
								if($query->num_rows()>0)
								{
									$results=$query->row_array();
									if($results["doors"]==0 && trim($results["doors_keys"])==trim($str))
									{
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_1_".time()."_3";
										
										if($db_do=="insert")
										{
											$this->db->query("INSERT INTO `dg_open` (`id`, `uid`, `state`, `time`) VALUES ('1', '$uid', '1', '".time()."')");	
										}
										elseif($db_do=="update")
										{
											$this->db->query("update `dg_open` set `uid`='$uid', `state`='1', `time`='".time()."' where `id`='1'");	
										}
										$this->curl_doors($str);
										//echo $str;die();	
										//结合mongo数据库处理
									}	
								}									
							}
							return false;
						}	
						
						return false;
					}
				}
				
				return false;
			}
		}
		
		//第二个门的扫码器验证地址
		public function validation_second()
		{
			//进门二维码验证
			isset($_REQUEST["vgdecoderesult"]) && trim($_REQUEST["vgdecoderesult"])!=""?$codes=trim($_REQUEST["vgdecoderesult"]):$codes="";
			if($codes=="")
			{
				//echo "nones";
				return false;
			}
			else
			{
				
				//$codes=str_replace(" ","+",$codes);
				//$fs=fopen(FCPATH."/z100.php","a");
				
				//fwrite($fs,$_REQUEST["vgdecoderesult"]."________".date("Y-m-d H:i:s")."<hr></hr>");
				
				require FCPATH."config/sys.inc.php";
				
				$memory=$this->db->query("select `time` from `dg_open` where `id`='1'");
				
				$db_do="insert";
				
				if($memory->num_rows()>0)
				{
					$db_do="update";
					$memorys=$memory->row_array();
					if(time()-$memorys["time"]<$_sys_inc["doors_time"])
					{
						//小于延时时间，拒绝操作
						return false;
					}
				}

				$str=str_replace(" ","+",trim($_REQUEST["vgdecoderesult"]));
				
			
				
				//echo $str;die();
				$result=$this->encrypt->decode($str);
				//echo $result;die();
				if(substr_count($result,"_")==6)
				{
					$arr=explode("_",$result);	
					
					if(trim($arr[4])==trim(sha1($arr[0]."_recsons")))
					{
						
						
						$uid=intval($arr[1]);//获取到用户ID
						
						if(trim($arr[5])==1)
						{
							
							//进门处理
							if(trim($arr[6])==1)
							{
								
								//普通用户进门
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_user` where `id`='$uid'");	
								if($query->num_rows()>0)
								{
									
									$results=$query->row_array();
									if($results["doors"]==0 && trim($results["doors_keys"])==trim($str))
									{
										
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_1_".time()."_1";												
											
										if($db_do=="insert")
										{
											$this->db->query("INSERT INTO `dg_open` (`id`, `uid`, `state`, `time`) VALUES ('1', '$uid', '1', '".time()."')");	
										}
										elseif($db_do=="update")
										{
											$this->db->query("update `dg_open` set `uid`='$uid', `state`='1', `time`='".time()."' where `id`='1'");	
										}
										$this->curl_second_doors($str);
										//echo $str;die();
										//echo 100;die();
										//结合mongo数据库处理
										
									}	
									
								}
								
								
							}
							elseif(trim($arr[6])==2)
							{
								//教练用户进门
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_teacher` where `id`='$uid'");	
								if($query->num_rows()>0)
								{
									$results=$query->row_array();
									if($results["doors"]==0 && trim($results["doors_keys"])==trim($str))
									{
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_1_".time()."_2";
										
										if($db_do=="insert")
										{
											$this->db->query("INSERT INTO `dg_open` (`id`, `uid`, `state`, `time`) VALUES ('1', '$uid', '1', '".time()."')");	
										}
										elseif($db_do=="update")
										{
											$this->db->query("update `dg_open` set `uid`='$uid', `state`='1', `time`='".time()."' where `id`='1'");	
										}
										$this->curl_second_doors($str);
										//echo $str;die();	
										//结合mongo数据库处理
									}	
								}									
							}
							elseif(trim($arr[6])==3)
							{
								//soser用户进门
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_worker` where `id`='$uid'");	
								if($query->num_rows()>0)
								{
									$results=$query->row_array();
									if($results["doors"]==0 && trim($results["doors_keys"])==trim($str))
									{
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_1_".time()."_3";
										
										if($db_do=="insert")
										{
											$this->db->query("INSERT INTO `dg_open` (`id`, `uid`, `state`, `time`) VALUES ('1', '$uid', '1', '".time()."')");	
										}
										elseif($db_do=="update")
										{
											$this->db->query("update `dg_open` set `uid`='$uid', `state`='1', `time`='".time()."' where `id`='1'");	
										}
										$this->curl_second_doors($str);
										//echo $str;die();	
										//结合mongo数据库处理
									}	
								}									
							}
							return false;
						}	
						
						return false;
					}
				}
				
				return false;
			}
		}
		
		public function validationouts()
		{
			//$str=json_encode($_REQUEST);
			//$files=fopen(FCPATH."/ceshi1.php","a");
			//fwrite($files,$str);			
			//出门二维码验证
			isset($_REQUEST["vgdecoderesult"]) && trim($_REQUEST["vgdecoderesult"])!=""?$codes=trim($_REQUEST["vgdecoderesult"]):$codes="";
			

			
			if($codes=="")
			{
				return false;
			}
			else
			{
				$str=str_replace(" ","+",trim($_REQUEST["vgdecoderesult"]));
				$result=$this->encrypt->decode($str);
				
				if(substr_count($result,"_")==6)
				{
					$arr=explode("_",$result);	
					if(trim($arr[4])==trim(sha1($arr[0]."_recsons")))
					{
						$uid=intval($arr[1]);//获取到用户ID
						
						if(trim($arr[5])==2)
						{
							if(trim($arr[6])==1)
							{
								
								//会员出门处理
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_user` where `id`='$uid'");	
								//print_r($query->row_array());
								
								if($query->num_rows()>0)
								{
									$results=$query->row_array();
									
									if($results["doors"]==1)
									{
										//echo 100;die();
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_2_".time()."_1";
										
										$this->curl_doors($str);
										
										//echo $str;die();
										//结合mongo数据库处理
									}	
								}
							}
							elseif(trim($arr[6])==2)
							{
								//教练出门处理
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_teacher` where `id`='$uid'");	
								if($query->num_rows()>0)
								{
									$results=$query->row_array();
									if($results["doors"]==1 && trim($results["doors_keys"])==trim($str))
									{
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_2_".time()."_2";
										$this->curl_doors($str);
										//echo $str;die();	
										//结合mongo数据库处理
									}	
								}	
							}
							elseif(trim($arr[6])==3)
							{
								//sos出门处理
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_worker` where `id`='$uid'");	
								if($query->num_rows()>0)
								{
									$results=$query->row_array();
									if($results["doors"]==1 && trim($results["doors_keys"])==trim($str))
									{
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_2_".time()."_3";
										$this->curl_doors($str);
										//echo $str;die();	
										//结合mongo数据库处理
									}	
								}	
							}
							return false;
						}	
						return false;
					}
				}
				return false;
			}
		}
		
		//第二个门的扫码验证地址
		public function validationouts_second()
		{
			//$str=json_encode($_REQUEST);
			//$files=fopen(FCPATH."/ceshi1.php","a");
			//fwrite($files,$str);			
			//出门二维码验证
			isset($_REQUEST["vgdecoderesult"]) && trim($_REQUEST["vgdecoderesult"])!=""?$codes=trim($_REQUEST["vgdecoderesult"]):$codes="";
			

			
			if($codes=="")
			{
				return false;
			}
			else
			{
				$str=str_replace(" ","+",trim($_REQUEST["vgdecoderesult"]));
				$result=$this->encrypt->decode($str);
				
				if(substr_count($result,"_")==6)
				{
					$arr=explode("_",$result);	
					if(trim($arr[4])==trim(sha1($arr[0]."_recsons")))
					{
						$uid=intval($arr[1]);//获取到用户ID
						
						if(trim($arr[5])==2)
						{
							if(trim($arr[6])==1)
							{
								
								//会员出门处理
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_user` where `id`='$uid'");	
								//print_r($query->row_array());
								
								if($query->num_rows()>0)
								{
									$results=$query->row_array();
									
									if($results["doors"]==1)
									{
										//echo 100;die();
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_2_".time()."_1";
										
										$this->curl_second_doors($str);
										
										//echo $str;die();
										//结合mongo数据库处理
									}	
								}
							}
							elseif(trim($arr[6])==2)
							{
								//教练出门处理
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_teacher` where `id`='$uid'");	
								if($query->num_rows()>0)
								{
									$results=$query->row_array();
									if($results["doors"]==1 && trim($results["doors_keys"])==trim($str))
									{
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_2_".time()."_2";
										$this->curl_second_doors($str);
										//echo $str;die();	
										//结合mongo数据库处理
									}	
								}	
							}
							elseif(trim($arr[6])==3)
							{
								//sos出门处理
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_worker` where `id`='$uid'");	
								if($query->num_rows()>0)
								{
									$results=$query->row_array();
									if($results["doors"]==1 && trim($results["doors_keys"])==trim($str))
									{
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_2_".time()."_3";
										$this->curl_second_doors($str);
										//echo $str;die();	
										//结合mongo数据库处理
									}	
								}	
							}
							return false;
						}	
						return false;
					}
				}
				return false;
			}
		}		
		
		public function validation_outs()
		{
			//$str=json_encode($_REQUEST);
			//$files=fopen(FCPATH."/ceshi1.php","a");
			//fwrite($files,$str);			
			//出门二维码验证
			isset($_REQUEST["vgdecoderesult"]) && trim($_REQUEST["vgdecoderesult"])!=""?$codes=trim($_REQUEST["vgdecoderesult"]):$codes="";
			

			
			if($codes=="")
			{
				return false;
			}
			else
			{
				$str=str_replace(" ","+",trim($_REQUEST["vgdecoderesult"]));
				$result=$this->encrypt->decode($str);
				
				if(substr_count($result,"_")==6)
				{
					$arr=explode("_",$result);	
					if(trim($arr[4])==trim(sha1($arr[0]."_recsons")))
					{
						$uid=intval($arr[1]);//获取到用户ID
						
						if(trim($arr[5])==2)
						{
							if(trim($arr[6])==1)
							{
								
								//会员出门处理
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_user` where `id`='$uid'");	
								//print_r($query->row_array());
								
								if($query->num_rows()>0)
								{
									$results=$query->row_array();
									
									if($results["doors"]==1 && trim($results["doors_keys"])==trim($str))
									{
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_2_".time()."_1";
										
										$this->curl_doors($str);
										
										//echo $str;die();
										//结合mongo数据库处理
									}	
								}
							}
							elseif(trim($arr[6])==2)
							{
								//教练出门处理
								$query=$this->db->query("select `id`,`doors`,`doors_keys` from `dg_teacher` where `id`='$uid'");	
								if($query->num_rows()>0)
								{
									$results=$query->row_array();
									if($results["doors"]==1 && trim($results["doors_keys"])==trim($str))
									{
										//数据相同，并且目前处理门外状态，ok开始通知开门啦
										//结合mongo数据库处理
										$str=$results["id"]."_".sha1(trim($results["id"])."recsons500wolfes")."_2_".time()."_2";
										$this->curl_doors($str);
										//echo $str;die();	
										//结合mongo数据库处理
									}	
								}	
							}
							return false;
						}	
						return false;
					}
				}
				return false;
			}
		}
		
		public function outs_do()
		{
			
			//出门计费处理，bingo
			if(isset($_GET["uid"]) && is_numeric($_GET["uid"]) && isset($_GET["ends"]) && trim($_GET["ends"])!='')
			{
				
				$uid=intval($_GET["uid"]);
				$end=time();	
				$sql="select `id`,`end_time`,`start_time` from `dg_doors` where `uid`='$uid' and `act`='1' order by `id` desc limit 1";
				$query=$this->db->query($sql);
				if($query->num_rows()>0)
				{
					$result=$query->row_array();
					if($result["end_time"]=="" && !is_numeric($result["end_time"]))
					{
						
						$this->db->trans_strict(false);
						$this->db->trans_begin();	
						
						$query1=$this->db->query("select * from `dg_user` where `id`='$uid'");
						
						if($query1->num_rows()<=0)
						{
							$this->db->trans_rollback();
							echo "false";die();					
						}
						
						$rs=$query1->row_array();
						
						//开始做事务信息处理
						$this->load->model("Opens_model","dos");
						
						//echo date("Y-m-d H:i:s",$end);die();
						
						$moneys=$this->dos->get_now_money($rs,$result["start_time"],$end);	
						
						//echo $moneys;die();
						
						$moneys1=$moneys;
						
						if($moneys<=0)
						{
							$this->db->trans_rollback();
							echo "false";die();	
						}			
						else
						{
							//开始扣费处理
							
							$querys=$this->db->query("select `money_remaining`,`id` from `dg_pay_order` where `uid`='$uid' and `uid_index`='".right_index($uid)."' and `money_remaining`>0 order by `id` asc");
							
							foreach($querys->result_array() as $arrays)
							{
								if($moneys>0)
								{
									if($arrays["money_remaining"]>$moneys)
									{
										$ye=$arrays["money_remaining"]-$moneys;
										$this->db->query("update `dg_pay_order` set `money_remaining`='$ye' where `id`='".$arrays["id"]."'");
										$moneys=0;
									}	
									else
									{
										$moneys=$moneys-$arrays["money_remaining"];
										$this->db->query("update `dg_pay_order` set `money_remaining`='0' where `id`='".$arrays["id"]."'");
									}
								}
							}
							
							if($moneys>0)
							{
								$this->db->trans_rollback();
								echo "false";die();	
							}
							//更新账户余额
							$this->db->query("update `dg_user` set `balance`=`balance`-'$moneys1',`doors`='0',`doors_keys`='' where `id`='$uid'");
							//更新账户余额
							
							//更新出门记录
							$_array=array(
								"end_time"=>$end,
								"money"=>$moneys1,
							);	
							$this->db->update("doors",$_array,array("id"=>$result["id"]));
							//更新这条出门记录
							
							//减去实时人数信息
							mysql_query("update `dg_config` set `people`=`people`-'1' where `id`='1'");
							//查询实时人数，如果为0，断电操作
							
							
							if($this->db->trans_status()==true){
								//$this->db->trans_commit();
								$this->db->trans_commit();
								$this->close_l();
								echo "success";die();
							}else{
								$this->db->trans_rollback();
								echo "false";die();
							}						
						}	
					}
					else
					{
						echo "false";die();	
					}
				}
				else
				{
					echo "false";die();
				}
			}	
			echo "false";die();
		}
		
		private function close_l()
		{
			//$f=fopen(FCPATH."1111.php","w");
			//fwrite($f,date("Y-m-d H:i:s"));
			$query=$this->db->query("select * from `dg_config` where `id`='1'");
			$result=$query->row_array();
			if($result["people"]==0)
			{
				//健身房没人了关电
				$urls=base_url()."apis/l_close.php?keys=1282d94a4f461110b676f711b221d86a76b8a8008982064b6bc94d08ec2b58fff9023a3699ba8e03adebe8a12359bb772ee639a5c418c908";		
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
		}		
		
		public function open_l()
		{
			//开电判断操作
			$query=$this->db->query("select * from `dg_config` where `id`='1'");
			$result=$query->row_array();
			if($result["people"]==1)
			{
				//进去了一个人，开电
				$urls=base_url()."apis/l_opens.php?keys=1282d94a4f461110b676f711b221d86a76b8a8008982064b6bc94d08ec2b58fff9023a3699ba8e03adebe8a12359bb772ee639a5c418c908";		
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
		}
		
		
		private function curl_doors($str)
		{
			//$urls=base_url()."websevice/https.php?str=".$str;	
			
			$keys="1282d94a4f461110b676f711b221d86a76b8a8008982064b6bc94d08ec2b58fff9023a3699ba8e03adebe8a12359bb772ee639a5c418c908";
			
			$urls=base_url()."apis/opens.php?codes=".$str."&keys=".$keys;		

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
		
		private function curl_second_doors($str)
		{
			//$urls=base_url()."websevice/https.php?str=".$str;	
			
			$keys="1282d94a4f461110b676f711b221d86a76b8a8008982064b6bc94d08ec2b58fff9023a3699ba8e03adebe8a12359bb772ee639a5c418c908";
			
			$urls=base_url()."apis/opens_tow.php?codes=".$str."&keys=".$keys;		

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
		
	}
	


