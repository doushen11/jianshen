<?php
	
	//新晋教练控制器----操课
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Index extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("nteach/Index_model","dos");
		}
		
		
		//操课教练详情上半部分
		public function item()
		{
			if(is_fulls("id",1))
			{
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				echo $this->dos->item($id);
			}
			else
			{
				error_show();	
			}				
		}
		
		//操课教练安排详情
		public function plan()
		{
			if(is_fulls("id",1))
			{
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				echo $this->dos->plan($id);
			}
			else
			{
				error_show();	
			}				
		}
		
		//预约详情信息
		public function subscribe()
		{
			if(is_fulls("id",1))
			{
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				echo $this->dos->subscribe($id);
			}
			else
			{
				error_show();
			}	
		}
		
	}