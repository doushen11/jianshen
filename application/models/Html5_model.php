<?php
	
	//对位操作HTML5的model类
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Html5_model extends CI_Model
	{

		protected $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
		
		//文章读取接口
		public function reads($id)
		{
			$query=$this->db->query("select * from `dg_articles` where `id`='$id' limit 1");
			if($query->num_rows()>0)
			{
				return $query->row_array();	
			}
			return false;		
		}
		
		//教练的图文简介信息
		public function teach_item($id)
		{
			$query=$this->db->query("select `contents` from `dg_teacher` where `id`='$id' limit 1");
			if($query->num_rows()>0)
			{
				return $query->row_array();	
			}
			return false;					
		}
		
		//课程的图文简介信息
		public function classs_item($id)
		{
			$query=$this->db->query("select `contents`,`path`,`act` from `dg_class` where `id`='$id' limit 1");
			if($query->num_rows()>0)
			{
				return $query->row_array();	
			}
			return false;				
		}

		/**
		 * 查看详情页
		 */
		public function show_detail_page($param) {
		    return $this->db->query('select * from dg_shuf where id='.$param['id'] . ' limit 1')->row_array();
		}
	}