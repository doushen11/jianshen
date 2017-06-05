<?php
	
	//我的分享控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Codes extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("my/Codes_model","dos");
		}
		
		//分享二维码加载Api
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