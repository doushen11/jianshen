<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Home_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//健身房大厅列表
		public function houses()
		{
			$query=$this->db->query("select * from `dg_rooms` order by `id` asc");
			json_array2("10000","成功",$query->result_array());	
		}
		
		//健身房首页信息显示
		public function index()
		{
			$query=$this->db->query("select `people`,`room_file`,`machine_file` from `dg_config` where `id`='1'");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				json_array2("10000","成功",$result);
			}
			else
			{
				error_show();	
			}
		}
		
		//健身房详情信息显示
		public function rooms()
		{
			$query=$this->db->query("select `people`,`room_file`,`name`,`address`,`phone`,`room_text`,`class_text` from `dg_config` where `id`='1'");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				json_array2("10000","成功",$result);
			}
			else
			{
				error_show();
			}				
		}
		
		//健身房器材分类信息
		public function acts()
		{
			$query=$this->db->query("select `id`,`name` from `dg_machine_act` order by `sort` asc");
			json_array2("10000","成功",$query->result_array());	
		}
		
		//健身器材列表信息
		public function machines($id)
		{
			$query=$this->db->query("select `id` from `dg_machine_act` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
				$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
				$sql="select `id`,`file`,`name` from `dg_machine` where `type`='$id' order by `sort` asc";
				$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
				$query1=$this->db->query($sql);	
				json_array2("10000","成功",$query1->result_array());
			}
			else
			{
				error_show();
			}				
		}
		
		//健身器材详情信息
		public function machine($id)
		{
			$query=$this->db->query("select `name`,`id`,`alt`,`file`,`act`,`video_path`,`file_path` from `dg_machine` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				if($result["file_path"]=="")
				{
					$result["file_path"]=array();	
				}
				else
				{
					$result["file_path"]=json_decode($result["file_path"],true);
				}
				json_array2("10000","成功",$result);
			}
			else
			{
				error_show();
			}
		}
		
	}