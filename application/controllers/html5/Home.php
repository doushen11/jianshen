<?php

	//H5页面对应的控制器
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Home extends CI_Controller
	{
		
		public function __construct()
		{
			parent::__construct();	
			$this->load->model("Html5_model","apps");
		}
		
		//充值说明H5页面
		public function pay()
		{
			$data["result"]=$this->apps->reads(1);	
			if(is_array($data["result"]))
			{
				$this->load->view("html5/pay.php",$data);
			}
			else
			{
				error_show();	
			}
		}

		/**
		 * 查看详情页
		 */
		public function show_detail_page() {
		    $array = array();
		    $param = $this->input->get();
		    $array['query'] = $this->apps->show_detail_page($param);
		    $this->load->view("html5/static_page.php",$array);
		}
	}