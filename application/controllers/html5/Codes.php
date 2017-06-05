<?php
	
	//二维码h5页面
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Codes extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			//$this->load->model("my/Codes_model","dos");
		}
		
		//分享的h5页面
		public function indexs()
		{
			$data["id"]=intval($this->uri->segment(4));
			$this->load->view("html5/codes.php",$data);
		}
		
	}