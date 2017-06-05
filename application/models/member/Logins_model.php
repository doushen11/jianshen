<?php

	//登录类Model处理层

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Logins_model extends Cmains_model
	{
		
		public function __construct()
		{
			parent::__construct();
		}
		
		//登录验证方法
		public function subs($mobile,$captcha)
		{
			$mobile=htmlspecialchars(trim($mobile));
			$captcha=htmlspecialchars(trim($captcha));
			$querys=$this->db->query("select `id`,`times` from `".$this->dbprefix."captcha` where `mobile`='$mobile' and `captcha`='$captcha' and `act`='2' limit 1");
			if($querys->num_rows()>0){
				$results=$querys->row_array();

				require FCPATH."config/sys.inc.php";
				if(time()-$results["times"]>$_sys_inc["captcha_lives"]){
					json_array2("30000","验证码已经失效","");
				}else{
					$query=$this->db->query("select `id`,`login_time`,`login_ip`,`token_app` ,`state` from `dg_user` where `mobile`='$mobile' limit 1");
					if($query->num_rows()>0){
						$result=$query->row_array();
						if($result["state"]==2)
						{
							json_array2("30000","您已经被管理员拉黑，无法登录","");	
						}
						$_token_app=create_token("milk");
						$_salts=mt_rand(10000000,99999999);
						$_array=array(
							//"mobile"=>$mobile,
							"passwd"=>sha1(microtime()."recsons"),
							"salts"=>$_salts,
							"login_time"=>time(),
							"login_ip"=>ip2long(get_ip()),
							"last_time"=>$result["login_time"],
							"last_ip"=>$result["login_ip"],
							"token_app"=>$_token_app,
							"user_agent"=>user_agent(),
						);
						
						if(isset($_REQUEST["push_key"]) && trim($_REQUEST["push_key"])!="")
						{
							$_array["push_key"]=trim($_REQUEST["push_key"]);	
						}						

						/*if($result["token_app"]!='')
						{
							unset($_array["salts"]);
							unset($_array["passwd"]);
							unset($_array["token_app"]);
						}*/
						if($this->db->update("user",$_array,array("id"=>$result["id"]))){
							//$this->db->query("delete from `".$this->dbprefix."captcha` where `mobile`='$mobile' and `act`='2'");
							if(isset($_array["push_key"]) && $_array["push_key"]!=""){
								
								$this->db->query("update `dg_user` set `push_key`='' where `push_key`='".$_array["push_key"]."' and `id`!='".$result["id"]."' limit 1");
								
							}
							json_array2("10000","成功",$this->read_login($result["id"]));
						}else{
							error_show();
						}
					}else{
						json_array2("30000","手机号尚未注册","");
					}
				}
			}else{
				json_array2("30000","验证码不正确","");
			}				
		}
		
	}