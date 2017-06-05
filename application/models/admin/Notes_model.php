<?php
	//后台的管理员控制器

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Notes_model extends CI_Model
	{

		private $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
		
		public function reads($id)
		{
			$this->db->query("update `dg_note` set `read`='2' where `id`='$id'");
		}
		
		public function all_subs($id)
		{
			if($this->db->query("delete from `dg_note` where `id` in (".$id.")"))
			{
				ajaxs(10000,"删除成功");
			}
			else
			{
				ajaxs(30000,"网络连接失败");
			}
		}
		
	}