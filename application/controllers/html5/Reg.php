<?php
	
	//注册协议
	class Reg extends CI_Controller
	{
		
		public function __construct()
		{
			parent::__construct();
		}	
		
		//注册协议
		public function indexs()
		{
			$query=$this->db->query("select * from `dg_articles` where `id`='5'");
			$data["result"]=$query->row_array();
			$this->load->view("html5/reg_item.php",$data);	
		}
	}