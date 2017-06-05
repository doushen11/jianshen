<?php
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Others extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
			$this->load->model("admin/Admin_model","admins");
		}
		
		//推送模板
		public function pushs()
		{
			$data["rs"]=$this->apps->N();	
			$this->load->view("admin/others/pushs.php",$data);	
		}
		
		//推送模板更新
		public function pushs_subs()
		{
			$data["rs"]=$this->apps->A();
			echo $this->admins->pushs_subs();					
		}
		
		//系统配置
		public function systems()
		{
			$data["rs"]=$this->apps->N();	
			$this->load->view("admin/others/systems.php",$data);
		}
		
		//更新系统配置
		public function systems_subs()
		{
			$data["rs"]=$this->apps->A();
			echo $this->admins->systems_subs();	
		}
		
		//关于我们
		public function abouts()
		{
			$data["rs"]=$this->apps->N();
			$query=$this->db->query("select * from `dg_abouts` where `id`='1'");
			$data["result"]=$query->row_array();
			$this->load->view("admin/others/abouts.php",$data);
		}
		
		//关于我们图片上传
		public function abouts_uploads()
		{
			$this->admins->abouts_uploads();
		}
		
		//关于我们程序更新
		public function abouts_subs()
		{
			$this->admins->abouts_subs();
		}
		
		//健身房信息
		public function houses()
		{
			$data["rs"]=$this->apps->N();
			$query=$this->db->query("select * from `dg_config` where `id`='1'");
			$data["result"]=$query->row_array();
			$this->load->view("admin/others/houses.php",$data);			
		}
		
		//健身房图片更新
		public function houses_uploads()
		{
			$this->admins->houses_uploads();	
		}
		
		//健身房信息更新
		public function houses_subs()
		{
			$this->admins->houses_subs();
		}
		
		//进门价格设置
		public function opens()
		{
			$data["rs"]=$this->apps->N();
			$data["query"]=$this->db->query("select * from `dg_pay_model` order by `id` asc");
			$this->load->view("admin/others/opens.php",$data);
		}
		
		//进门价格数据处理
		public function opens_subs()
		{
			$this->admins->opens_subs($this->input->post("sphinx"),$this->input->post("del_id"));
		}
		
		//轮播图设置
		public function shufs()
		{
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["act"]) && trim($_REQUEST["act"])==2?$act=2:$act=1;
			$data["act"]=$act;
			$this->load->view("admin/others/shufs.php",$data);	
		}
		
		//轮播图ajax读取
		public function shufs_ajaxs()
		{
			isset($_REQUEST["acts"]) && trim($_REQUEST["acts"])==2?$acts=2:$acts=1;	
			$data["query"]=$this->db->query("select * from `dg_shuf` where `act`='$acts' order by `sort` asc");
			$data["acts"]=$acts;
			$this->load->view("admin/others/shufs.ajaxs.php",$data);
		}
		
		//轮播图添加
		public function shufs_adds()
		{
			$data["id"]=intval($this->uri->segment(4));	
			$this->load->view("admin/others/shufs.adds.php",$data);
		}
		
		//轮播图添加
		public function shufs_inserts()
		{
			$this->admins->shufs_inserts($this->input->post("file"),$this->input->post("alt"),$this->input->post("act"),$this->input->post("content"));
		}
		
		//轮播图修改
		public function shufs_edits()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_shuf` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				$this->load->view("admin/others/shufs.edits.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}	
		}
		
		//轮播图修改处理
		public function shufs_updates()
		{
			$this->admins->shufs_updates($this->input->post("file"),$this->input->post("alt"),$this->input->post("act"),$this->input->post("content"),intval($this->uri->segment(4)));	
		}
		
		//轮播图删除
		public function shufs_dels()
		{
			$this->admins->shufs_dels($this->input->post("id"));	
		}
	}