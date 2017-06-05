<?php
	
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Doors extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
		}
		
		//sos进门记录管理
		public function sos()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/doors/sos.php",$data);
		}
		
		//ajax读取进门记录信息
		public function sos_ajaxs()
		{
			$pagesize=12;
			$segment=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";		
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$start_time=strtotime(trim($_REQUEST["start_time"])."00:00:00"):$start_time="";	
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$end_time=strtotime(trim($_REQUEST["end_time"])."23:59:59"):$end_time="";	
			$where="";
			
			if($keywords!="")
			{
				$where.=" and (`u`.`desc` like '%$keywords%' or `u`.`mobile`='$keywords')";
			}
			
			if($start_time!="")
			{
				$where.=" and (`n`.`start_time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`n`.`end_time`<='$end_time')";
			}
			
			$sql="select `n`.*,`u`.`mobile`,`u`.`desc` as `nickname` from `dg_doors` as `n` left join `dg_worker` as `u` on `n`.`uid`=`u`.`id` where `n`.`act`='3' ".$where." order by `n`.`id` desc";
			//echo $sql;
			$sql=$this->db->page($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->db->page_number($pagecount,$pageindex);

			
			$this->load->view("admin/doors/sos.ajax.php",$data);	
		}
		
		//教练进门记录管理
		public function indexs()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/doors/indexs.php",$data);
		}
		
		//教练进门记录ajax读取
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
				$where.=" and (`u`.`realname` like '%$keywords%' or `u`.`mobile`='$keywords')";
			}
			
			if($start_time!="")
			{
				$where.=" and (`n`.`start_time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`n`.`end_time`<='$end_time')";
			}
			
			$sql="select `n`.*,`u`.`mobile`,`u`.`realname` as `nickname`,`u`.`avatar`,`u`.`gender` from `dg_doors` as `n` left join `dg_teacher` as `u` on `n`.`uid`=`u`.`id` where `n`.`act`='2' ".$where." order by `n`.`id` desc";
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
			
			$this->load->view("admin/doors/indexs.ajax.php",$data);				
		}
		
		//学员进门记录管理
		public function homes()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/doors/homes.php",$data);
		}
		
		//ajax读取学员进门记录管理
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
				$where.=" and (`u`.`nickname` like '%$keywords%' or `u`.`mobile`='$keywords')";
			}
			
			if($start_time!="")
			{
				$where.=" and (`n`.`start_time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`n`.`end_time`<='$end_time')";
			}
			
			$sql="select `n`.*,`u`.`mobile`,`u`.`nickname`,`u`.`avatar`,`u`.`gender` from `dg_doors` as `n` left join `dg_user` as `u` on `n`.`uid`=`u`.`id` where `n`.`act`='1' ".$where." order by `n`.`id` desc";
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
			
			$this->load->view("admin/doors/homes.ajax.php",$data);					
		}
		
		//系统分析
		public function maps()
		{
			$date=date("Y-m-d");
			if(isset($_GET["days"]) && trim($_GET["days"])!="")
			{
				$date=trim($_GET["days"]);
			}	
			$data["date"]=$date;
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/doors/maps.php",$data);
			
		}
		
	}