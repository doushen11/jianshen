<?php
	//后台的管理员控制器

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Rooms_model extends CI_Model
	{

		private $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
		
		public function adds_subs($name,$count)
		{
			$query=$this->db->query("select `id` from `dg_rooms` where `name`='$name' limit 1");
			if($query->num_rows()>0)
			{
				ajaxs(30000,"抱歉：已经存在相同的课厅名称");
			}
			else
			{
				$_array=array(
					"name"=>$name,
					"count"=>$count,
				);
				if($this->db->insert("rooms",$_array))
				{
					ajaxs(10000,"添加成功");
				}
				else
				{
					ajaxs(30000,"网络连接失败");
				}
			}
		}
		
		public function edits_subs($id,$name,$count)
		{
			$query=$this->db->query("select `id` from `dg_rooms` where `name`='$name' and `id`!='$id' limit 1");
			if($query->num_rows()>0)
			{
				ajaxs(30000,"抱歉：已经存在相同的课厅名称");
			}
			else
			{
				$_array=array(
					"name"=>$name,
					"count"=>$count,
				);
				if($this->db->update("rooms",$_array,array("id"=>$id)))
				{
					ajaxs(10000,"修改成功");
				}
				else
				{
					ajaxs(30000,"网络连接失败");
				}
			}			
		}
		
		public function all_subs($id)
		{
			$query=$this->db->query("select `id` from `dg_tearch_plans` where `room_id`='$id' limit 1");
			if($query->num_rows()>0)
			{
				ajaxs(30000,"抱歉：当前课厅已安排过课程，无法删除");
			}
			else
			{
				if($this->db->query("delete from `dg_rooms` where `id`='$id'"))
				{
					ajaxs(10000,"删除成功");
				}
				else
				{
					ajaxs(30000,"网络连接失败");
				}
			}			
		}
	
		
	}