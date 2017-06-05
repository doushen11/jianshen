<?php
	
	//会员登录注册控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Logins extends CI_Controller
	{
		
		public function __construct()
		{
			parent::__construct();	
		}
		
		//登录验证
		public function subs()
		{
			if(is_fulls("mobile") && preg_match("/1[34578]{1}\d{9}$/",$_REQUEST["mobile"],$m) && is_fulls("captcha") && strlen(trim($_REQUEST["captcha"]))==6){
				$this->load->model("member/Logins_model","apps");
				$this->apps->subs($_REQUEST["mobile"],$_REQUEST["captcha"]);
			}else{
				error_show();
			}				
		}
		
			
	}