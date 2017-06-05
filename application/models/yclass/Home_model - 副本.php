<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Home_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//我要约课，所有操课
		public function index()
		{
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
			$sql="select `id`,`name`,`bg_file`,`contents` from `dg_class` order by `join` desc";		
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);	
			json_array2("10000","成功",$query->result_array());				
		}
		
		//所有私课教练--今日可约
		public function teachs()
		{
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):30;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
			$day=isset($_REQUEST["day"]) && trim($_REQUEST["day"])!=""?trim($_REQUEST["day"]):date("Y-m-d");	
			//echo $day;
			$sql="select `tid` from `dg_tearch_plan_list` where `date`='$day' group by `tid`";
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);	
			$array=array();$i=0;
			
			$query1=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='2' and `model`='1' order by `min` asc");
			if($query1->num_rows()>0)
			{
				$peak=$query1->result_array();
			}
			else
			{
				json_array2("30000","读取信息失败：当前教练没有设置对应的高低峰时间，无法直接读取！","");	
			}
			$query2=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='2' and `model`='2' order by `min` asc");
			if($query2->num_rows()>0)
			{
				$slack=$query2->result_array();
			}
			else
			{
				json_array2("30000","读取信息失败：当前教练没有设置对应的高低峰时间，无法直接读取！","");	
			}			
			
			foreach($query->result_array() as $arrays)
			{
				$array[$i]=$arrays;
				//开始计算教练信息
				$query_a=$this->db->query("select `realname`,`avatar`,`birthday`,`level`,`score`,`desc`,`money_desc`,`id` from `dg_teacher` where `id`='".$arrays["tid"]."'");
				if($query_a->num_rows()>0)
				{
					$result_a=$query_a->row_array();
					$money=json_decode($result_a["money_desc"],true);
					$array[$i]=$result_a;
					if(is_array($money) && !empty($money))
					{
						$array[$i]["money_peak"]=$money["money_peak"];
						$array[$i]["money_slack"]=$money["money_slack"];	
						$array[$i]["time_peak"]=$peak;	
						$array[$i]["time_slack"]=$slack;	
						unset($array[$i]["money_desc"]);
					}
					else
					{
						json_array2("30000","抱歉：没有读取到对应教练的高低峰价格信息","");		
					}
				}
				else
				{
					json_array2("30000","抱歉：没有读取到对应的教练信息","");	
				}
				
				//计算今日预约的状态
				$queryz=$this->db->query("select `state` from `dg_tearch_plan_list` where `date`='$day' and `tid_index`='".right_index($array[$i]["id"])."' and `tid`='".$array[$i]["id"]."'");
				$array[$i]["class_state"]=2;
				foreach($queryz->result_array() as $ars)
				{
					if($ars["state"]==2)
					{
						$array[$i]["class_state"]=1;
					}

				}
				
				//计算今日可预约时间
				
				//$arrr=explode("-",$day);
				//$days=$arrr[0]."-".$arrr[1];
				//print_r($array[$i]);
				$result["free_time"]="";	
				$queyrp=$this->db->query("select `frees` from `dg_tearch_plan_list` where `date`='$day' and `tid_index`='".right_index($array[$i]["id"])."' and `tid`='".$array[$i]["id"]."' and `frees`!='' limit 1");
				if($queyrp->num_rows()>0)
				{
					$resultp=$queyrp->row_array();
					$result["free_time"]=$resultp["frees"];
				}
				$array[$i]["free_time"]=$result["free_time"];
				$array[$i]["birthday"]=explode("-",$array[$i]["birthday"]);
				$array[$i]["birthday"]=date("Y")-$array[$i]["birthday"][0];
				$i++;	
			}
			//print_r($array);
			json_array2("10000","成功",$array);
		}
		
	}