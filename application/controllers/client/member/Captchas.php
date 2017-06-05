<?php
	
	//验证码发送控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Captchas extends CI_Controller
	{
		
		public function __construct()
		{
			parent::__construct();
		}
		
		//注册验证号码发送
		public function register()
		{
			if(is_fulls("mobile") && preg_match("/1[34578]{1}\d{9}$/",$_REQUEST["mobile"],$m))
			{
				$this->load->model("member/Captchas_model","apps");
				$this->apps->register($_REQUEST["mobile"]);					
			}
			else
			{
				error_show();	
			}		
		}
		
		//登录验证号码发送
		public function login()
		{
			if(is_fulls("mobile") && preg_match("/1[34578]{1}\d{9}$/",$_REQUEST["mobile"],$m))
			{
				$this->load->model("member/Captchas_model","apps");
				$this->apps->login($_REQUEST["mobile"]);					
			}
			else
			{
				error_show();	
			}				
		}
			
	}