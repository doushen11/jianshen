<?php
	
	//我要约课相关控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Home extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("yclass/Home_model","dos");
		}
		
		//所有操课列表
		public function index()
		{
			echo $this->dos->index();
		}
		
		//对应私课教练信息
		public function teachs()
		{
			echo $this->dos->teachs();	
		}
		
	}