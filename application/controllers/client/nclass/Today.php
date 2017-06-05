<?php
	
	//当日课程控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Today extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();	
			$this->load->model("nclass/Today_model","apps");
		}
		
		//已经成立的操课
		public function indexs()
		{
			echo $this->apps->indexs();	
		}
		
		//当日私教信息
		public function homes()
		{
			echo $this->apps->homes();			
		}
	}