<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Codes_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}	
	
		//分享二维码加载Api
		public function index($rs)
		{
			$array["code_id"]=$rs["id"];
			$array["code_imgs"]=http_url()."imgs/codes/indexs/".$rs["id"];
			$array["code_urls"]=http_url()."html5/codes/indexs/".$rs["id"];
			json_array2(10000,"成功",$array);
		}
	}