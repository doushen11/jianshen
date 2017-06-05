<?php
	
	//我的控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Home extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model("my/Home_model","dos");
		}
		
		//退出登录
		public function logout()
		{
			if(is_fulls("token")){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token,"`id`");
				echo $this->dos->logout($rs);
			}else{
				error_show();	
			}				
		}
		
		//读取我的个人资料接口
		public function index()
		{
			if(is_fulls("token")){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$rs=$this->check_token($token,"`id`,`mobile`,`avatar`,`nickname`,`balance`,`gender`,`brithday`,`height`,`weight`,`professional`,`doors`");
				echo $this->dos->index($rs);
			}else{
				error_show();	
			}				
		}
		
		//提交资料信息
		public function subs()
		{
			if(is_fulls("token") && is_fulls("nickname") && is_fulls("gender")){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$nickname=htmlspecialchars(trim($_REQUEST["nickname"]));
				$gender=htmlspecialchars(trim($_REQUEST["gender"]));
				$brithday=isset($_REQUEST["brithday"]) && trim($_REQUEST["brithday"])!=""?htmlspecialchars(trim($_REQUEST["brithday"])):"";
				$height=isset($_REQUEST["height"]) && trim($_REQUEST["height"])!=""?htmlspecialchars(trim($_REQUEST["height"])):"";
				$weight=isset($_REQUEST["weight"]) && trim($_REQUEST["weight"])!=""?htmlspecialchars(trim($_REQUEST["weight"])):"";
				$professional=isset($_REQUEST["professional"]) && trim($_REQUEST["professional"])!=""?htmlspecialchars(trim($_REQUEST["professional"])):"";
				$rs=$this->check_token($token,"`id`,`mobile`,`avatar`,`nickname`,`balance`,`gender`,`brithday`,`height`,`weight`,`professional`");
				echo $this->dos->subs($rs,$nickname,$gender,$brithday,$height,$weight,$professional);
			}else{
				error_show();	
			}				
		}
		
		//更新头像信息
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
		
		//关于我们接口
		public function abouts()
		{
			
			echo $this->dos->abouts(1);
			
		}
		
		//意见反馈提交接口
		public function notes()
		{
			if(is_fulls("token") && is_fulls("content")){
				$token=htmlspecialchars(trim($_REQUEST["token"]));
				$content=htmlspecialchars(trim($_REQUEST["content"]));
				$rs=$this->check_token($token,"`id`");
				echo $this->dos->notes($rs,$content);
			}else{
				error_show();	
			}				
		}
		
	}