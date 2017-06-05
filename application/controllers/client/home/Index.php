<?php
	
	//首页进门出门相关控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Index extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("client/Index_model","dos");
		}
		
		//客户端首页接口
		public function index()
		{
			echo $this->dos->index();
		}
		
		//客户端首页显示经度纬度接口
		public function address()
		{
			echo $this->dos->address();	
		}
		
	}