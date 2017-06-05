<?php

	//登录类Model处理层

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Amains_model.php";
	
	class Logins_model extends Amains_model
	{
		
		public function __construct()
		{
			parent::__construct();
		}
		
		//登录验证方法
		public function subs($mobile,$passwd)
		{
			$mobile=htmlspecialchars(trim($mobile));
			$passwd=htmlspecialchars(trim($passwd));
			$querys=$this->db->query("select * from `".$this->dbprefix."teacher` where `mobile`='$mobile' and `passwd`='".sha1($passwd)."' limit 1");
			if($querys->num_rows()>0){
				$results=$querys->row_array();
				if($results["state"]==2)
				{
					json_array2("30000","您已经被管理员拉黑，无法登录","");	
				}
				//$_token_app=$results["token_app"];
				//if($_token_app=="")
				//{
					$_token_app=create_token("leesee");
				//}
				$_array=array(
					"login_time"=>time(),
					"login_ip"=>ip2long(get_ip()),
					"last_time"=>$results["login_time"],
					"last_ip"=>$results["login_ip"],
					"token_app"=>$_token_app,
					"user_agent"=>user_agent(),
				);
				if(isset($_REQUEST["push_key"]) && trim($_REQUEST["push_key"])!="")
				{
					$_array["push_key"]=trim($_REQUEST["push_key"]);	
				}
				if($this->db->update("teacher",$_array,array("id"=>$results["id"]))){
					
					if(isset($_array["push_key"]) && $_array["push_key"]!="")
					{
						$this->db->query("update `dg_teacher` set `push_key`='' where `push_key`='".$_array["push_key"]."' and `id`!='".$results["id"]."' limit 1");
					}
					
					json_array2("10000","成功",$this->read_login($results["id"]));
				}else{
					error_show();
				}
				
			}else{
				json_array2("30000","登录账号或密码不正确","");
			}				
		}
		
		//退出登录
		public function outs($token)
		{
			if($this->db->query("update `dg_teacher` set `token_app`='' where `token_app`='$token' limit 1"))
			{
				json_array2("10000","成功","");	
			}	
			else
			{
				json_array2("30000","网络连接失败","");	
			}
		}
		
	}