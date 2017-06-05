<?php
	
	//教练墙控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Home extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("room/Home_model","dos");
		}
		
		//读取健身房大厅
		public function houses()
		{
			echo $this->dos->houses();	
		}
		
		//健身房相关信息显示
		public function index()
		{
			echo $this->dos->index();
		}
		
		//健身房详情信息显示
		public function rooms()
		{
			echo $this->dos->rooms();
		}
		
		//健身器材分类信息
		public function acts()
		{
			echo $this->dos->acts();		
		}
		
		//健身器材列表
		public function machines()
		{
			if(is_fulls("id",1)){
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				echo $this->dos->machines($id);
			}else{
				error_show();	
			}	
		}
		
		//健身器材详情
		public function machine()
		{
			if(is_fulls("id",1)){
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				echo $this->dos->machine($id);
			}else{
				error_show();	
			}				
		}
		
	}