<?php
	
	//新晋教练控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Home extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("nteach/Home_model","dos");
		}
		
		//获取新晋教练列表方法
		public function index()
		{
			echo $this->dos->index();
		}
		
		//私课教练的详情一
		public function item()
		{
			if(is_fulls("id",1))
			{
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				echo $this->dos->item($id);
			}
			else
			{
				error_show();	
			}	
		}
		
		//私课安排表（一周数据）
		public function classs()
		{
			if(is_fulls("id",1)){
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				echo $this->dos->classs($id);
			}else{
				error_show();	
			}				
		}
		
		//私课预约详情
		public function items()
		{
			if(is_fulls("id",1)){
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				echo $this->dos->items($id);
			}else{
				error_show();	
			}				
		}
		
		//私课预约购买
		public function buy()
		{
			if(is_fulls("id",1) && is_fulls("days") && is_fulls("times") && is_fulls("token")){
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				$days=htmlspecialchars(trim($_REQUEST["days"]));
				$times=htmlspecialchars(trim($_REQUEST["times"]));
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token);
				echo $this->dos->buy($id,$days,$times,$rs);
			}else{
				error_show();	
			}				
		}

		//关注教练
		public function focus()
		{
			if(is_fulls("id",1) && is_fulls("token")){
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token,"`id`");
				echo $this->dos->focus($id,$rs);
			}else{
				error_show();	
			}		
		}
		
		//我关注的教练
		public function collect()
		{
			if(is_fulls("token")){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token,"`id`");
				echo $this->dos->collect($rs);
			}else{
				error_show();	
			}				
		}
		
		//获取操课教练教授的课程
		public function hisclass()
		{
			if(is_fulls("id",1)){
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				echo $this->dos->hisclass($id);
			}else{
				error_show();	
			}				
		}
		
	}