<?php
	
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Worker extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
			$this->load->model("admin/Worker_model","admins");
		}
		
		public function soss()
		{
			//sos操作记录
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";
			isset($_REQUEST["doors"]) && trim($_REQUEST["doors"])!=""?$data["doors"]=trim($_REQUEST["doors"]):$data["doors"]="";
			$this->load->view("admin/worker/soss.php",$data);	
		}
		
		public function soss_ajaxs()
		{
			//ajax读取soss操作记录
			$pagesize=15;
			$segment=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$states=trim($_REQUEST["states"]):$states="";		
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$start_time=strtotime(trim($_REQUEST["start_time"])."00:00:00"):$start_time="";	
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$end_time=strtotime(trim($_REQUEST["end_time"])."23:59:59"):$end_time="";	
			$where="";	
			if($keywords!="")
			{
				$where.=" and (`w`.`desc`='$keywords' or `w`.`mobile`='$keywords')";
			}

			if($start_time!="")
			{
				$where.=" and (`s`.`time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`s`.`time`<='$end_time')";
			}
			$sql="select `s`.*,`w`.`mobile`,`w`.`desc` from `dg_worker_sos` as `s` left join `dg_worker` as `w` on `s`.`uid`=`w`.`id` where `s`.`id`>0 ".$where." order by `s`.`id` desc";
			$sql=$this->db->page($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->db->page_number($pagecount,$pageindex);
			$this->load->view("admin/worker/soss.ajax.php",$data);	
		}
		
		public function indexs()
		{
			//工作人员
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";
			isset($_REQUEST["doors"]) && trim($_REQUEST["doors"])!=""?$data["doors"]=trim($_REQUEST["doors"]):$data["doors"]="";
			$this->load->view("admin/worker/indexs.php",$data);				
		}
		
		public function indexs_ajaxs()
		{
			//ajax读取对应工作人员信息
			$pagesize=15;
			$segment=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$states=trim($_REQUEST["states"]):$states="";	
			isset($_REQUEST["doors"]) && trim($_REQUEST["doors"])!=""?$doors=trim($_REQUEST["doors"]):$doors="";	
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$start_time=strtotime(trim($_REQUEST["start_time"])."00:00:00"):$start_time="";	
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$end_time=strtotime(trim($_REQUEST["end_time"])."23:59:59"):$end_time="";	
			$where="";	
			if($keywords!="")
			{
				$where.=" and (`desc`='$keywords' or `mobile`='$keywords' or `id`='$keywords')";
			}
			if($states!="")
			{
				$where.=" and (`state`='$states')";		
			}
			if($start_time!="")
			{
				$where.=" and (`reg_time`>='$start_time')";
			}
			if($doors!="")
			{
				if($doors==1)
				{
					$where.=" and (`doors`='1')";		
				}	
				else
				{
					$where.=" and (`doors`='0')";		
				}
			}
			if($end_time!="")
			{
				$where.=" and (`reg_time`<='$end_time')";
			}
			$sql="select * from `dg_worker` where `id`>'0' ".$where." order by `id` desc";
			$sql=$this->db->page($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->db->page_number($pagecount,$pageindex);
			$this->load->view("admin/worker/indexs.ajax.php",$data);
		}
		
		public function indexs_add()
		{
			//添加工作人员
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";
			$this->load->view("admin/worker/indexs_add.php",$data);	
		}
		
		public function index_inserts()
		{
			//程序处理添加工作人员
			echo $this->admins->index_inserts();	
		}
		
		//更改sos登录状态
		public function changes()
		{
			echo $this->admins->changes(intval($this->input->post("id")));			
		}	
		
		//删除sos会员
		public function index_dels()
		{
			echo $this->admins->index_dels($this->input->post("id"));	
		}	
		
		//修改sos会员
		public function index_edits()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_worker` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				$this->load->view("admin/worker/indexs.edits.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}	
		}
		
		//修改sos会员程序处理
		public function index_updates()
		{
			echo $this->admins->index_updates(intval($this->uri->segment(4)));	
		}
		
	}