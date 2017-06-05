<?php
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Admins extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
			$this->load->model("admin/Admin_model","admins");
		}
		
		public function logouts()
		{
			//退出会员
			setcookie("rs_author","",time()-3600*30*24,"/");
			header("location:".http_url()."admin/login/indexs");	
		}
		
		public function welcome()
		{
			//欢迎页面
			$data["rs"]=$this->apps->N();	
			$this->load->view("admin/admins/welcome.php",$data);
		}
		
		public function countss()
		{
			//sleep(10);
			$qy=$this->db->query("select `id` from `dg_note` where `read`='1'");
			echo $qy->num_rows();
		}
		
		public function indexs()
		{
			//管理员首页
			$data["rs"]=$this->apps->N();
			$data["title"]="管理员列表";
			$data["rs"]=$this->apps->A();	
			$data["query"]=$this->admins->get_admins();
			$this->load->view("admin/admins/indexs.php",$data);
		}
		
		public function adds()
		{
			//添加管理员
			$data["rs"]=$this->apps->N();
			$data["title"]="添加管理员";
			$this->load->view("admin/admins/indexs.adds.php",$data);		
		}
		
		public function adds_subs()
		{
			//添加管理员程序处理
			$rs=$this->apps->A();
			$username=$this->input->post("username");
			$passwd=$this->input->post("passwd");
			echo $this->admins->adds_subs($username,$passwd);	
		}
		
		public function edits()
		{
			//修改管理员信息
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select `id`,`username` from `dg_admin` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				$data["title"]="修改管理员";
				$this->load->view("admin/admins/indexs.edits.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}
		}
		
		public function edits_subs()
		{
			//修改管理员
			$rs=$this->apps->A();
			$id=intval($this->uri->segment(4));
			$passwd=$this->input->post("passwd");
			echo $this->admins->edits_subs($id,$passwd);		
		}
		
		public function dels()
		{
			//删除管理员
			//sleep(5);
			$rs=$this->apps->A();
			$id=$this->input->post("id");
			echo $this->admins->dels($id);				
		}
		
		
		public function systems()
		{
			//配置常用信息
			$data["rs"]=$this->apps->N();
			$data["title"]="配置常规信息";
			$this->load->view("admin/admins/indexs.inc.php",$data);	
		}
		
		public function system_subs()
		{
			//更新系统常用配置信息
			$rs=$this->apps->R();
			echo $this->admins->system_subs();	
		}
			
	}