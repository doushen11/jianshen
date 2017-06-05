<?php
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Home extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
			$this->load->model("admin/Admin_model","admins");
		}
		
		public function indexs()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/indexs.php",$data);	
		}
		
	}