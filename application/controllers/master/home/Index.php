<?php
	
	//首页相关控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Index extends CI_Controller
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("master/home/Index_model","dos");
		}
		
		//教练端首页接口
		public function index()
		{
			echo $this->dos->index();
		}
		
		//教练端公告
		public function notice()
		{
			echo $this->dos->notice();
		}
		
	}