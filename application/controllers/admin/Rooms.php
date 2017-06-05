<?php
	
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Rooms extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
			$this->load->model("admin/Rooms_model","admins");
		}
		
		public function indexs()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/rooms/indexs.php",$data);			
		}
		
		public function indexs_ajaxs()
		{
			$data["query"]=$this->db->query("select * from `dg_rooms` order by `id` asc");
			$this->load->view("admin/rooms/indexs.ajaxs.php",$data);
		}
		
		public function adds()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/rooms/indexs.adds.php",$data);		
		}
		
		public function adds_subs()
		{
			echo $this->admins->adds_subs($this->input->post("name"),$this->input->post("count"));
		}
		
		public function all_subs()
		{
			echo $this->admins->all_subs($this->input->post("id"));
		}
		
		public function edits()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_rooms` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				$this->load->view("admin/rooms/indexs.edits.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}			
		}
		
		public function edits_subs()
		{
			echo $this->admins->edits_subs($this->uri->segment(4),$this->input->post("name"),$this->input->post("count"));
		}
		
	}