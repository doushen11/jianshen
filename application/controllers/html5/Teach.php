<?php

	//H5页面对应的控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Teach extends CI_Controller
	{
		
		public function __construct()
		{
			parent::__construct();	
			$this->load->model("Html5_model","apps");
		}
		
		//私课教练图文简介
		public function item()
		{
			$id=$this->uri->segment(4);
			$data["result"]=$this->apps->teach_item($id);
			if(is_array($data["result"]))
			{
				$this->load->view("html5/teach_item.php",$data);
			}
			else
			{
				error_show();
			}
		}
		
	}