<?php

	//H5页面课程对应的控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Classs extends CI_Controller
	{
		
		public function __construct()
		{
			parent::__construct();	
			$this->load->model("Html5_model","apps");
		}
		
		//课程的图文简介信息
		public function item()
		{
			$id=$this->uri->segment(4);
			$data["result"]=$this->apps->classs_item($id);
			if(is_array($data["result"]))
			{
				$this->load->view("html5/classs_item.php",$data);
			}
			else
			{
				error_show();
			}
		}
		
	}