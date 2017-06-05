<?php
	
	//教练端--我的控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/master/Alls.php";
	
	class Index extends Alls
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("master/my/Index_model","dos");
		}
		
		
		//我的资料
		public function infos()
		{
			if(is_fulls("token"))
			{
				$rs=$this->check_token($_REQUEST["token"],"`id`,`realname`,`gender`,`level`,`birthday`,`desc`,`avatar`");	
				$this->dos->infos($rs);
			}	
			else
			{
				error_show();	
			}				
		}
		
		//我的钱包-主页
		public function mys()
		{
			if(is_fulls("token"))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->mys($rs);
			}	
			else
			{
				error_show();	
			}				
		}
		
		//我的钱包--友情提示
		public function lovelys()
		{
			$this->load->view("html5/lovelys.php");	
		}
		
		//账单明细
		public function lists()
		{
			if(is_fulls("token"))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->lists($rs);
			}	
			else
			{
				error_show();	
			}				
		}
		
		//账单明细详情
		public function listss()
		{
			if(is_fulls("token") && is_fulls("id",1))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->listss($rs,$_REQUEST["id"]);
			}	
			else
			{
				error_show();	
			}				
		}
		
		//提交意见反馈
		public function notes()
		{
			if(is_fulls("token") && is_fulls("content"))
			{
				$rs=$this->check_token($_REQUEST["token"]);	
				$this->dos->notes($rs,$_REQUEST["content"]);
			}	
			else
			{
				error_show();	
			}				
		}
		
		//修改头像
		public function avatars()
		{
			if(is_fulls("token") && isset($_FILES["file"]) && trim($_FILES["file"]["name"])!=""){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token,"`id`");
				echo $this->dos->avatars($rs);
			}else{
				error_show();	
			}				
		}
	}