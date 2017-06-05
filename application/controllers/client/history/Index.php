<?php
	
	//我的历史记录
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Index extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("my/History_model","dos");
		}
		
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