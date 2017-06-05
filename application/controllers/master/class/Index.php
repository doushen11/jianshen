<?php
	
	//课程相关控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/master/Alls.php";
	
	class Index extends Alls
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("master/class/Index_model","dos");
		}
		
		//我的私刻--新--对应的周期
		public function my_class_aa_date()
		{
			$this->dos->my_class_aa_date();
		}
		
		//我的私刻-详情
		public function my_class_aa_item()
		{
			if(is_fulls("token") && is_fulls("id",1))
			{
				$token=I("token");
				$id=I("id");
				$rs=$this->check_token($token);
				$this->dos->my_class_aa_item($rs,$id);
			}
			else
			{
				error_show();	
			}
		}
		
		//我的私刻-新2017-1-20
		public function my_class_aa()
		{
			if(is_fulls("token"))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->my_class_aa($rs);
			}	
			else
			{
				error_show();	
			}	
		}
		
		//我的私课
		public function my_class_a()
		{
			if(is_fulls("token"))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->my_class_a($rs);
			}	
			else
			{
				error_show();	
			}
		}	
		
		//我的私课详情信息
		public function my_class_a_item()
		{
			if(is_fulls("token") && is_fulls("id",1))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->my_class_a_item($rs,trim($_REQUEST["id"]));
			}	
			else
			{
				error_show();	
			}				
		}
		
		//我的操课-新
		public function my_class_bb()
		{
			if(is_fulls("token"))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->my_class_bb($rs);
			}	
			else
			{
				error_show();	
			}				
		}	
		
		//我的操课参与人数信息-新
		public function my_class_bb_join_item()
		{
			if(is_fulls("token") && is_fulls("id"))
			{
				$id=I("id");
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->my_class_bb_join_item($rs,$id);
			}	
			else
			{
				error_show();	
			}	
		}
		
		//我的操课
		public function my_class_b()
		{
			if(is_fulls("token"))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->my_class_b($rs);
			}	
			else
			{
				error_show();	
			}				
		}
		
		//我的操课详情信息
		public function my_class_b_item()
		{
			if(is_fulls("token") && is_fulls("id",1))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->my_class_b_item($rs,trim($_REQUEST["id"]));
			}	
			else
			{
				error_show();	
			}			
		}
		
		//我的操课参与详情
		public function my_class_b_joins()
		{
			if(is_fulls("token") && is_fulls("id",1))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->my_class_b_joins($rs,trim($_REQUEST["id"]));
			}	
			else
			{
				error_show();	
			}				
		}
		
	}