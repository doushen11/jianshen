<?php
	
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Notes extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
			$this->load->model("admin/Notes_model","admins");
		}
		
		//健身器材列表
		public function indexs()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/notes/indexs.php",$data);
		}
		
		//健身器材ajax读取信息
		public function indexs_ajaxs()
		{
			$pagesize=12;
			$segment=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";		
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$start_time=strtotime(trim($_REQUEST["start_time"])."00:00:00"):$start_time="";	
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$end_time=strtotime(trim($_REQUEST["end_time"])."23:59:59"):$end_time="";	
			$where="";
			
			if($keywords!="")
			{
				$where.=" and (`u`.`nickname` like '%$keywords%' or `u`.`mobile`='$keywords' or `n`.`contents` like '%$keywords%')";
			}
			
			if($start_time!="")
			{
				$where.=" and (`n`.`time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`n`.`time`<='$end_time')";
			}
			
			$sql="select `n`.*,`u`.`mobile`,`u`.`nickname`,`u`.`avatar` from `dg_note` as `n` left join `dg_user` as `u` on `n`.`uid`=`u`.`id` where `n`.`act`='1' ".$where." order by `n`.`id` desc";
			//echo $sql;
			$sql=$this->db->page($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->db->page_number($pagecount,$pageindex);
			
			require FCPATH."config/img.inc.php";
			
			$data["avatar"]=$img_inc["avatar"];
			
			$this->load->view("admin/notes/indexs.ajax.php",$data);				
		}
		
		//读取详细信息
		public function reads()
		{
			echo $this->admins->reads(@$_GET["id"]);
		}
		
		//删除所有信息
		public function all_subs()
		{
			echo $this->admins->all_subs($this->input->post("id"));
		}
		
		//健身器材列表
		public function homes()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/notes/homes.php",$data);
		}
		
		//健身器材ajax读取信息
		public function homes_ajaxs()
		{
			$pagesize=12;
			$segment=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";		
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$start_time=strtotime(trim($_REQUEST["start_time"])."00:00:00"):$start_time="";	
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$end_time=strtotime(trim($_REQUEST["end_time"])."23:59:59"):$end_time="";	
			$where="";
			
			if($keywords!="")
			{
				$where=" and (`u`.`realname` like '%$keywords%' or `u`.`mobile`='$keywords' or `n`.`contents` like '%$keywords%')";
			}
			
			if($start_time!="")
			{
				$where=" and (`n`.`time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where=" and (`n`.`time`<='$end_time')";
			}
			
			$sql="select `n`.*,`u`.`mobile`,`u`.`realname` as `nickname`,`u`.`avatar`,`u`.`gender` from `dg_note` as `n` left join `dg_teacher` as `u` on `n`.`uid`=`u`.`id` where `n`.`act`='2' ".$where." order by `n`.`id` desc";
			//echo $sql;
			$sql=$this->db->page($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->db->page_number($pagecount,$pageindex);
			
			require FCPATH."config/img.inc.php";
			
			$data["avatar"]=$img_inc["avatar"];
			
			$this->load->view("admin/notes/homes.ajax.php",$data);				
		}		
	
	}