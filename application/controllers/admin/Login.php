<?php
	//会员登录控制器
	
	//author:recson
	
	//time:2016-5-30 9:00
	
	//QQ:1439294242
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Login extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();	
			$this->load->model("admin/Mains_model","apps");
		}
		
		public function indexs()
		{
			$rs=$this->apps->L();
			if($rs=="faild")
			{
				$this->load->view("admin/login.php");
			}
			else
			{
				header("location:".http_url()."admin/home/indexs");		
			}
				
		}
		
		public function subs()
		{
			echo $this->apps->subs($this->input->post("username"),$this->input->post("passwd"));
		}
			
		public function logouts()
		{
			setcookie("rs_author","",time()-3600*24*3,"/");	
			header("location:".http_url()."admin/login/indexs");
			die();
		}
	}