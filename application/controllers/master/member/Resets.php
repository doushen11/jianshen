<?php
	
	//教练端找回密码控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Resets extends CI_Controller
	{
		
		private $dbprefix;
		
		public function __construct()
		{
			parent::__construct();	
			$this->dbprefix="dg_";
		}
		
		public function captcha()
		{
			if(is_fulls("mobile") && preg_match("/1[34578]{1}\d{9}$/",$_REQUEST["mobile"],$m))
			{
				$mobile=trim($_REQUEST["mobile"]);
				$query=$this->db->query("select `id` from `dg_teacher` where `mobile`='$mobile' limit 1");
				if($query->num_rows()>0)
				{
					require FCPATH."config/sys.inc.php";
					if(isset($_SESSION["captcha_times"]) && time()-$this->session->captcha_times<$_sys_inc["captcha_time"]){
						json_array2("30000","抱歉，验证码发的也忒快了些吧，两次短信发送间隔不能小于".$_sys_inc["captcha_time"]."秒","");		
					}
					$querys=$this->db->query("select `id`,`times` from `".$this->dbprefix."captcha` where `mobile`='$mobile' and `act`='3' limit 1");
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
							msn($mobile,str_replace("{code}",$_captcha,$_sys_inc["code_reset"]));
							json_array2("10000","验证码发送成功","");	
						}else{
							error_show();	
						}
					}else{
						//insert
						$_array=array(
							"mobile"=>$mobile,
							"captcha"=>$_captcha,
							"act"=>3,
							"times"=>time(),
						);
						if($this->db->insert("captcha",$_array)){
							$this->session->set_userdata(array("captcha_times"=>time()));													//send_captcha($mobile,$_captcha,$_sys_inc["captcha_lives"],"37554");
							msn($mobile,str_replace("{code}",$_captcha,$_sys_inc["code_reset"]));
							json_array2("10000","验证码发送成功","");	
						}else{
							error_show();	
						}
					}						
				}				
				else
				{
					json_array2(30000,"抱歉：无法获取到教练信息，请稍后再试！","");	
				}
			}
			else
			{
				error_show();	
			}				
		}
		
		public function subs()
		{
			//更新教师端密码
			if(is_fulls("mobile") && preg_match("/1[34578]{1}\d{9}$/",$_REQUEST["mobile"],$m) && is_fulls("captcha") && is_fulls("passwd") && strlen(trim($_REQUEST["passwd"]))>=6 && strlen(trim($_REQUEST["passwd"]))<=16)
			{
				$mobile=trim($_REQUEST["mobile"]);
				$passwd=trim($_REQUEST["passwd"]);
				$captcha=trim($_REQUEST["captcha"]);
				$querys=$this->db->query("select `id`,`times` from `".$this->dbprefix."captcha` where `mobile`='$mobile' and `captcha`='$captcha' and `act`='3' limit 1");
				if($querys->num_rows()>0)
				{		
					$results=$querys->row_array();
					require FCPATH."config/sys.inc.php";
					if(time()-$results["times"]>$_sys_inc["captcha_lives"]){
						json_array2("30000","验证码已经失效","");
					}else{
						$query=$this->db->query("select `id` from `dg_teacher` where `mobile`='$mobile' limit 1");
						if($query->num_rows()>0){
							$result=$query->row_array();
							$_array=array(
								"passwd"=>sha1($passwd),
							);
							if($this->db->update("teacher",$_array,array("id"=>$result["id"])))
							{
								json_array2("10000","密码已经修改成功","");	
							}
							else
							{
								json_array2("30000","网络慢，请稍后再试","");	
							}
						}else{
							json_array2("30000","手机号尚未注册","");
						}
					}
				}
				else
				{
					json_array2("30000","验证码不正确","");	
				}
			}
			else
			{
				error_show();	
			}
		}

		
	}