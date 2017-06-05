<?php
	
	//我的分享二维码控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."controllers/client/Mains.php";
	
	class Codes extends Mains
	{
		
		public function __construct()
		{
			parent::__construct();
			//$this->load->model("my/Codes_model","dos");
		}
		
		//分享二维码加载Api
		public function indexs()
		{
			
			
			
			$id=intval($this->uri->segment(4));
			
			if($id>0)
			{
				require FCPATH."phpqrcode.php";
				QRcode::png(http_url()."html5/codes/indexs/".$id,false,"H",100000);	
			}		
			else
			{
				error_shows();		
			}
		}
		
	}