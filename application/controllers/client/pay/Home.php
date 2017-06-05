<?php

	//充值相关控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Home extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("pay/Home_model","dos");
		}
		
		//我当前享受的折扣信息以及高低峰时间
		public function index()
		{
			if(is_fulls("token")){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token);
				echo $this->dos->index($rs);
			}else{
				error_show();	
			}				
		}
		
	}