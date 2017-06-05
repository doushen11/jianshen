<?php
	
	//我的订单控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Orders extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("my/Order_model","dos");
		}
		
		//我的订单
		public function index()
		{
			if(is_fulls("token")){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token);
				echo $this->dos->index($rs);
			}else{
				error_show();	
			}				
		}
		
		//我的订单（操课）详情
		public function items()
		{
			if(is_fulls("token") && is_fulls("id",1)){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				$rs=$this->check_token($token);
				echo $this->dos->items($rs,$id);
			}else{
				error_show();	
			}				
		}
		
		//我的订单（私课）详情
		public function item()
		{
			if(is_fulls("token") && is_fulls("id",1)){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				$rs=$this->check_token($token);
				echo $this->dos->item($rs,$id);
			}else{
				error_show();	
			}				
		}
		
		//私课取消订单
		public function clears()
		{
			if(is_fulls("token") && is_fulls("id",1)){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				$rs=$this->check_token($token);
				echo $this->dos->clears($rs,$id);
			}else{
				error_show();	
			}				
		}
		
		//操课取消订单
		public function clear()
		{
			if(is_fulls("token") && is_fulls("id",1)){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				$rs=$this->check_token($token);
				echo $this->dos->clear($rs,$id);
			}else{
				error_show();	
			}				
		}
		
		//私课点评
		public function comment()
		{
			if(is_fulls("token") && is_fulls("id",1) && is_fulls("star",1)){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				$star=htmlspecialchars(trim($_REQUEST["star"]));
				$rs=$this->check_token($token);
				echo $this->dos->comment($rs,$id,$star);
			}else{
				error_show();	
			}				
		}
		
		//私课点评
		public function comments()
		{
			if(is_fulls("token") && is_fulls("id",1) && is_fulls("star_a",1) && is_fulls("star_b",1)){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$id=htmlspecialchars(trim($_REQUEST["id"]));
				$star_a=htmlspecialchars(trim($_REQUEST["star_a"]));
				$star_b=htmlspecialchars(trim($_REQUEST["star_b"]));
				$rs=$this->check_token($token);
				echo $this->dos->comments($rs,$id,$star_a,$star_b);
			}else{
				error_show();	
			}				
		}
		
	}