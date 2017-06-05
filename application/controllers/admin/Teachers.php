<?php
	
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Teachers extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
			$this->load->model("admin/Teachers_model","admins");
		}
		
		//打款记录
		public function draws()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/teachers/draws.php",$data);					
		}
		
		//ajax读取打款记录
		public function draws_ajaxs()
		{
			$pagesize=10;
			$segment=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$states=trim($_REQUEST["states"]):$states="";		
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$start_time=strtotime(trim($_REQUEST["start_time"])."00:00:00"):$start_time="";	
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$end_time=strtotime(trim($_REQUEST["end_time"])."23:59:59"):$end_time="";	
			$where="";	
			if($keywords!="")
			{
				$where.=" and (`u`.`nickname`='$keywords' or `u`.`mobile`='$keywords' or `d`.`text` like '%$keywords%' or `d`.`money`='$keywords')";
			}
			if($states!="")
			{
				$where.=" and (`d`.`act`>='$states')";	
			}
			if($start_time!="")
			{
				$where.=" and (`d`.`time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`d`.`time`<='$end_time')";
			}
			$sql="select `d`.*,`u`.`avatar`,`u`.`realname`,`u`.`mobile` as `mobile1` from `dg_teacher_money` as `d` left join `dg_teacher` as `u` on `d`.`tid`=`u`.`id` where `d`.`models`='2' ".$where." order by `d`.`id` desc";
			
			
			$sql1="select sum(`d`.`money`) as `price` from `dg_teacher_money` as `d` left join `dg_teacher` as `u` on `d`.`tid`=`u`.`id` where `d`.`models`='2' ".$where." order by `d`.`id` desc";
			$query1=$this->db->query($sql1);
			$data["result1"]=$query1->row_array();
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
			
			$this->load->view("admin/teachers/draws.ajax.php",$data);				
		}
		
		//私教打款
		public function sikes_draws()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_teacher` where `id`='$id' and `act`='1'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				$this->load->view("admin/teachers/indexs.draws.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}				
		}
		
		//私教打款处理
		public function indexs_draw_sub()
		{
			echo $this->admins->indexs_draw_sub();
		}
		
		
		//操课打款
		public function homes_draws()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_teacher` where `id`='$id' and `act`='2'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				if($data["result"]["balance_count"]<15)
				{
					error("抱歉：当前操课教练不符合打款条件，系统拒绝了您的操作",http_url()."admin/teachers/homes?keywords=".@$_GET["keywords"]."&pageindex=".@$_GET["pageindex"]."&start_time=".@$_GET["start_time"]."&end_time=".@$_GET["end_time"]."&states=".@$_GET["states"]);	
				}
				else
				{
					$this->load->view("admin/teachers/homes.draws.php",$data);	
				}
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}				
		}
		
		//操课打款处理
		public function homes_draw_sub()
		{
			echo $this->admins->homes_draw_sub();	
		}
		
		//教练收益
		public function moneys()
		{
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";
			isset($_REQUEST["doors"]) && trim($_REQUEST["doors"])!=""?$data["doors"]=trim($_REQUEST["doors"]):$data["doors"]="";
			$this->load->view("admin/teachers/moneys.php",$data);				
		}
		
		//ajax读取教练收益
		public function moneys_ajaxs()
		{
			$pagesize=15;
			$segment=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$states=trim($_REQUEST["states"]):$states="";		
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$start_time=strtotime(trim($_REQUEST["start_time"])."00:00:00"):$start_time="";	
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$end_time=strtotime(trim($_REQUEST["end_time"])."23:59:59"):$end_time="";	
			$where="";	
			if($keywords!="")
			{
				$where.=" and (`t`.`realname`='$keywords' or `t`.`mobile`='$keywords' or `t`.`id`='$keywords' or `t`.`gender`='$keywords')";
			}
			if($states!="")
			{
				$where.=" and (`m`.`act`='$states')";		
			}
			if($start_time!="")
			{
				$where.=" and (`m`.`time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`m`.`time`<='$end_time')";
			}
			$sql="select `m`.*,`t`.`realname`,`t`.`avatar`,`t`.`mobile`,`t`.`gender`,`t`.`level`,`t`.`score` from `dg_teacher_money` as `m` left join `dg_teacher` as `t` on `m`.`tid`=`t`.`id` where `m`.`models`='1' ".$where." order by `m`.`id` desc";
			
			$sql1="select sum(`m`.`money`) as `price`,sum(`m`.`money_in`) as `price1` from `dg_teacher_money` as `m` left join `dg_teacher` as `t` on `m`.`tid`=`t`.`id` where `m`.`models`='1' ".$where." order by `m`.`id` desc";
			
			$querys1=$this->db->query($sql1);
			$data["result1"]=$querys1->row_array();
			
			$sql=$this->db->page($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->db->page_number($pagecount,$pageindex);
			$this->load->view("admin/teachers/moneys.ajax.php",$data);					
		}
		
		//私教主页
		public function indexs()
		{
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";
			isset($_REQUEST["doors"]) && trim($_REQUEST["doors"])!=""?$data["doors"]=trim($_REQUEST["doors"]):$data["doors"]="";
			$this->load->view("admin/teachers/indexs.php",$data);	
		}
		
		//ajax删除私教
		public function index_dels()
		{
			echo $this->admins->index_dels($this->input->post("id"));	
		}
		
		//ajax删除操教
		public function home_dels()
		{
			echo $this->admins->home_dels($this->input->post("id"));	
		}
		
		//ajax读取私教信息
		public function indexs_ajaxs()
		{
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
				$where.=" and (`realname`='$keywords' or `mobile`='$keywords' or `id`='$keywords' or `gender`='$keywords')";
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
			$sql="select * from `dg_teacher` where `act`='1' ".$where." order by `id` desc";
			$sql=$this->db->page($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->db->page_number($pagecount,$pageindex);
			$this->load->view("admin/teachers/indexs.ajax.php",$data);				
		}
		
		//更改教练登录状态
		public function changes()
		{
			echo $this->admins->changes(intval($this->input->post("id")));			
		}
		
		//添加私课教练
		public function indexs_add()
		{
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";
			$this->load->view("admin/teachers/indexs_add.php",$data);				
		}
		
		//添加私课教练程序处理
		public function index_inserts()
		{
			echo $this->admins->index_inserts();			
		}
		
		//修改私课教练信息
		public function index_edits()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_teacher` where `id`='$id' and `act`='1'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				$this->load->view("admin/teachers/indexs.edits.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}				
		}
		
		//修改程序处理
		public function index_updates()
		{
			echo $this->admins->index_updates(intval($this->uri->segment(4)));
		}
		
		
		//操课教练主页
		public function homes()
		{
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";
			isset($_REQUEST["doors"]) && trim($_REQUEST["doors"])!=""?$data["doors"]=trim($_REQUEST["doors"]):$data["doors"]="";
			$this->load->view("admin/teachers/homes.php",$data);				
		}
		
		//ajax读取操课教练
		public function homes_ajaxs()
		{
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
				if($keywords=="打款")
				{
					$where.=" and `balance_count`>15";	
				}
				else
				{
					$where.=" and (`realname`='$keywords' or `mobile`='$keywords' or `id`='$keywords' or `gender`='$keywords')";
				}
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
			if($states!="")
			{
				$where.=" and (`state`='$states')";		
			}
			if($start_time!="")
			{
				$where.=" and (`reg_time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`reg_time`<='$end_time')";
			}
			$sql="select * from `dg_teacher` where `act`='2' ".$where." order by `id` desc";
			//echo $sql;
			$sql=$this->db->page($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->db->page_number($pagecount,$pageindex);
			$this->load->view("admin/teachers/homes.ajax.php",$data);				
		}
		
		//操课教练添加
		public function homes_add()
		{
			$data["rs"]=$this->apps->N();
			$data["querys"]=$this->db->query("select `id`,`name` from `dg_class` order by `id` asc");
			$this->load->view("admin/teachers/homes_add.php",$data);				
		}
		
		//操课教练添加
		public function home_inserts()
		{
			echo $this->admins->home_inserts();
		}
		
		//操课教练修改
		public function homes_edits()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_teacher` where `id`='$id' and `act`='2'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				$data["querys"]=$this->db->query("select `id`,`name` from `dg_class` order by `id` asc");
				$this->load->view("admin/teachers/homes.edits.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}				
		}
		
		//修改操课教练
		public function home_updates()
		{
			echo $this->admins->home_updates(intval($this->uri->segment(4)));	
		}
	}