<?php
	
	//会员注册控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Registers extends CI_Controller
	{
		
		public function __construct()
		{
			parent::__construct();	
		}
		
		//会员注册提交操作
		public function subs()
		{
			if(is_fulls("mobile") && preg_match("/1[34578]{1}\d{9}$/",$_REQUEST["mobile"],$m) && is_fulls("captcha") && strlen(trim($_REQUEST["captcha"]))==6){
				$this->load->model("member/Registers_model","apps");
				$this->apps->subs($_REQUEST["mobile"],$_REQUEST["captcha"]);
			}else{
				error_show();
			}					
		}
		
		//会员注册协议
		public function texts()
		{
			$query=$this->db->query("select * from `dg_articles` where `id`='5'");
			$data["result"]=$query->row_array();
			$this->load->view("html5/reg_item.php",$data);					
		}
			
	}