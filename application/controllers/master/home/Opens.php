<?php
	
	//教练端--进门出门控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/master/Alls.php";
	
	class Opens extends Alls
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("master/my/Index_model","dos");
		}
		
		
		//出码控制器
		public function indexs()
		{
			if(is_fulls("token"))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
//开始判断用户是开门还是进门
				if($rs["doors"]==0)
				{
					//判断最后一条进门记录是否为正常数据
					$querys=$this->db->query("select `end_time` from `dg_doors` where `uid`='".$rs["id"]."' and `act`='2' order by `id` desc limit 1");
					if($querys->num_rows()>0)
					{
						$results=$querys->row_array();
						
						if($results["end_time"]=="")
						{
							json_array2(30000,"抱歉：您的上次进门记录有异常，系统拒绝了您的进门请求","");
						}	
					}

					$hash=mt_rand(10000000,999999999);
					$str=$this->encrypt->encode($hash."_".$rs["id"]."_".microtime()."_".time()."_".sha1($hash."_recsons")."_1_2");
					//加密对应的用户信息，存储到对应的开门透传字段中
					if($this->db->query("update `dg_teacher` set `doors_keys`='$str' where `id`='".$rs["id"]."'"))
					{
						$rands=mt_rand(10000000,999999999);
						//开始出码
						json_array2(10000,"1",http_url()."client/doors/opens/captchas/".$rs["id"]."/".sha1($rs["id"]."{recson}shenyuxihuangxiaoyu1000{recson}".$rands)."/".$rands."/2");
					}
					else
					{
						json_array2(30000,"抱歉：网络连接失败，请稍后再试","");	
					}
					
				}
				elseif($rs["doors"]==1)
				{
					//当前用户需要出门
					$querys=$this->db->query("select `end_time`,`start_time` from `dg_doors` where `uid`='".$rs["id"]."' and `act`='2' order by `id` desc limit 1");
					if($querys->num_rows()>0)
					{
						$results=$querys->row_array();	
						if($results["end_time"]=="")
						{
							
							$hash=mt_rand(10000000,999999999);
							$str=$this->encrypt->encode($hash."_".$rs["id"]."_".microtime()."_".time()."_".sha1($hash."_recsons")."_2_2");
							//加密对应的用户信息，存储到对应的开门透传字段中
							if($this->db->query("update `dg_teacher` set `doors_keys`='$str' where `id`='".$rs["id"]."'"))
							{
								$rands=mt_rand(10000000,999999999);
								//开始出码
								json_array2(10000,"2",http_url()."client/doors/opens/captchas/".$rs["id"]."/".sha1($rs["id"]."{recson}shenyuxihuangxiaoyu1000{recson}".$rands)."/".$rands."/2");
								
							}
							else
							{
								json_array2(30000,"抱歉：网络连接失败，请稍后再试","");	
							}															

						}
						else
						{
							json_array2(30000,"抱歉：您的上次出门状态出现异常，系统本次拒绝了您的出门请求","");		
						}
					}	
					else
					{
						json_array2(30000,"抱歉：您的上次出门状态出现异常，系统本次拒绝了您的出门请求","");	
					}
				}
				else
				{
					//系统错误
					json_array2(30000,"抱歉：系统读取您的身份信息错误，请稍后再试","");	
				}				
			}	
			else
			{
				error_show();	
			}				
		}
		
	}