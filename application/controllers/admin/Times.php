<?php
	
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Times extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
			$this->load->model("admin/Times_model","admins");
		}
		
		//开门时间段设置
		public function opens()
		{
			$query1=$this->db->query("select * from `dg_time_model` where `id`='1'");
			$query2=$this->db->query("select * from `dg_time_model` where `id`='2'");
			$data["result1"]=$query1->row_array();
			$data["result2"]=$query2->row_array();
			$this->load->view("admin/times/opens.php",$data);
		}
		
		//开门时间段数据更新
		public function opens_subs()
		{
			echo $this->admins->opens_subs($this->input->post("start_1"),$this->input->post("start_2"),$this->input->post("end_1"),$this->input->post("end_2"));	
		}
		
		//私课高低峰时间设置
		public function class_a()
		{
			$data["query"]=$this->db->query("select * from `dg_time_model` where `act`='2' order by `model` asc");	
			$this->load->view("admin/times/class_a.php",$data);	
		}
		
		//私课高低峰时间更新
		public function class_a_subs()
		{
			echo $this->admins->class_a_subs($this->input->post("sphinx"),$this->input->post("del_id"));		
		}
		
		//操课高低峰时间设置
		public function class_b()
		{
			$data["query"]=$this->db->query("select * from `dg_time_model` where `act`='3' order by `model` asc");	
			$this->load->view("admin/times/class_b.php",$data);	
		}
		
		//操课高低峰时间更新
		public function class_b_subs()
		{
			echo $this->admins->class_b_subs($this->input->post("sphinx"),$this->input->post("del_id"));		
		}
		
	}