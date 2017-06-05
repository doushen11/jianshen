<?php
	
	class Member extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();	
		}
		
		public function login()
		{
			//sos登录接口
			if(is_fulls("mobile") && is_fulls("passwd"))
			{
				$mobile=I("mobile");
				$passwd=I("passwd");
				$query=$this->db->query("select * from `dg_worker` where `mobile`='$mobile' limit 1");
				if($query->num_rows()>0)
				{
					$result=$query->row_array();
					if($result["state"]==2)
					{
						json_array2("30000","您的账号已被禁用，需要登录请联系系统管理员","");	
					}
					if(sha1(sha1($passwd).$result["salt"])==$result["passwd"])
					{
						$_array=array(
							"token"=>sha1(sha1(microtime())."recsons").md5(microtime().uniqid()),
							"login_ip"=>get_ip(),
							"login_time"=>time(),
						);
						//开始token多个客户端登录功能
						if($result["token"]!="")
						{
							unset($_array["token"]);	
						}
						//开始token多个客户端登录功能
						if($this->db->update("worker",$_array,array("id"=>$result["id"])))
						{
							$result=array_merge($result,$_array);
							unset($result["passwd"]);
							unset($result["salt"]);
							unset($result["doors"]);
							unset($result["doors_keys"]);
							unset($result["login_ip"]);
							unset($result["login_time"]);
							json_array2(10000,"登录成功",$result);	
						}
						else
						{
							json_array2("30000","网络连接失败","");		
						}
					}
					else
					{
						json_array2("30000","账号或者密码不正确哦","");		
					}
				}
				else
				{
					json_array2("30000","账号或者密码不正确哦","");	
				}
			}	
			else
			{
				error_show();	
			}
		}
			
	}