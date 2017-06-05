<?php
	
	//教练墙控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Home extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("yteach/Home_model","dos");
		}
		
		//所有的教练，包含操课的和私课的
		public function index()
		{
			echo $this->dos->index();
		}
		
	}