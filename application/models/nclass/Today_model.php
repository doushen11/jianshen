<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	require APPPATH."models/Cmains_model.php";
	
	class Today_model extends Cmains_model
	{
	
		function __construct()
		{
			parent::__construct();
		}
		
		//已经成立的当日操课
		public function indexs()
		{
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):6;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
			$date=date("Y-m-d");
			$model=1;
			if(isset($_REQUEST["model"]) && in_array(trim($_REQUEST["model"]),array(1,2)))
			{
				$model=trim($_REQUEST["model"]);	
			}
			if($model==1)
			{
				$sql="select  `p`.`loads`,`p`.`max`,`p`.`class_name`,`p`.`node`,`p`.`class_id`,`p`.`id`,`p`.`tid`,`t`.`realname`,`t`.`avatar`,`c`.`bg_file` from `dg_tearch_plans` as `p` ,`dg_class` as `c`,`dg_teacher` as `t` where `p`.`class_id`=`c`.`id` and `p`.`tid`=`t`.`id` and `p`.`tid`>0 and `p`.`class_id`>0 and `p`.`date`='$date' and (`p`.`state`='2' or `p`.`state`='1') order by `p`.`id` asc";
			}
			else
			{
				$sql="select  `p`.`loads`,`p`.`max`,`p`.`class_name`,`p`.`node`,`p`.`class_id`,`p`.`id`,`p`.`tid`,`t`.`realname`,`t`.`avatar`,`c`.`bg_file` from `dg_tearch_plans` as `p` ,`dg_class` as `c`,`dg_teacher` as `t` where `p`.`class_id`=`c`.`id` and `p`.`tid`=`t`.`id` and `p`.`tid`>0 and `p`.`class_id`>0 and `p`.`date`='$date' and (`p`.`state`='2') order by `p`.`id` asc";	
			}
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);
			json_array2("10000","成功",$query->result_array());
		}
		
		//读取今日游客的私教
		public function homes()
		{
			
			$pagesize=isset($_REQUEST["pagesize"]) && is_numeric($_REQUEST["pagesize"])?intval($_REQUEST["pagesize"]):6;
			$segment= isset($_REQUEST["pageindex"]) && is_numeric($_REQUEST["pageindex"])?intval($_REQUEST["pageindex"]):1;	
			$date=date("Y-m-d");
			//$date="2016-08-16";
			$month=date("Y-m");
			//echo $date;
			//$keywords='"state":2,"today":"'.$date.'"';
			
			$sql="select `t`.`realname`,`t`.`avatar`,`t`.`score`,`t`.`desc` as `descs`,`t`.`birthday`,`t`.`level`,`t`.`money_desc`,`p`.`tid`,`p`.`id` from `dg_tearch_plan_list` as `p`,`dg_teacher` as `t` where `p`.`tid`=`t`.`id` and `p`.`date`='$date' and `p`.`state`='2' group by `p`.`tid`";
			$sql=$this->db->page_json($sql,$pagesize,$pagecount,$pageindex,$pageall,$segment);
			$query=$this->db->query($sql);
			$array=array();
			$i=0;
			
			//计算高低峰时间
			$query1=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='2' and `model`='1' order by `min` asc");
			if($query1->num_rows()<=0)
			{
				json_array2("30000","读取信息失败：当前教练没有设置对应的高低峰时间，无法直接读取！","");	
			}
			$query2=$this->db->query("select `min`,`max` from `dg_time_model` where `act`='2' and `model`='2' order by `min` asc");
			if($query2->num_rows()<=0)
			{
				json_array2("30000","读取信息失败：当前教练没有设置对应的高低峰时间，无法直接读取！","");	
			}			
			//print_r($query->result_array());
			foreach($query->result_array() as $arrays)
			{
				$array[$i]=$arrays;
				//计算生日
				$bs=explode("-",$array[$i]["birthday"]);
				$array[$i]["birthday"]=date("Y")-$bs[0];
				//计算高峰低峰价格
				$money=json_decode($array[$i]["money_desc"],true);
				if(is_array($money) && !empty($money) && isset($money["money_peak"]) && isset($money["money_slack"]))
				{
					$array[$i]["money_peak"]=$money["money_peak"];
					$array[$i]["money_slack"]=$money["money_slack"];
					unset($array[$i]["money_desc"]);
				}
				else
				{
					json_array2("10000","抱歉：查询结果中有未设置高峰和低峰价格的教练信息，无法显示，请您稍后再试！","");	
				}
				$array[$i]["time_peak"]=$query1->result_array();
				$array[$i]["time_slack"]=$query2->result_array();
				$qys=$this->db->query("select `frees` from `dg_tearch_plan_list` where `tid_index`='".right_index($arrays["tid"])."' and `tid`='".$arrays["tid"]."' and `frees`!='' and `date`='$date' limit 1");
				if($qys->num_rows()>0)
				{
					$res=$qys->row_array();
					$array[$i]["success"][0]["time"]=$res["frees"];
				}
				else
				{
					$array[$i]["success"][0]["time"]="";	
				}
				//$array[$i]["success"]=$this->read_success_nodes($array[$i]["desc"]);
				unset($array[$i]["desc"]);
				$i++;
			}
			json_array2("10000","成功",$array);
		}
		
		//读取教练可预约节点时间
		/*private function read_success_nodes($desc)
		{
			$arr=json_decode($desc,true);
			$d=date("d")-1;
			if(is_array($arr) && !empty($arr))
			{
				$arrs=array();$a=0;
				$array=$arr[$d]["class"];
				for($i=0;$i<count($array);$i++)
				{
					if($array[$i]["state"]==2)
					{
						$arrs[$a]["time"]=$array[$i]["time"];
						$a++;	
					}	
				}
				return $arrs;
			}
			else
			{
				json_array2("10000","抱歉：教练可预约时间读取出错，无法显示，请您稍后再试！","");		
			}	
		}*/
	}