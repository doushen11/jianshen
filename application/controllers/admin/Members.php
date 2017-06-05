<?php
	
	//会员后台控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Members extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/Mains_model","apps");
			$this->load->model("admin/Members_model","admins");
		}
		
		//出门操作页面
		public function out_doors()
		{
			$data["rs"]=$this->apps->N();
			$id=$this->uri->segment(4);
			$query=$this->db->query("select * from `dg_user` where `id`='$id'");
			if($query->num_rows()>0)
			{
				$data["result"]=$query->row_array();
				if($data["result"]["doors"]==1)
				{
					$this->load->view("admin/members/outdoors.php",$data);
				}
				else
				{
					error("抱歉:当前会员不在健身房内，系统拒绝执行当前操作");		
				}
			}
			else
			{
				error("抱歉:没有找到对应会员信息");	
			}
			
		}
		
		//模拟出门操作
		public function door_out_sub()
		{
			$data["rs"]=$this->apps->A();
			echo $this->admins->door_out_sub($this->uri->segment(4));	
		}
		
		//提现纪录
		public function tixians()
		{
			$data["rs"]=$this->apps->N();
			$this->load->view("admin/members/tixians.php",$data);	
		}
		
		//ajax读取提现纪录
		public function tixians_ajaxs()
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
				$where.=" and (`u`.`nickname`='$keywords' or `u`.`mobile`='$keywords' or `d`.`desc` like '%$keywords%')";
			}
			if($start_time!="")
			{
				$where.=" and (`d`.`time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`d`.`time`<='$end_time')";
			}
			$sql="select `d`.*,`u`.`avatar`,`u`.`nickname`,`u`.`mobile` as `mobile1` from `dg_user_draw` as `d` left join `dg_user` as `u` on `d`.`uid`=`u`.`id` where `d`.`id`>'0' ".$where." order by `d`.`id` desc";
			
			
			$sql1="select sum(`d`.`money`) as `price` from `dg_user_draw` as `d` left join `dg_user` as `u` on `d`.`uid`=`u`.`id` where `d`.`id`>'0' ".$where." order by `d`.`id` desc";
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
			
			$this->load->view("admin/members/tixians.ajax.php",$data);				
		}
		
		//操课购买记录
		public function caokes()
		{
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";
			$this->load->view("admin/members/caokes.php",$data);	
		}
		
		//ajax读取操课购买记录
		public function caokes_ajaxs()
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
				$where.=" and (`u`.`nickname`='$keywords' or `u`.`mobile`='$keywords' or `p`.`id`='$keywords' or `t`.`realname`='$keywords' or `t`.`mobile`='$keywords')";
			}
			if($states!="")
			{
				$where.=" and (`o`.`state`='$states')";		
			}
			if($start_time!="")
			{
				$where.=" and (`o`.`time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`o`.`time`<='$end_time')";
			}
			$sql="select `o`.*,`u`.`nickname`,`u`.`mobile` as `mobile1`,`t`.`mobile` as `mobile2`,`t`.`realname`,`u`.`avatar` as `avatar1`,`t`.`avatar` as `avatar2`,`o`.`uid`,`p`.`tid`,`p`.`date`,`p`.`node`,`p`.`class_name`,`p`.`room_name` from `dg_orders` as `o` left join `dg_user` as `u` on `o`.`uid`=`u`.`id` left join `dg_tearch_plans` as `p` on `o`.`pid`=`p`.`id` left join `dg_teacher` as `t` on `p`.`tid`=`t`.`id` where `o`.`act`='2' ".$where." order by `o`.`id` desc";
			$sql1="select sum(`o`.`money`) as `price` from `dg_orders` as `o` left join `dg_user` as `u` on `o`.`uid`=`u`.`id` left join `dg_tearch_plans` as `p` on `o`.`pid`=`p`.`id` left join `dg_teacher` as `t` on `p`.`tid`=`t`.`id` where `o`.`act`='2' ".$where." order by `o`.`id` desc";
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
			
			$this->load->view("admin/members/caokes.ajax.php",$data);				
		}
		
		//私课购买记录
		public function sikes()
		{
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";
			$this->load->view("admin/members/sikes.php",$data);	
		}
		
		//ajax读取私课购买记录
		public function sikes_ajaxs()
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
				$where.=" and (`u`.`nickname`='$keywords' or `u`.`mobile`='$keywords' or `p`.`id`='$keywords' or `t`.`realname`='$keywords' or `t`.`mobile`='$keywords')";
			}
			if($states!="")
			{
				$where.=" and (`o`.`state`='$states')";		
			}
			if($start_time!="")
			{
				$where.=" and (`o`.`time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`o`.`time`<='$end_time')";
			}
			$sql="select `o`.*,`u`.`nickname`,`u`.`mobile` as `mobile1`,`t`.`mobile` as `mobile2`,`t`.`realname`,`u`.`avatar` as `avatar1`,`t`.`avatar` as `avatar2`,`o`.`uid`,`p`.`tid`,`p`.`date`,`p`.`node` from `dg_orders` as `o` left join `dg_user` as `u` on `o`.`uid`=`u`.`id` left join `dg_tearch_plan_list` as `p` on `o`.`class_id`=`p`.`id` left join `dg_teacher` as `t` on `p`.`tid`=`t`.`id` where `o`.`act`='1' ".$where." order by `o`.`id` desc";
			$sql1="select sum(`o`.`money`) as `price` from `dg_orders` as `o` left join `dg_user` as `u` on `o`.`uid`=`u`.`id` left join `dg_tearch_plan_list` as `p` on `o`.`class_id`=`p`.`id` left join `dg_teacher` as `t` on `p`.`tid`=`t`.`id` where `o`.`act`='1' ".$where." order by `o`.`id` desc";
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
			
			$this->load->view("admin/members/sikes.ajax.php",$data);					
		}
		
		//私课删除功能
		public function sikes_dels()
		{
			echo $this->admins->sikes_dels($this->input->post("id"));	
		}
		
		//会员主页
		public function indexs()
		{
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]="";
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$data["start_time"]=trim($_REQUEST["start_time"]):$data["start_time"]="";
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$data["end_time"]=trim($_REQUEST["end_time"]):$data["end_time"]="";
			isset($_REQUEST["states"]) && trim($_REQUEST["states"])!=""?$data["states"]=trim($_REQUEST["states"]):$data["states"]="";
			isset($_REQUEST["doors"]) && trim($_REQUEST["doors"])!=""?$data["doors"]=trim($_REQUEST["doors"]):$data["doors"]="";
			$this->load->view("admin/members/indexs.php",$data);				
		}
		
		//ajax读取会员信息
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
				$where.=" and (`nickname`='$keywords' or `mobile`='$keywords' or `id`='$keywords' )";
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
				elseif($doors==2)
				{
					$where.=" and (`doors`='0')";	
				}	
			}
			if($end_time!="")
			{
				$where.=" and (`reg_time`<='$end_time')";
			}
			$sql="select * from `dg_user` where `id`>'0' ".$where." order by `id` desc";
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
			
			$this->load->view("admin/members/indexs.ajax.php",$data);			
		}
		
		//会员修改
		public function edits()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_user` where `id`='$id'");
			if($query->num_rows()>0)
			{
				require FCPATH."config/img.inc.php";
				$data["avatar"]=$img_inc["avatar"];
				$data["result"]=$query->row_array();
				$this->load->view("admin/members/indexs.edits.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}	
		}
		
		//会员在线充值
		public function online_pay()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_user` where `id`='$id'");
			if($query->num_rows()>0)
			{
				require FCPATH."config/img.inc.php";
				$data["avatar"]=$img_inc["avatar"];
				$data["result"]=$query->row_array();
				$this->load->view("admin/members/online.pay.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}				
		}	
		
		//会员在线提现
		public function online_draw()
		{
			$data["rs"]=$this->apps->N();
			$id=intval($this->uri->segment(4));
			$query=$this->db->query("select * from `dg_user` where `id`='$id'");
			if($query->num_rows()>0)
			{
				require FCPATH."config/img.inc.php";
				$data["avatar"]=$img_inc["avatar"];
				$data["result"]=$query->row_array();
				$this->load->view("admin/members/online.draw.php",$data);					
			}	
			else
			{
				show_error("The Page Is Not Found!");	
			}				
		}
		
		//会员提现操作
		public function oneline_draw_sub()
		{
			$id=intval($this->uri->segment(4));
			echo $this->admins->oneline_draw_sub($id);	
		}
		
		//会员在线充值
		public function oneline_pay_sub()
		{
			echo $this->admins->oneline_pay_sub(intval($this->uri->segment(4)));	
		}	
		
		//会员修改处理
		public function indexs_updates()
		{
			echo $this->admins->indexs_updates(intval($this->uri->segment(4)));
		}
		
		//会员状态更新
		public function changes()
		{
			echo $this->admins->changes(intval($this->input->post("id")));	
		}
		
		//删除会员信息
		public function dels()
		{
			echo $this->admins->dels(($this->input->post("id")));	
		}
		
		//会员充值记录
		public function pays()
		{
			$data["rs"]=$this->apps->N();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			$this->load->view("admin/members/pays.php",$data);
		}
		
		//ajax读取会员充值记录
		public function pays_ajaxs()
		{
			$pagesize=15;
			$segment=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";
			isset($_REQUEST["froms"]) && trim($_REQUEST["froms"])!=""?$froms=trim($_REQUEST["froms"]):$froms="";		
			isset($_REQUEST["start_time"]) && trim($_REQUEST["start_time"])!=""?$start_time=strtotime(trim($_REQUEST["start_time"])."00:00:00"):$start_time="";	
			isset($_REQUEST["end_time"]) && trim($_REQUEST["end_time"])!=""?$end_time=strtotime(trim($_REQUEST["end_time"])."23:59:59"):$end_time="";	
			$where="";
			
			if($keywords!="")
			{
				$where.=" and (`u`.`nickname`='$keywords' or `u`.`mobile`='$keywords' or `n`.`order_id`='$keywords' or `n`.`trade_index`='$keywords' or `n`.`money`='$keywords')";
			}
			if($froms!="")
			{
				$where.=" and (`n`.`pay_act`='$froms')";		
			}
			if($start_time!="")
			{
				$where.=" and (`n`.`time`>='$start_time')";
			}
			
			if($end_time!="")
			{
				$where.=" and (`n`.`time`<='$end_time')";
			}
			
			$sql="select `n`.*,`u`.`mobile`,`u`.`nickname`,`u`.`avatar` from `dg_pay_order` as `n` left join `dg_user` as `u` on `n`.`uid`=`u`.`id` where `n`.`id`>'0' ".$where." order by `n`.`id` desc";
			
			$sql_100="select sum(`n`.`money`) as `money_1`,sum(`n`.`money_remaining`) as `money_2`  from `dg_pay_order` as `n` left join `dg_user` as `u` on `n`.`uid`=`u`.`id` where `n`.`id`>'0' ".$where." ";
			$query_100=$this->db->query($sql_100);
			$data["result_100"]=$query_100->row_array();
			//print_r($data["result_100"]);
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
			
			$this->load->view("admin/members/pays.ajax.php",$data);				
		}
		
	}