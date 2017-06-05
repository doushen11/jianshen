<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Index_model extends CI_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//教练端首页
		public function index()
		{
			$query=$this->db->query("select `file` from `dg_shuf` where `act`='2' order by `sort` asc");	
			json_array("10000","成功",$query->result_array());
		}
		
		//公告详情
		public function notice()
		{
			$query=$this->db->query("select * from `dg_articles` where `id`='2'");		
			$data["result"]=$query->row_array();
			$this->load->view("html5/notice.php",$data);
		}
		
	}
	
	