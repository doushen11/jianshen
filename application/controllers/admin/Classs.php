<?php
	
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Classs extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
			$this->load->model("admin/Classs_model","admins");
		}
		
		
		//课程曲线统计
		public function index_tj()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_class` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				$data["querys"]=$this->db->query("select `id`,`name` from `dg_class`");
				$this->load->view("admin/classs/indexs.tongji.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}				
		}
		
		//课程列表
		public function indexs()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/classs/indexs.php",$data);				
		}
		
		//ajax读取对应课程列表
		public function indexs_ajaxs()
		{
			$data["query"]=$this->db->query("select * from `dg_class` order by `id` asc");
			$this->load->view("admin/classs/indexs.ajaxs.php",$data);	
		}
		
		//课程添加
		public function adds()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/classs/indexs.adds.php",$data);				
		}
		
		//课程添加处理
		public function adds_subs()
		{
			echo $this->admins->adds_subs();	
		}
		
		//课程删除
		public function dels()
		{
			echo $this->admins->dels($this->input->post("id"));		
		}
		
		//课程修改
		public function edits()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_class` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				$this->load->view("admin/classs/indexs.edits.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}
		}
		
		//课程修改
		public function edits_subs()
		{
			echo $this->admins->edits_subs($this->uri->segment(4));	
		}
		
		//私课管理
		public function sikes()
		{
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";			
			$this->load->view("admin/classs/sikes.php",$data);	
		}
		
		//私课ajax读取
		public function sikes_ajaxs()
		{
			$pagesize=13;
			$segment=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$states=trim($_REQUEST["states"]):$states="";		
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$start_time=strtotime(trim($_REQUEST["start_time"])."00:00:00"):$start_time="";	
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$end_time=strtotime(trim($_REQUEST["end_time"])."23:59:59"):$end_time="";	
			$where="";	
			if($keywords!="")
			{
				$where.=" and (`t`.`realname`='$keywords' or `t`.`mobile`='$keywords' or `t`.`gender`='$keywords' and `p`.`money`='$keywords')";
			}
			if($states!="")
			{
				$where.=" and (`p`.`state`='$states')";		
			}
			if($start_time!="")
			{
				$where.=" and (`p`.`start_time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`p`.`end_time`<='$end_time')";
			}
			$sql="select `p`.*,`t`.`realname`,`t`.`mobile`,`t`.`avatar`,`t`.`gender`,`t`.`level`,`t`.`score`,`t`.`desc`,`t`.`balance`,`t`.`reg_time`,`t`.`login_time`,`t`.`contents`,`t`.`user_agent` from `dg_tearch_plan_list` as `p` left join `dg_teacher` as `t` on `p`.`tid`=`t`.`id` where `p`.`id`>'0' ".$where." order by `p`.`end_time` desc";
			//echo $sql;die();
			$sql=$this->db->page($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->db->page_number($pagecount,$pageindex);
			//查看最后一条的课程信息
			$querys=$this->db->query("select `end_time` from `dg_tearch_plan_list` order by `end_time` desc limit 0,1");
			if($querys->num_rows()>0)
			{
				$results=$querys->row_array();
				$data["time"]=$results["end_time"];
			}
			else
			{
				$data["time"]="";	
			}
			$this->load->view("admin/classs/sikes.ajax.php",$data);				
		}
		
		//私课修改页面
		public function sikes_edits()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$sql="select `p`.*,`t`.`realname`,`t`.`mobile`,`t`.`avatar`,`t`.`gender`,`t`.`level`,`t`.`score`,`t`.`desc`,`t`.`balance`,`t`.`reg_time`,`t`.`login_time`,`t`.`contents`,`t`.`user_agent` from `dg_tearch_plan_list` as `p` left join `dg_teacher` as `t` on `p`.`tid`=`t`.`id` where `p`.`id`='$id' limit 1";
			$query=$this->db->query($sql);
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				if($data["result"]["state"]==2)
				{
					$this->load->view("admin/classs/sikes.edits.php",$data);
				}
				else
				{
					error("抱歉：当前课程状态不允许修改", http_url()."admin/classs/sikes?keywords=".@$_GET["keywords"]."&pageindex=".@$_GET["pageindex"]."&start_time=".@$_GET["start_time"]."&end_time=".@$_GET["end_time"]."&states=".@$_GET["states"]);	
				}
			}
			else
			{
				error("抱歉：没有找到对应信息", http_url()."admin/classs/sikes?keywords=".@$_GET["keywords"]."&pageindex=".@$_GET["pageindex"]."&start_time=".@$_GET["start_time"]."&end_time=".@$_GET["end_time"]."&states=".@$_GET["states"]);	
			}
		}
		
		//处理修改私课程序
		public function sikes_edits_subs()
		{
			$this->admins->sikes_edits_subs();
		}
		
		//处理删除私课程序
		public function sikes_dels()
		{
			$this->admins->sikes_dels();	
		}
		
		//私课添加
		public function sikes_add()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_teacher` where `id`='$id' and `act`='1'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				//查看最后一条的课程信息
				$querys=$this->db->query("select `end_time` from `dg_tearch_plan_list` order by `end_time` desc limit 0,1");
				if($querys->num_rows()>0)
				{
					$results=$querys->row_array();
					$data["time"]=$results["end_time"];
				}
				else
				{
					$data["time"]="";	
				}				
				$this->load->view("admin/classs/sikes.add.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}	
		}
		
		//私课添加
		public function sikes_adds_subs()
		{
			$this->admins->sikes_adds_subs();	
		}
		
		//操课管理
		public function caokes()
		{
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";
			isset($_REQUEST["states1"]) && trim($_REQUEST["states1"])!=""?$data["states1"]=trim($_REQUEST["states1"]):$data["states1"]="";	
			isset($_REQUEST["rooms"]) && trim($_REQUEST["rooms"])!=""?$data["rooms"]=trim($_REQUEST["rooms"]):$data["rooms"]="";		
			$data["query"]=$this->db->query("select `id`,`name` from `dg_rooms`");	
			$this->load->view("admin/classs/caokes.php",$data);				
		}
		
		//ajax读取操课信息
		public function caokes_ajaxs()
		{
			$pagesize=13;
			$segment=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$states=trim($_REQUEST["states"]):$states="";		
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$start_time=strtotime(trim($_REQUEST["start_time"])."00:00:00"):$start_time="";	
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$end_time=strtotime(trim($_REQUEST["end_time"])."23:59:59"):$end_time="";	
			isset($_REQUEST["states1"]) && trim($_REQUEST["states1"])!=""?$states1=trim($_REQUEST["states1"]):$states1="";	
			isset($_REQUEST["rooms"]) && trim($_REQUEST["rooms"])!=""?$rooms=trim($_REQUEST["rooms"]):$rooms="";
			$where="";	
			if($keywords!="")
			{
				$where.=" and (`t`.`realname`='$keywords' or `t`.`mobile`='$keywords' or `t`.`gender`='$keywords' and `p`.`sale`='$keywords')";
			}
			if($states!="")
			{
				$where.=" and (`p`.`state`='$states')";		
			}
			if($rooms!="")
			{
				$where.=" and (`p`.`room_id`='$rooms')";		
			}
			if($states1!="")
			{
				if($states1==1)
				{
					$where.=" and (`p`.`sale`='0')";		
				}
				else
				{
					$where.=" and (`p`.`sale`>0)";		
				}	
			}
			if($start_time!="")
			{
				$where.=" and (`p`.`start_time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`p`.`end_time`<='$end_time')";
			}
			$sql="select `p`.*,`t`.`realname`,`t`.`mobile`,`t`.`avatar`,`t`.`gender`,`t`.`level`,`t`.`score`,`t`.`desc`,`t`.`balance`,`t`.`reg_time`,`t`.`login_time`,`t`.`contents`,`t`.`user_agent` from `dg_tearch_plans` as `p` left join `dg_teacher` as `t` on `p`.`tid`=`t`.`id` where `p`.`id`>'0' ".$where." order by `p`.`end_time` desc";
			//echo $sql;die();
			$sql=$this->db->page($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->db->page_number($pagecount,$pageindex);
			//查看最后一条的课程信息
			$querys=$this->db->query("select `end_time` from `dg_tearch_plans` order by `end_time` desc limit 0,1");
			if($querys->num_rows()>0)
			{
				$results=$querys->row_array();
				$data["time"]=$results["end_time"];
			}
			else
			{
				$data["time"]="";	
			}
			$this->load->view("admin/classs/caokes.ajax.php",$data);					
		}
		
		//操课删除
		public function caokes_dels()
		{
			$this->admins->caokes_dels();	
		}
		
		//操课添加
		public function caokes_add()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_teacher` where `id`='$id' and `act`='2'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				//查看最后一条的课程信息
				$querys=$this->db->query("select `end_time` from `dg_tearch_plans` order by `end_time` desc limit 0,1");
				if($querys->num_rows()>0)
				{
					$results=$querys->row_array();
					$data["time"]=$results["end_time"];
				}
				else
				{
					$data["time"]="";	
				}	
				isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
				isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
				isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
				isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
				isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";	
				$data["query1"]=$this->db->query("select `id`,`name`,`counts` from `dg_class`");
				$data["query2"]=$this->db->query("select `id`,`name`,`count` as `counts` from `dg_rooms`");
				$this->load->view("admin/classs/caokes.add.php",$data);									
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}
					
		}
		
		//操课添加
		public function caokes_adds_subs()
		{
			echo $this->admins->caokes_adds_subs();	
		}
		
		//操课修改
		public function caokes_edits()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$sql="select `p`.*,`t`.`realname`,`t`.`mobile`,`t`.`avatar`,`t`.`gender`,`t`.`level`,`t`.`score`,`t`.`desc`,`t`.`money_desc`,`t`.`balance`,`t`.`reg_time`,`t`.`login_time`,`t`.`contents`,`t`.`user_agent` from `dg_tearch_plans` as `p` left join `dg_teacher` as `t` on `p`.`tid`=`t`.`id` where `p`.`id`='$id' limit 1";
			$query=$this->db->query($sql);
			if($query->num_rows()>0)
			{
				$time=strtotime(date("Y-m-d",time()+3600*24*7)."00:00:00");
				$data["result"]=$query->row_array();
				if($data["result"]["state"]==1 && $data["result"]["loads"]==0)
				{
					if($data["result"]["start_time"]>$time)
					{
						$data["query1"]=$this->db->query("select `id`,`name`,`counts` from `dg_class`");
						$data["query2"]=$this->db->query("select `id`,`name`,`count` as `counts` from `dg_rooms`");
						$querys=$this->db->query("select `end_time` from `dg_tearch_plans` order by `end_time` desc limit 0,1");
						if($querys->num_rows()>0)
						{
							$results=$querys->row_array();
							$data["time"]=$results["end_time"];
						}
						else
						{
							$data["time"]="";	
						}
						$this->load->view("admin/classs/caokes.edits.php",$data);	
					}
					else
					{
						error("抱歉：未来七日内课程不允许修改", http_url()."admin/classs/caokes?keywords=".@$_GET["keywords"]."&pageindex=".@$_GET["pageindex"]."&start_time=".@$_GET["start_time"]."&end_time=".@$_GET["end_time"]."&states=".@$_GET["states"]);	
					}
					
				}
				else
				{
					error("抱歉：当前课程状态不允许修改", http_url()."admin/classs/caokes?keywords=".@$_GET["keywords"]."&pageindex=".@$_GET["pageindex"]."&start_time=".@$_GET["start_time"]."&end_time=".@$_GET["end_time"]."&states=".@$_GET["states"]);	
				}
			}
			else
			{
				error("抱歉：没有找到对应信息", http_url()."admin/classs/caokes?keywords=".@$_GET["keywords"]."&pageindex=".@$_GET["pageindex"]."&start_time=".@$_GET["start_time"]."&end_time=".@$_GET["end_time"]."&states=".@$_GET["states"]);	
			}				
		}
		
		//操课修改
		public function caokes_edit_subs()
		{
			echo $this->admins->caokes_edit_subs();	
		}
	}

	
	