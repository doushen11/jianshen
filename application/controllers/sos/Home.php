<?php
	
	class Home extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();	
		}
		
		//验证token的方法
		private function check_token($token)
		{
			$query=$this->db->query("select * from `dg_worker` where `token`='$token' limit 1");
			if($query->num_rows()>0)
			{
				return $query->row_array();	
			}
			else
			{
				json_array2(20000,"抱歉：您的登录状态已失效，请重新登录","");	
			}
		}
		
		//查看当前sos的进出门记录接口
		public function history()
		{
			if(is_fulls("token"))
			{
				$token=I("token");
				$rs=$this->check_token($token);
				$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
				$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;
				$sql="select `start_time`,`end_time` from `dg_doors` where `act`='3' and `uid`='".$rs["id"]."' order by `id` desc";
				$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
				$query=$this->db->query($sql);	
				json_array2(10000,"成功",$query->result_array());	
			}
			else
			{
				error_show();	
			}
		}
		
		//sos操作
		public function sossub()
		{
			if(is_fulls("token"))
			{
				$token=I("token");
				$rs=$this->check_token($token);
				$querys=$this->db->query("select * from `dg_open` where `id`='2'");
				$do="insert";
				if($querys->num_rows()>0)
				{
					$do="update";
					$results=$querys->row_array();
					if(time()-$results["time"]<=5)
					{
						json_array2(30000,"抱歉：sos功能使用太频繁，请稍后再试","");
					}
				}
				
				$_array=array(
					"id"=>2,
					"uid"=>$rs["id"],
					"state"=>1,
					"time"=>time(),
				);
				//做最后节点操作记录
				if($do=="insert")
				{
					$this->db->insert("open",$_array);	
				}
				else
				{
					$this->db->update("open",$_array,array("id"=>2));	
				}
				//做最后节点操作记录
				$_array1=array(
					"uid"=>$rs["id"],
					"time"=>time(),
				);
				$this->db->insert("worker_sos",$_array1);//插入一条使用记录
				$this->sos_curl("recson");
				json_array2(10000,"成功","");
			}
			else
			{
				error_show();	
			}
		}
		
		//开门啦
		private function sos_curl($str)
		{
			$keys="1282d94a4f461110b676f711b221d86a76b8a8008982064b6bc94d08ec2b58fff9023a3699ba8e03adebe8a12359bb772ee639a5c418c908";
			$urls=base_url()."apis/opens.php?codes=".$str."&keys=".$keys;						
			$ch = curl_init($urls) ;  
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true) ; // 获取数据返回  
			curl_setopt($ch,CURLOPT_BINARYTRANSFER,true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
			$output = curl_exec($ch);	
			curl_close($ch);			
			return $output;	
						
		}
		
		//出码控制器
		public function getcaptcha()
		{
			if(is_fulls("token"))
			{
				$token=I("token");
				$rs=$this->check_token($token);	
				//开始判断用户是开门还是进门
				if($rs["doors"]==0)
				{
					$querys=$this->db->query("select `end_time` from `dg_doors` where `uid`='".$rs["id"]."' and `act`='3' order by `id` desc limit 1");
					if($querys->num_rows()>0)
					{
						$results=$querys->row_array();
						if($results["end_time"]=="")
						{
							json_array2(30000,"抱歉：您的上次进门记录有异常，系统拒绝了您的进门请求","");
						}	
					}	
					$hash=mt_rand(10000000,999999999);
					$str=$this->encrypt->encode($hash."_".$rs["id"]."_".microtime()."_".time()."_".sha1($hash."_recsons")."_1_3");
					if($this->db->query("update `dg_worker` set `doors_keys`='$str' where `id`='".$rs["id"]."'"))
					{
						$rands=mt_rand(10000000,999999999);
						//开始出码
						json_array2(10000,"1",http_url()."client/doors/opens/captchas/".$rs["id"]."/".sha1($rs["id"]."{recson}shenyuxihuangxiaoyu1000{recson}".$rands)."/".$rands."/3");
					}
					else
					{
						json_array2(30000,"抱歉：网络连接失败，请稍后再试","");	
					}
				}
				elseif($rs["doors"]==1)
				{
					$querys=$this->db->query("select `end_time`,`start_time` from `dg_doors` where `uid`='".$rs["id"]."' and `act`='3' order by `id` desc limit 1");	
					if($querys->num_rows()>0)
					{
						$results=$querys->row_array();	
						if($results["end_time"]=="")
						{
							
							$hash=mt_rand(10000000,999999999);
							$str=$this->encrypt->encode($hash."_".$rs["id"]."_".microtime()."_".time()."_".sha1($hash."_recsons")."_2_3");
							//加密对应的用户信息，存储到对应的开门透传字段中
							if($this->db->query("update `dg_worker` set `doors_keys`='$str' where `id`='".$rs["id"]."'"))
							{
								$rands=mt_rand(10000000,999999999);
								//开始出码
								json_array2(10000,"2",http_url()."client/doors/opens/captchas/".$rs["id"]."/".sha1($rs["id"]."{recson}shenyuxihuangxiaoyu1000{recson}".$rands)."/".$rands."/3");
								
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
					json_array2(30000,"抱歉：出门进门信息读取失败，请稍后再试","");		
				}
			}
			else
			{
				error_show();	
			}
		}
			
	}