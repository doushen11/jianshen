<?php

	//验证码类Model处理层

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Captchas_model extends Cmains_model
	{
		
		public function __construct()
		{
			parent::__construct();
		}
		
		//发送注册验证吗
		public function register($mobile)
		{
			$mobile=htmlspecialchars(trim($mobile));
			if(!$this->mobile($mobile)){
				json_array2("30000","当前手机号码已经被注册过","false");	
			}else{
				require FCPATH."config/sys.inc.php";
				if(isset($_SESSION["captcha_times"]) && time()-$this->session->captcha_times<$_sys_inc["captcha_time"]){
					json_array2("30000","抱歉，验证码发的也忒快了些吧，两次短信发送间隔不能小于".$_sys_inc["captcha_time"]."秒","");		
				}
				$querys=$this->db->query("select `id`,`times` from `".$this->dbprefix."captcha` where `mobile`='$mobile' and `act`='1' limit 1");
				$_captcha=rand(100000,999999);
				if($querys->num_rows()>0){
					//updates
					$results=$querys->row_array();
					if(time()-$results["times"]<$_sys_inc["captcha_time"]){
						json_array2("30000","抱歉，验证码发的也忒快了些吧，两次短信发送间隔不能小于".$_sys_inc["captcha_time"]."秒","");
					}
					$_array=array(
						"captcha"=>$_captcha,
						"times"=>time(),
					);
					if($this->db->update("captcha",$_array,array("id"=>$results["id"]))){
						$this->session->set_userdata(array("captcha_times"=>time()));
						msn($mobile,str_replace("{code}",$_captcha,$_sys_inc["code_reg"]));
						//send_captcha($mobile,$_captcha,$_sys_inc["captcha_lives"],"37554");
						json_array2("10000","验证码发送成功",$_captcha);	
					}else{
						error_show();	
					}
				}else{
					//insert
					$_array=array(
						"mobile"=>$mobile,
						"captcha"=>$_captcha,
						"act"=>1,
						"times"=>time(),
					);
					if($this->db->insert("captcha",$_array)){
						$this->session->set_userdata(array("captcha_times"=>time()));
						//send_captcha($mobile,$_captcha,$_sys_inc["captcha_lives"],"37554");
						msn($mobile,str_replace("{code}",$_captcha,$_sys_inc["code_reg"]));
						json_array2("10000","验证码发送成功",$_captcha);	
					}else{
						error_show();	
					}
				}
			}
		}
		
		//发送登录验证吗
		public function login($mobile)
		{
			$mobile=htmlspecialchars(trim($mobile));
			if($this->mobile($mobile)){
				json_array2("30000","当前手机号码尚未被注册过","false");	
			}else{
				
				$query=$this->db->query("select `state` from `dg_user` where `mobile`='$mobile' limit 1");
				$result=$query->row_array();
				if($result["state"]==2)
				{
					json_array2("30000","您已经被管理员拉黑，无法登录","");	
				}
				
				require FCPATH."config/sys.inc.php";
				if(isset($_SESSION["captcha_times"]) && time()-$this->session->captcha_times<$_sys_inc["captcha_time"]){
					json_array2("30000","抱歉，验证码发的也忒快了些吧，两次短信发送间隔不能小于".$_sys_inc["captcha_time"]."秒","");		
				}
				$querys=$this->db->query("select `id`,`times` from `".$this->dbprefix."captcha` where `mobile`='$mobile' and `act`='2' limit 1");
				$_captcha=rand(100000,999999);
				if($querys->num_rows()>0){
					//updates
					$results=$querys->row_array();
					if(time()-$results["times"]<$_sys_inc["captcha_time"]){
						json_array2("30000","抱歉，验证码发的也忒快了些吧，两次短信发送间隔不能小于".$_sys_inc["captcha_time"]."秒","");
					}
					$_array=array(
						"captcha"=>$_captcha,
						"times"=>time(),
					);
					if($this->db->update("captcha",$_array,array("id"=>$results["id"]))){
						$this->session->set_userdata(array("captcha_times"=>time()));
						//send_captcha($mobile,$_captcha,$_sys_inc["captcha_lives"],"37554");
						msn($mobile,str_replace("{code}",$_captcha,$_sys_inc["code_login"]));
						json_array2("10000","验证码发送成功",$_captcha);
					}else{
						error_show();
					}
				}else{
					//insert
					$_array=array(
						"mobile"=>$mobile,
						"captcha"=>$_captcha,
						"act"=>2,
						"times"=>time(),
					);
					if($this->db->insert("captcha",$_array)){
						$this->session->set_userdata(array("captcha_times"=>time()));
						//send_captcha($mobile,$_captcha,$_sys_inc["captcha_lives"],"37554");
						msn($mobile,str_replace("{code}",$_captcha,$_sys_inc["code_login"]));
						json_array2("10000","验证码发送成功",$_captcha);	
					}else{
						error_show();	
					}
				}
			}
		}
		
	}