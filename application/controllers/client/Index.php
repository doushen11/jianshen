<?php
	
	//我要约课相关控制器
	
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
		
		//客户端升级接口
		public function downloads()
		{
			require FCPATH."config/soft.inc.php";
			json_array2(10000,"成功",$soft_inc);
		}
		
	}