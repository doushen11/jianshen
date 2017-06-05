<?php
	
	//支付相关Model处理层

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Home_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//我当前享受的折扣信息以及高低峰时间
		public function index($rs)
		{
			$query1=$this->db->query("select `min`,`max` from `dg_time_model` where `model`='1' and `act`='1'");
			$query2=$this->db->query("select `min`,`max` from `dg_time_model` where `model`='2' and `act`='1'");	
			
			$query=$this->db->query("select `money` from `dg_pay_order` where `uid_index`='".right_index($rs["id"])."' and `uid`='".$rs["id"]."' and `money`>'0' order by `id` asc limit 1");
			
			$money=0.01;
			if($query->num_rows()>0)
			{
				//有充值记录
				$result=$query->row_array();
				$money=$result["money"];
			}
			
			$query=$this->db->query("select `money_peak`,`money_slack` from `dg_pay_model` where (`min`<'$money' and `max`>='$money') limit 1");
			if($query->num_rows()>0)
			{
				$result=$query->row_array();
				$result["time_peak"]=$query1->result_array();
				$result["time_slack"]=$query2->result_array();
				json_array(10000,"成功",$result);
			}
			else
			{
				$query=$this->db->query("select `money_peak`,`money_slack` from `dg_pay_model` where (`min`<'$money' and `max`='0') limit 1");
				if($query->num_rows()>0)
				{
					$result=$query->row_array();
					$result["time_peak"]=$query1->result_array();
					$result["time_slack"]=$query2->result_array();
					json_array(10000,"成功",$result);
				}
				else
				{
					json_array(30000,"抱歉：没有读取到对应的高低峰价格，请联系管理员设置","");	
				}
			}
			
		}
	
	}