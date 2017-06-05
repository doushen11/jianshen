<?php
	
	//新晋课程相关控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Home extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();	
			$this->load->model("nclass/Home_model","apps");
		}
		
		//购买操课
		public function buys()
		{
			if(is_fulls("id",1) && is_fulls("token")){
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token);
				echo $this->apps->buys($id,$rs);
			}else{
				error_show();	
			}				
		}
		
		//新晋课程接口
		public function index()
		{
			echo $this->apps->index();
		}
		
		//课程详情接口
		public function item()
		{
			if(is_fulls("id",1)){
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				echo $this->apps->item($id);
			}else{
				error_show();	
			}				
		}
		
		//课程关注接口
		public function focus()
		{
			if(is_fulls("id",1) && is_fulls("token")){
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token,"`id`");
				echo $this->apps->focus($id,$rs);
			}else{
				error_show();	
			}				
		}
		
		//当前课程对应的教练信息
		public function teachers()
		{
			if(is_fulls("id",1)){
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				echo $this->apps->teachers($id);
			}else{
				error_show();	
			}				
		}
		
		//当前课程对应的近七日计划
		public function classall()
		{
			if(is_fulls("id",1) && is_fulls("rid",1)){
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				$rid=htmlspecialchars(trim($_REQUEST["rid"]));
				echo $this->apps->classall($id,$rid);
			}else{
				error_show();	
			}				
		}
		
		//人气操课信息
		public function joins()
		{
			echo $this->apps->joins();	
		}
		
		//我关注的课程
		public function collect()
		{
			if(is_fulls("token")){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token,"`id`");
				echo $this->apps->collect($rs);
			}else{
				error_show();	
			}				
		}
	}