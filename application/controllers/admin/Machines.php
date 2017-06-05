<?php
	
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Machines extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
			$this->load->model("admin/Machines_model","admins");
		}
		
		//分类信息
		public function acts()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/machines/acts.php",$data);
		}
		
		//分类信息ajax显示
		public function acts_ajax()
		{
			$data["query"]=$this->db->query("select * from `dg_machine_act` order by `sort` asc");
			$this->load->view("admin/machines/acts.ajax.php",$data);
		}
		
		//分类上移
		public function ups()
		{
			$id=@$_GET["id"];
			echo $this->admins->ups($id);
		}
		
		//分类下移
		public function downs()
		{
			$id=@$_GET["id"];
			echo $this->admins->downs($id);
		}
		
		//修改分类名称
		public function act_edits()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_machine_act` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				$this->load->view("admin/machines/acts.edits.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}
		}
		
		//处理修改分类名称
		public function edit_subs()
		{
			$id=$this->uri->segment(4);
			echo $this->admins->edit_subs($id);
		}
		
		//健身器材列表
		public function indexs()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_machine_act` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
				isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="1";
				$data["id"]=$id;
				$this->load->view("admin/machines/indexs.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}			
		}
		
		//健身器材ajax读取对应列表
		public function indexs_ajaxs()
		{	
			$pagesize=12;
			$segment=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";
			isset($_REQUEST["id"]) && trim($_REQUEST["id"])!=""?$id=trim($_REQUEST["id"]):$id="";
			
			$where="";
			
			if($keywords!="")
			{
				$where=" and (`name` like '%$keywords%' or `alt` like '%$keywords%')";
			}
			
			$sql="select * from `dg_machine` where `type`='$id' ".$where." order by `sort` asc";
			//echo $sql;
			$sql=$this->db->page($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["id"]=$id;
			$data["arrs"]=$this->db->page_number($pagecount,$pageindex);
			$this->load->view("admin/machines/indexs.ajax.php",$data);			
		}
		
		//健身器材添加
		public function adds()
		{
			$data["rs"]=$this->apps->N();
			
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["id"]) && trim($_REQUEST["id"])!=""?$data["id"]=trim($_REQUEST["id"]):$data["id"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			
			$this->load->view("admin/machines/indexs.adds.php",$data);
			
		}
		
		//对应图片集上传函数
		public function indexs_uploads()
		{
			$this->admins->indexs_uploads();
		}
		
		//对应的视频上传函数
		public function mv_uploads()
		{
			$this->admins->mv_uploads();
		}
		
		//显示视频
		public function mvs()
		{
			//echo $_GET["p"];
			$this->load->view("admin/machines/mvs.php");
		}
		
		//添加健身器材
		public function adds_subs()
		{
			$id=intval($this->uri->segment(4));
			$this->admins->adds_subs($id);
		}
		
		//修改健身器材
		public function edits()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_machine` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				$this->load->view("admin/machines/index.edits.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}			
		}
		
		//修改健身器材处理
		public function edits_subs()
		{
			$id=intval($this->uri->segment(4));
			$this->admins->edits_subs($id);
		}
		
		//健身器材上移操作
		public function index_ups()
		{
			$id=@$_GET["id"];
			$type=@$_GET["type"];
			echo $this->admins->index_ups($id,$type);			
		}
		
		//健身器材下移操作
		public function index_downs()
		{
			$id=@$_GET["id"];
			$type=@$_GET["type"];
			echo $this->admins->index_downs($id,$type);			
		}
		
		//健身器材删除
		public function all_subs()
		{
			$id=$this->input->post("id");
			echo $this->admins->all_subs($id);
		}
		
	}