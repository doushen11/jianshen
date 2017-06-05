<?php
	
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Article extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
			$this->load->model("admin/Article_model","admins");
		}
		
		//文章资讯首页
		public function indexs()
		{
			$data["rs"]=$this->apps->N();
			$data["query"]=$this->db->query("select `id`,`title`,`times` from `dg_articles`");
			$this->load->view("admin/article/indexs.php",$data);
		}
		
		//文章修改
		public function edits()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_articles` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				$this->load->view("admin/article/indexs.edits.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}
		}
		
		//更新文章
		public function edit_subs()
		{
			$this->apps->A();
			$id=intval($this->uri->segment(4));
			echo $this->admins->edit_subs($id);
		}
		
	}