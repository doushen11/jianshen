<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Index_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//客户端首页
		public function index()
		{
			$query=$this->db->query("select `people` from `dg_config` where `id`='1'");
			$result=$query->row_array();
			
			
			$query1=$this->db->query("select `file`,`url` from `dg_shuf` where `act`='1' order by `sort` asc");
			
			$array=array(
				"people"=>$result["people"],
				"files"=>$query1->result_array(),
			);
			
			json_array("10000","成功",$array);
		}
		
		//读取店铺地址的经度纬度接口
		public function address()
		{
			require FCPATH."config/address.inc.php";	
			json_array("10000","成功",$address_array);
		}
		
	}