<?php

	//H5页面对应的控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Notice extends CI_Controller
	{
		
		public function __construct()
		{
			parent::__construct();	
			$this->load->model("Html5_model","apps");
		}
		
		//公告H5页面
		public function item()
		{
			$query=$this->db->query("select * from `dg_articles` where `id`='3'");		
			$data["result"]=$query->row_array();
			$this->load->view("html5/notice.item.php",$data);
		}
		
	}