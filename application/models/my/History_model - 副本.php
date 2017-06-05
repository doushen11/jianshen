<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class History_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//我的充值记录
		public function index($rs)
		{
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
			$sql="select `id`,`money`,`time` from `dg_pay_order` where `uid_index`='".$rs["id"]."' and `uid`='".$rs["id"]."' order by `id` desc";		
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);	
			
			$array=array();$i=0;
			foreach($query->result_array() as $arrays)
			{
				$array[$i]=$arrays;
				$array[$i]["date"]=date("Y-m-d",$arrays["time"]);
				$array[$i]["time"]=date("H:i",$arrays["time"]);
				$i++;		
			}
			
			json_array2("10000","成功",$array);					
		}
		
	}