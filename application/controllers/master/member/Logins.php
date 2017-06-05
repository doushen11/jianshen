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
			if(is_fulls("mobile") && preg_match("/1[34578]{1}\d{9}$/",$_REQUEST["mobile"],$m) && is_fulls("passwd")){
				$this->load->model("master/member/Logins_model","apps");
				$this->apps->subs($_REQUEST["mobile"],$_REQUEST["passwd"]);
			}else{
				error_show();
			}
		}
		
		//退出登录
		public function outs()
		{
			if(is_fulls("token")){
				$this->load->model("master/member/Logins_model","apps");
				$this->apps->outs($_REQUEST["token"]);
			}else{
				error_show();
			}	
		}
		
			
	}